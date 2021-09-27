<?php
namespace App\Models\Questions;
use App\Classes\Database;

class Question 
{
    public ?int $id = null;
    public ?string $question_prompt = null;
    public ?string $category_name = null;
    public ?string $survey_id = null;


    public function __construct()
    {
        
    }
    public function setId($id)
    {
        $this->id = $id;
    }

    public function loadData($data)
    {
        if(isset($data['question_id']))
        {
            $this->id = $data['question_id'];
        }
        $this->question_prompt = $data['prompt'];
        $this->category_name = $data['category'];
    }

    public function save($survey_id)
    {
        $db = Database::$db;
        $db->createQuestion($this, $survey_id);
        echo $this->id;

    }

    public function update($survey_id)
    {

    }

}