<?php
namespace App\Controllers;

use App\Classes\UrlRouter;

class UserController
{
    public function __construct()
    {
        
    }

    public function index(UrlRouter $urlRouter)
    {
        $users = $urlRouter->database->getUsers("");

        $urlRouter->renderView('Users/index', [
            'users' => $users
        ]);
    }
}