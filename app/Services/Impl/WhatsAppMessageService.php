<?php 

namespace App\Services\Impl;

use App\Services\MessagesService;
use Twilio\Rest\Client;

class WhatsAppMessageService implements MessagesService
{

    public function send($messageId, $message)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_FROM');

        $twilio = new Client($sid, $token);
        $twilio->messages
                ->create("whatsapp:+6281935307423",
            array(
            "from" => "whatsapp:+14155238886",
            "body" => 'Hari ini adalah jadwal anda untuk adzan'
            )
        );
    }

}