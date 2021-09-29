<?php
namespace App\Controllers;

use App\Classes\UrlRouter;

class EventController
{
    public function __construct()
    {
        
    }

    // Grabs all the events for a given user
    public function index(UrlRouter $urlRouter)
    {
        if( isset($_POST['user_id']))
        {
            $events = $urlRouter->database->getEvents($_POST['user_id']);

            $urlRouter->renderView('Events/index', [
                'events' => $events
            ]);
        }
        else
        {
            $urlRouter->accessDenied();
        }
    }
}