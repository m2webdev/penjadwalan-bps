<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JadwalController extends Controller
{
    function index()
    {
        $jadwal = Jadwal::all();
        return view('jadwal/index', ['jadwals' => $jadwal]);
    }

    function create(Request $request)
    {
        $request->validate([
            'jadwal_tambah' => 'required',
        ]);


        Jadwal::create([
            'type_jadwal' => $request->input('jadwal_tambah'),
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dibuat!');
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'jadwal' => 'required'
        ]);

        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return redirect()->route('jadwal.index')->with('error', 'Jadwal tidak ditemukan.');
        }

        $jadwal->type_jadwal = $request->input('jadwal');
     
        $jadwal->save();
        return redirect()->route('jadwal.index')->with('success', 'Akun berhasil Diubah!');
    }

    function delete($id)
    {
        $jadwal = Jadwal::find($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Pengguna berhasil dihapus');
    }
}
