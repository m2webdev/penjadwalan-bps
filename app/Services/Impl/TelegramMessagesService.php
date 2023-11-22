<?php 

namespace App\Services\Impl;

use App\Services\MessagesService;
use ErrorException;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramMessagesService implements MessagesService
{

    public function send($tgid, $message)
    {
        try {
            $response = Telegram::sendMessage([
                'chat_id' => $tgid,
                'text' => $message
            ]);
            return $response;
        } catch (TelegramResponseException $e) {
            Log::error('Error send message from telegram bot : ' . $e->getMessage());
            throw new ErrorException($e->getMessage());
        }
    }

}