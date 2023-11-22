<?php

namespace App\Http\Controllers;

use App\Models\Penjadwalan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenjadwalanController extends Controller
{
    function index(Request $request)
    {
        $id = $request->id;
        return view('penjadwalan/index', [
            'id' => $id
        ]);
    }

    function create(Request $request)
    {
        $request->validate([
            'user_tambah' => 'required',
        ]);

        $lastJadwal = Penjadwalan::where('jadwal_id', $request->jadwal)->where('is_done', false)->orderBy('urutan', 'DESC')->first(); 
        $urutan = $lastJadwal !== null ? $lastJadwal->urutan + 1 : 1;
        $tanggal = $lastJadwal!== null && $lastJadwal->tanggal_jadwal !== null ? Carbon::parse($lastJadwal->tanggal_jadwal)->addDay() : null;

        Penjadwalan::create([
            'user_id' => $request->input('user_tambah'),
            'jadwal_id' => $request->jadwal,
            'urutan' => $urutan,
            'tanggal_jadwal' => $tanggal
        ]);

        return redirect()->route('penjadwalan.index', ['id' => $request->jadwal])->with('success', 'Penjadwalan berhasil dibuat!');
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'user' => 'required',
            'penjadwalan_tambah' => 'required',
        ]);

        $penjadwalan = Penjadwalan::find($id);
        if (!$penjadwalan) {
            return redirect()->route('penjadwalan.index')->with('error', 'Penjadwalan tidak ditemukan.');
        }

        $penjadwalan->user_id = $request->input('user');
        $penjadwalan->tanggal_jadwal = $request->input('penjadwalan_tambah');
        $penjadwalan->jadwal_id = $request->jadwal;

        $penjadwalan->save();
        return redirect()->route('penjadwalan.index', ['id' => $request->jadwal])->with('success', 'Penjadwalan berhasil Diubah!');
    }

    function delete(Request $request,$id)
    {
        $user = Penjadwalan::find($id);
        $user->delete();

        return redirect()->route('penjadwalan.index', ['id' => $request->jadwal])->with('success', 'Penjadwalan berhasil dihapus');
    }

}
