<?php

namespace App\Http\Middleware;

use App\Models\Jadwal;
use App\Models\Penjadwalan;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JadwalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        foreach (Jadwal::all() as $jadwal) {
            $lastPenjadwalan = Penjadwalan::where('jadwal_id', $jadwal->id)->orderBy('urutan', 'DESC')->first();
            if ($lastPenjadwalan && (strtotime($lastPenjadwalan) < Carbon::now()->timestamp)) {
                $tanggal = Carbon::today();
                $penjadwalans = Penjadwalan::where('jadwal_id', $jadwal->id)->orderBy('urutan')->get();
                foreach ($penjadwalans as $penjadwalan) {
                    if ($tanggal->isSaturday())
                        $tanggal->addDays(2);
                    elseif($tanggal->isSunday())
                        $tanggal->addDay();
                    $penjadwalan->tanggal_jadwal = $tanggal->toDateString();
                    $penjadwalan->save();
                    $tanggal->addDay();
                }
            }
        }
        return $next($request);
    }
}
