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

    public function update()
    {
        $db = Database::$db;
        $db->updateOptions($this);
    }

    public function setId($id)
    {
        $this->id = intval($id);
    }

    public function getId():?int
    {
        return $this->id;
    }

    public function setChoice($choice)
    {
        $this->choice = $choice;
    }

    public function getString():?string
    {
        return $this->choice;
    }

    public function setQuestionId($qid)
    {
        $this->questionId = $qid;
    }

    public function getQuestionId():?int
    {
        return $this->questionId;
    }
}