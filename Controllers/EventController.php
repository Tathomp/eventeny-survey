<?php
namespace App\Controllers;

use App\Classes\UrlRouter;

class EventController
{
    public function __construct()
    {
        
    }

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
            //through out page not found type page
            include __DIR__."/Views/access_denied.php";
        }
    }
}