<?php 

namespace App\Services;

interface MessagesService
{

    function send($messageId, $message);

}