<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Penjadwalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenjadwalanController extends Controller
{
    function index(Request $request)
    {
        $id = $request->id;
        $jadwal = Jadwal::find($id);
        $penjadwalan = Penjadwalan::where('jadwal_id', $id)->get();
        return view('penjadwalan/index', ['penjadwalans' => $penjadwalan, 'jadwal_id' => $jadwal->id]);
    }

    function create(Request $request)
    {
        $request->validate([
            'user_tambah' => 'required',
            'penjadwalan_tambah' => 'required',
        ]);

        Penjadwalan::create([
            'user_id' => $request->input('user_tambah'),
            'jadwal_id' => $request->jadwal,
            'tanggal_jadwal' => $request->input('penjadwalan_tambah'),
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
