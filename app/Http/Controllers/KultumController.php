<?php

namespace App\Http\Controllers;

use App\Models\Kultum;
use App\Models\Penjadwalan;
use Illuminate\Http\Request;

class KultumController extends Controller
{
    
    public function index(Request $request)
    {
        $penjadwalan = Penjadwalan::find($request->id);
        if (!$penjadwalan)
            return back();
        return response()->view('dashboard.kultum-form', [
            'id' => $penjadwalan->jadwal_id,
            'penjadwalan' => $penjadwalan
        ]);
    }

    public function create(Request $request, $id)
    {
        $request->validate([
            'judul' => ['required'],
            'isi' => ['required']
        ]);
        $kultum = null;
        if (isset($request->kultum_id))
            $kultum = Kultum::find($request->kultum_id);
        if ($kultum) {
            $kultum->judul = $request->judul;
            $kultum->isi = $request->isi;
        }
        $kultum = Kultum::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);
        $penjadwalan = Penjadwalan::find($id);
        if ($penjadwalan) {
            $penjadwalan->kultum_id = $kultum->id;
            $penjadwalan->save();
        }
        return back()->with('kultum', 'Kultum berhasil disimpan!');
    }

    public function show(Kultum $kultum)
    {
        return response()->view('dashboard.show-kultum', [
            'kultum' => $kultum
        ]);
    }

}
