<?php

namespace App\Livewire;

use App\Models\Jadwal;
use App\Models\Penjadwalan as ModelsPenjadwalan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Penjadwalan extends Component
{
    public $id;
    public $jadwal_id, $penjadwalans, $pengguna, $tanggal_jadwal;

    public function render()
    {
        $this->jadwal_id = Jadwal::find($this->id)->id;
        $this->penjadwalans = ModelsPenjadwalan::where('jadwal_id', $this->id)->where('is_done', false)->orderBy('tanggal_jadwal')->get();
        if (Jadwal::find($this->id)->type_jadwal == 'adzan' || Jadwal::find($this->id)->type_jadwal == 'Imam Sholat') {
            $this->pengguna = User::where('agama', 'islam')->where('role', 'pengguna')->where('jk', 'laki-laki');
        }else {
            $this->pengguna = User::where('role', 'pengguna')->where('jk', 'laki-laki');
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
            if ($tanggal->isSaturday())
                $tanggal->addDays(2);
            elseif($tanggal->isSunday())
                $tanggal->addDay();
            $penjadwalan->tanggal_jadwal = $tanggal->toDateString();
            $penjadwalan->save();
            $tanggal->addDay();
        }
        $this->dispatch('dismiss-modal');
    }
}
