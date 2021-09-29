<?php
namespace App\Controllers;

use App\Classes\UrlRouter;

class UserController
{
    // List all users (event organizers)
    public function index(UrlRouter $urlRouter)
    {
        $users = $urlRouter->database->getUsers("");

        $urlRouter->renderView('Users/index', [
            'users' => $users
        ]);
    }
}