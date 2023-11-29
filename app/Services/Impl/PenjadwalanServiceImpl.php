<?php 

namespace App\Services\Impl;

use App\Helper\JadwalType;
use App\Models\Jadwal;
use App\Models\Penjadwalan;
use App\Services\MessagesService;
use App\Services\PenjadwalanService;
use Carbon\Carbon;

class PenjadwalanServiceImpl implements PenjadwalanService
{
    private MessagesService $messageService;

    public function __construct(MessagesService $messagesService)
    {
        $this->messageService = $messagesService;
    }

    public function setNextSchedule()
    {
        foreach (Jadwal::all() as $jadwal) {
            $penjadwalan = Penjadwalan::where('jadwal_id', $jadwal->id)->where('is_done', false)->orderBy('tanggal_jadwal', 'DESC')->first();
            $tanggal = Carbon::today();
            if ($penjadwalan && (Carbon::parse($penjadwalan->tanggal_jadwal)->timestamp < $tanggal->timestamp)) {
                $penjadwalans = Penjadwalan::where('jadwal_id', $jadwal->id)->where('is_done', false)->orderBy('urutan')->get();
                foreach ($penjadwalans as $penjadwalan) {
                    $tipe_jadwal = strtolower($penjadwalan->jadwal->type_jadwal);
                    if ($tipe_jadwal == strtolower(JadwalType::KULTUM) || $tipe_jadwal == strtolower(JadwalType::SENAM)) {
                        if (!$tanggal->isFriday())
                            $tanggal = $tanggal->next(Carbon::FRIDAY);
                    } else {
                        if ($tanggal->isSaturday())
                            $tanggal->addDays(2);
                        elseif($tanggal->isSunday())
                            $tanggal->addDay();
                    }
                    $penjadwalan->is_done = true;
                    $penjadwalan->save();
                    Penjadwalan::create([
                        'user_id' => $penjadwalan->user_id,
                        'jadwal_id' => $penjadwalan->jadwal_id,
                        'urutan' => $penjadwalan->urutan,
                        'tanggal_jadwal' => $tanggal->toDateString()
                    ]);
                    if ($tipe_jadwal == strtolower(JadwalType::KULTUM) || $tipe_jadwal == strtolower(JadwalType::SENAM))
                        $tanggal->addWeek();
                    else 
                        $tanggal->addDay();
                }
            }
        }
    }

    public function sendNotificationAlert()
    {
        foreach (Jadwal::all() as $jadwal) {
            $allPenjadwalan = Penjadwalan::where('jadwal_id', $jadwal->id)->whereDate('tanggal_jadwal', Carbon::today()->toDateString())->get();
            foreach ($allPenjadwalan as $penjadwalan) {
                $telegram_id = $penjadwalan->user->telegram_id;
                $message = 'Hari ini adalah jadwal anda untuk ' . $jadwal->type_jadwal;
                if ($telegram_id)
                    $this->messageService->send($telegram_id, $message);
            }
        }
    }

}