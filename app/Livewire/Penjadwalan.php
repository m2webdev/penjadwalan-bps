<?php

namespace App\Livewire;

use App\Helper\JadwalType;
use App\Models\Jadwal;
use App\Models\Kultum;
use App\Models\Penjadwalan as ModelsPenjadwalan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class Penjadwalan extends Component
{
    public $id;
    public $jadwal_id, $penjadwalans, $pengguna, $tanggal_jadwal, $is_create_kultum, $penjadwalan, $kultum_id, $judul, $isi;

    public function mount()
    {
        $this->is_create_kultum = false;
    }

    public function updated($field)
    {
        if ($field == 'judul' || $field == 'isi')
            $this->validateKultumForm();
    }

    public function render()
    {
        $this->jadwal_id = Jadwal::find($this->id)->id;
        $this->penjadwalans = ModelsPenjadwalan::where('jadwal_id', $this->id)->where('is_done', false)->orderBy('tanggal_jadwal')->get();
        $tipe_jadwal = Jadwal::find($this->id)->type_jadwal;
        if (Str::startsWith(strtolower($tipe_jadwal), 'adzan') || Str::startsWith(strtolower($tipe_jadwal), 'imam')) {
            $this->pengguna = User::where('agama', 'islam')->where('role', 'pengguna')->where('jk', 'laki-laki');
        }else {
            $this->pengguna = User::where('role', 'pengguna');
        }
        $this->pengguna = $this->pengguna->whereDoesntHave('jadwals', function($query) {
            $query->where('jadwal_id', $this->jadwal_id);
        })->get();
        return view('livewire.penjadwalan');
    }

    public function toUp($id)
    {
        DB::transaction(function() use ($id) {
            $penjadwalan = ModelsPenjadwalan::find($id);
            $currentUrutan = $penjadwalan->urutan;
            $currentTanggal = $penjadwalan->tanggal_jadwal;
            $prevPenjadwalan = ModelsPenjadwalan::where('jadwal_id', $penjadwalan->jadwal_id)->where('is_done', false)->where('urutan', $currentUrutan - 1)->first();
            $penjadwalan->urutan = $prevPenjadwalan->urutan;
            $penjadwalan->tanggal_jadwal = $prevPenjadwalan->tanggal_jadwal;
            $penjadwalan->save();
            $prevPenjadwalan->urutan = $currentUrutan;
            $prevPenjadwalan->tanggal_jadwal = $currentTanggal;
            $prevPenjadwalan->save();
        });
    }

    public function toDown($id)
    {
        DB::transaction(function() use ($id) {
            $penjadwalan = ModelsPenjadwalan::find($id);
            $currentUrutan = $penjadwalan->urutan;
            $currentTanggal = $penjadwalan->tanggal_jadwal;
            $nextPenjadwalan = ModelsPenjadwalan::where('jadwal_id', $penjadwalan->jadwal->id)->where('is_done', false)->where('urutan', $currentUrutan + 1)->first();
            $penjadwalan->urutan = $nextPenjadwalan->urutan;
            $penjadwalan->tanggal_jadwal = $nextPenjadwalan->tanggal_jadwal;
            $penjadwalan->save();
            $nextPenjadwalan->urutan = $currentUrutan;
            $nextPenjadwalan->tanggal_jadwal = $currentTanggal;
            $nextPenjadwalan->save();
        });
    }

    public function setScheduleDate()
    {
        if ($this->tanggal_jadwal == null)
            return;
        $tanggal = Carbon::create($this->tanggal_jadwal);
        $penjadwalans = ModelsPenjadwalan::where('jadwal_id', $this->jadwal_id)->where('is_done', false)->orderBy('urutan')->get();
        foreach ($penjadwalans as $penjadwalan) {
            $tipe_jadwal = strtolower($penjadwalan->jadwal->type_jadwal);
            if ($tipe_jadwal == strtolower(JadwalType::KULTUM) || $tipe_jadwal == strtolower(JadwalType::SENAM)) {
                if (!$tanggal->isFriday()) {
                    $this->dispatch('dismiss-modal');
                    session()->flash('error', 'jadwal untuk '. $tipe_jadwal .' harus hari jumat');
                    return;
                }
                $penjadwalan->tanggal_jadwal = $tanggal->toDateString();
                $penjadwalan->save();
                $tanggal->addWeek();
            } else {
                if ($tanggal->isSaturday())
                    $tanggal->addDays(2);
                elseif($tanggal->isSunday())
                    $tanggal->addDay();
                $penjadwalan->tanggal_jadwal = $tanggal->toDateString();
                $penjadwalan->save();
                $tanggal->addDay();
            }
        }
        $this->dispatch('dismiss-modal');
    }

    public function createKultum($id)
    {
        $penjadwalan = ModelsPenjadwalan::find($id);
        if ($penjadwalan == null)
            return;
        $this->is_create_kultum = true;
        $this->penjadwalan = $penjadwalan;
        $kultum = $this->penjadwalan->kultum;
        if ($kultum) {
            $this->kultum_id = $kultum->id;
            $this->judul = $kultum->judul;
            $this->isi = $kultum->isi;
        }
    }

    public function saveKultum()
    {
        $this->validateKultumForm();
        if ($this->kultum_id) {
            $kultum = Kultum::find($this->kultum_id);
            $kultum->judul = $this->judul;
            $kultum->isi = $this->isi;
            $kultum->save();
        } else {
            $kultum = Kultum::create([
                'judul' => $this->judul,
                'isi' => $this->isi,
            ]);
            $this->penjadwalan->kultum_id = $kultum->id;
            $this->penjadwalan->save();
        }
        session()->flash('kultum', 'Kultum berhasil disimpan!');
    }

    public function validateKultumForm()
    {
        $this->validate([
            'judul' => ['required'],
            'isi' => ['required'],
        ]);
    }

    public function showAll()
    {
        $this->is_create_kultum = false;
        $this->clearKultumForm();
    }

    private function clearKultumForm()
    {
        $this->penjadwalan = null;
        $this->judul = null;
        $this->isi = null;
        $this->clearValidation(['judul', 'isi']);
    }
}
