<?php

namespace App\Models;
use App\Classes\Database;

class Response 
{
    public ?int $id = null;
    public ?string $uuid = null;
    public ?int $question_id = null;
    public ?string $reply = null;

    public function __construct()
    {
        
    }

    public function loadData($question, $reply, $uuid)
    {
        $this->question_id =  intval(explode("-", $question)[0]);
        $this->reply = $reply;
        $this->uuid = $uuid;
    }

    public function save()
    {
        $db = Database::$db;
        $db->createResponse($this);
    }
}