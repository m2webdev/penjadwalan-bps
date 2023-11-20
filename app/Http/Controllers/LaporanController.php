<?php

namespace App\Http\Controllers;

use App\Models\Penjadwalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LaporanController extends Controller
{   

    public function index()
    {
        return response()->view('laporan.index');
    }

    public function download()
    {
        $penjadwalan = Penjadwalan::all();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('laporan.penjadwalan', [
            'penjadwalan' => $penjadwalan
        ]);
        return $pdf->stream();
    }
}