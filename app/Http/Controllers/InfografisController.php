<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use App\Models\Penjadwalan;
use Illuminate\Http\Request;

class InfografisController extends Controller
{
    
    public function index(Request $request)
    {
        $penjadwalan = Penjadwalan::find($request->id);
        if (!$penjadwalan)
            return back();
        return response()->view('dashboard.infografis', [
            'penjadwalan' => $penjadwalan
        ]);
    }

    public function create(Request $request, $id)
    {
        $request->validate([
            'judul' => ['required'],
            'isi' => ['required']
        ]);
        $infografis = null;
        if (isset($request->infografis_id))
            $infografis = Infografis::find($request->infografis_id);
        if ($infografis) {
            $infografis->judul = $request->judul;
            $infografis->isi = $request->isi;
        }
        $infografis = Infografis::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);
        $penjadwalan = Penjadwalan::find($id);
        if ($penjadwalan) {
            $penjadwalan->infografis_id = $infografis->id;
            $penjadwalan->save();
        }
        return back()->with('infografis', 'Infografis berhasil disimpan!');
    }

}