<?php 

namespace App\Services\Impl;

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