<?php

namespace App\Http\Controllers;

use App\Models\Penjadwalan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{   

    public function index()
    {
        return response()->view('laporan.index');
    }

    public function showPdfView()
    {
        return response()->view('laporan.penjadwalan', [
            'penjadwalan' => $this->getPenjadwalanByMonthAndTye(Penjadwalan::orderBy('tanggal_jadwal')->get())
        ]);
    }

    public function download()
    {
        $allPenjadwalan = session('penjadwalan');
        if ($allPenjadwalan == null)
            return back();
        $pdf = Pdf::loadView('laporan.penjadwalan', [
            'penjadwalan' => $this->getPenjadwalanByMonthAndTye($allPenjadwalan)
        ]);
        return $pdf->download('laporan.pdf');
    }

    private function getPenjadwalanByMonthAndTye($allPenjadwalan)
    {
        $allPenjadwalanByMonthAndType = [];
        foreach ($allPenjadwalan as $penjadwalan) {
            $bulan = Carbon::parse($penjadwalan->tanggal_jadwal)->translatedFormat('F');
            $tipe = $penjadwalan->jadwal->type_jadwal;
            $allPenjadwalanByMonthAndType[$bulan][$tipe][$penjadwalan->user_id][] = $penjadwalan;
            $allPenjadwalanByMonthAndType[$bulan][$tipe]['all'][] = $penjadwalan;
        }
        return $allPenjadwalanByMonthAndType;
    }

}