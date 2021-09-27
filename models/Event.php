<?php

namespace App\Models;

class Event 
{
    public ?int $id = null;
    public ?string $eventName = null;

    public function load($data)
    {
        $this->id = $data['id'] ?? null;
        $this->eventName = $data['name'] ?? null;

    }
}