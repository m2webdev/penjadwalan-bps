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
                'jumlah_mobil_tersedia' => Jadwal::count(),
                'jumlah_mobil_tidak_tersedia' => Penjadwalan::count(),
                'jumlah_peminjaman' => Penjadwalan::count(),
            ];

        
            return view('dashboard.admin', ['laporan' => $laporan]);
        }
        else
        {
           return view('dashboard.user_index');
        }
    }
}
