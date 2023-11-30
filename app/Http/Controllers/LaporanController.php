<?php

namespace App\Http\Controllers;

use App\Helper\JadwalType;
use App\Models\Penjadwalan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

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

    public function download($type)
    {
        $allPenjadwalan = session('penjadwalan');
        if ($allPenjadwalan == null)
            return back();
        $pdf = Pdf::loadView('laporan.penjadwalan', [
            'penjadwalan' => $this->getPenjadwalanByMonthAndTye($allPenjadwalan),
            'type' => $type
        ]);
        if (strtolower($type) == strtolower(JadwalType::INFOGRAFIS))
            $pdf->setPaper('a4', 'portrait');
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