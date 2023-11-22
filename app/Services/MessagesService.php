<?php 

namespace App\Services;

interface MessagesService
{

    function send($tgid, $message);

}