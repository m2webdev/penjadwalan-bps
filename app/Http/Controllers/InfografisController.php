<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use App\Models\Penjadwalan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

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
        if (isset($request->infografis_id)) {
            $infografis = Infografis::find($request->infografis_id);
            if ($infografis) {
                $infografis->judul = $request->judul;
                $infografis->isi = $request->isi;
                $infografis->save();
            }
        } else {
            $infografis = Infografis::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
            ]);
        }
        $penjadwalan = Penjadwalan::find($id);
        if ($penjadwalan) {
            $penjadwalan->infografis_id = $infografis->id;
            $penjadwalan->save();
        }
        return back()->with('infografis', 'Infografis berhasil disimpan!');
    }

    public function uploadImg(Request $request, $id)
    {
        $request->validate([
            'gambar' => ['required', File::image()->max('3mb')]
        ]);
        $fileName = now()->timestamp . '.jpg';
        $request->gambar->storePubliclyAs('public/infografis', $fileName);
        $infografis = Infografis::find($id);
        if($infografis) {
            $infografis->gambar = $fileName;
            $infografis->save();
        }
        return back()->with('infografis', 'Berhasil mengupload gambar');
    }

    public function show(Infografis $infografis)
    {
        return response()->view('dashboard.show-infografis', [
            'infografis' => $infografis,
        ]);
    }

}