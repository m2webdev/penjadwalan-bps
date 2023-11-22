<?php

namespace App\Livewire;

use App\Models\Jadwal;
use App\Models\Penjadwalan;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Laporan extends Component
{
    public $is_custom, $tahun, $bulan, $tipe_jadwal, $dari_tanggal, $sampai_tanggal;
    public $allTahun, $allBulan, $allJadwalType;

    public function mount()
    {
        $this->is_custom = false;
        $this->tahun = 'semua';
        $this->bulan = 'semua';
        $this->tipe_jadwal = 'semua';
    }

    public function updated($field)
    {
        if ($field == 'tahun')
            $this->bulan = 'semua';
    }

    public function render()
    {
        $this->allTahun = $this->getAllTahun();
        $this->allBulan = $this->getAllBulan($this->tahun);
        $this->allJadwalType = $this->getAllJadwal();
        return view('livewire.laporan');
    }

    public function templateLaporan()
    {
        $this->is_custom = false;
        $this->clearModel();
    }

    public function customLaporan()
    {
        $this->is_custom = true;
        $this->clearModel();
    }

    private function clearModel()
    {
        $this->tahun = 'semua';
        $this->bulan = 'semua';
        $this->tipe_jadwal = 'semua';
        $this->dari_tanggal = '';
        $this->sampai_tanggal = '';
    }

    public function downloadLaporanPdf()
    {
        if ($this->is_custom)
            $this->validate([
                'dari_tanggal' => ['required'],
                'sampai_tanggal' => ['required'],
                'tipe_jadwal' => ['required']
            ]);
        else
            $this->validate([
                'tahun' => ['required'],
                'bulan' => ['required'],
                'tipe_jadwal' => ['required']
            ]);
        $allPenjadwalan = $this->getAllPenjadwalan();
        if (count($allPenjadwalan) == 0)
            throw ValidationException::withMessages([
                'penjadwalan' => 'Data penjadwalan kosong'
            ]);
        return redirect()->route('laporan.download')->with('penjadwalan', $allPenjadwalan);
    }

    private function getAllPenjadwalan()
    {
        $allPenjadwalan = Penjadwalan::select('*');
        if ($this->is_custom) {
            $start = Carbon::parse($this->dari_tanggal);
            $end = Carbon::parse($this->sampai_tanggal);
            $allPenjadwalan->whereBetween('tanggal_jadwal', [$start, $end]);
            if ($this->tipe_jadwal !== 'semua')
                $allPenjadwalan->whereHas('jadwal', function($jadwal) {
                    $jadwal->where('type_jadwal', $this->tipe_jadwal);
                });
        } else {
            if ($this->tahun !== 'semua') 
                $allPenjadwalan->whereYear('tanggal_jadwal', $this->tahun);
            if ($this->bulan !== 'semua') {
                $month = Carbon::parse($this->bulan)->month;
                $allPenjadwalan->whereMonth('tanggal_jadwal', $month);
            }
            if ($this->tipe_jadwal !== 'semua') {
                $allPenjadwalan->whereHas('jadwal', function($jadwal) {
                    $jadwal->where('type_jadwal', $this->tipe_jadwal);
                });
            }
        }
        return $allPenjadwalan->orderBy('tanggal_jadwal')->get();
    }

    private function getAllTahun()
    {
        return Penjadwalan::select(DB::raw('DATE_FORMAT(tanggal_jadwal, "%Y") AS tahun'))->distinct()->get();
    }

    private function getAllBulan($tahun = null)
    {
        if ($tahun == null)
            return Penjadwalan::whereYear('tanggal_jadwal', Carbon::today()->year)->select(DB::raw('DATE_FORMAT(tanggal_jadwal, "%M") AS bulan'))->distinct()->get();
        else if($tahun == 'semua')
            return Penjadwalan::select(DB::raw('DATE_FORMAT(tanggal_jadwal, "%M") AS bulan'))->distinct()->get();
        else
            return Penjadwalan::whereYear('tanggal_jadwal', $tahun)->select(DB::raw('DATE_FORMAT(tanggal_jadwal, "%M") AS bulan'))->distinct()->get();
    }

    private function getAllJadwal()
    {
        return Jadwal::select('type_jadwal')->get();
    }
}
