<?php

namespace App\Livewire;

use App\Models\Jadwal;
use App\Models\Penjadwalan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
