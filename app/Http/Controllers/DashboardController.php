<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Penjadwalan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $laporan = [
                'jumlah_pengguna' => User::count(),
                'jumlah_jadwal' => Jadwal::count(),
                'jumlah_penjadwalan' => Penjadwalan::count(),
            ];


            return view('dashboard.admin', ['laporan' => $laporan]);
        } else {
            $penjadwalan = Penjadwalan::all();
            $jadwal = Jadwal::all();

            return view('dashboard.user_index', ['jadwals' => $jadwal, 'penjadwalan' => $penjadwalan, 'user' => $user]);
        }
    }
}
