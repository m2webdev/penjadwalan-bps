<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Penjadwalan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    function index()
    {
        $user = Auth::user();

        if (auth()->check() && ($user->role === 'super-admin' || $user->role === 'admin')) {
            $laporan = [
                'jumlah_pengguna' => User::count(),
                'jumlah_jadwal' => Jadwal::count(),
                'jumlah_penjadwalan' => Penjadwalan::count(),
                'allPenjadwalan' => Penjadwalan::whereDate('tanggal_jadwal', Carbon::today()->toDateString())->get(),
            ];


            return view('dashboard.admin', ['laporan' => $laporan]);
        } else {
            $penjadwalan = Penjadwalan::orderBy('tanggal_jadwal', 'DESC')->get();
            $penjadwalanHariIni = Penjadwalan::whereDate('tanggal_jadwal', Carbon::today()->toDateString())->get();
            $jadwal = Jadwal::all();

            return view('dashboard.user_index', [
                'jadwals' => $jadwal, 
                'penjadwalan' => $penjadwalan, 
                'user' => $user,
                'penjadwalanHariIni' => $penjadwalanHariIni
            ]);
        }
    }
}
