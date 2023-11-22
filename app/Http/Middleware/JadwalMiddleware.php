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
            $penjadwalan = Penjadwalan::where('jadwal_id', $jadwal->id)->where('is_done', false)->orderBy('tanggal_jadwal', 'DESC')->first();
            if ($penjadwalan && (Carbon::parse($penjadwalan->tanggal_jadwal)->timestamp < Carbon::now()->timestamp)) {
                $tanggalAfterLastPenjadwalan = Carbon::parse($penjadwalan->tanggal_jadwal)->addDay();
                $tanggal = $tanggalAfterLastPenjadwalan->timestamp < Carbon::today()->timestamp ? Carbon::today() : $tanggalAfterLastPenjadwalan;
                $penjadwalans = Penjadwalan::where('jadwal_id', $jadwal->id)->where('is_done', false)->orderBy('urutan')->get();
                foreach ($penjadwalans as $penjadwalan) {
                    if ($tanggal->isSaturday())
                        $tanggal->addDays(2);
                    elseif($tanggal->isSunday())
                        $tanggal->addDay();
                    $penjadwalan->is_done = true;
                    $penjadwalan->save();
                    Penjadwalan::create([
                        'user_id' => $penjadwalan->user_id,
                        'jadwal_id' => $penjadwalan->jadwal_id,
                        'urutan' => $penjadwalan->urutan,
                        'tanggal_jadwal' => $tanggal->toDateString()
                    ]);
                    $tanggal->addDay();
                }
            }
        }
        return $next($request);
    }
}
