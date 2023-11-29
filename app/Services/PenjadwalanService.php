<?php 

namespace App\Services;

interface PenjadwalanService
{

    function setNextSchedule();

    function sendNotificationAlert();

}