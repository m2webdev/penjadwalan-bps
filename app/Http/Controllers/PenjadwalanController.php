<?php

namespace App\Http\Controllers;

use App\Models\Penjadwalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenjadwalanController extends Controller
{
    function index()  
    {
        $penjadwalan = Penjadwalan::all();
        return view('penjadwalan/index', ['penjadwalans' => $penjadwalan]);
    }

    function create(Request $request)
    {
        $request->validate([
            'jadwal_tambah' => 'required',
            'user_tambah' => 'required',
            'penjadwalan_tambah' => 'required',
        ]);

        Penjadwalan::create([
            'user_id' => $request->input('jadwal_tambah'),
            'jadwal_id' => $request->input('user_tambah'),
            'tanggal_jadwal' => $request->input('penjadwalan_tambah'),
        ]);

        return redirect()->route('penjadwalan.index')->with('success', 'Penjadwalan berhasil dibuat!');
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'jk' => 'required',
            'role' => 'required',
        ]);

        $user = Penjadwalan::find($id);
        if (!$user) {
            return redirect()->route('penjadwalan.index')->with('error', 'Penjadwalan tidak ditemukan.');
        }

        $user->name = $request->input('name');
        $user->jk = $request->input('jk');
        $user->role = $request->input('role');
        if($request->password != null)
        {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        return redirect()->route('penjadwalan.index')->with('success', 'Penjadwalan berhasil Diubah!');
    }

    function delete($id)
    {
        $user = Penjadwalan::find($id);
        $user->delete();

        return redirect()->route('penjadwalan.index')->with('success', 'Penjadwalan berhasil dihapus');
    }
}
