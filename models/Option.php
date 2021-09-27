<?php
namespace App\Models;

use App\Classes\Database;

class Option 
{
    public ?int $id = null;
    public ?string $choice;
    public ?int $questionId;

    public function __construct()
    {
        
    }

    public function loadData($data)
    {
        $this->id = $data['options_id'];
        $this->choice = $data['choice'];
        $this->questionId = $data['question_id'];
    }

    public function save($questionId)
    {
        $this->questionId = $questionId; //we might not need this
        $db = Database::$db;
        $db->createOption($this);
    }

}