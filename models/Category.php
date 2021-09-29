<?php
// I think we can delete this. Not sure we're ever using this
namespace App\Models;

class Category 
{
    public ?int $id = null;
    public ?string $title = null;
    public ?int $event_id = null;

    public function loadData($data)
    {

    }

    public function saveData()
    {

    }

    public function updateData()
    {

    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setEventId($eventId)
    {
        $this->id = $eventId;
    }

    public function getEventId(): ?int
    {
        return $this->event_id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }



}