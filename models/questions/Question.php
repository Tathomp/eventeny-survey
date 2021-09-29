<?php
namespace App\Models\Questions;
use App\Classes\Database;

class Question 
{
    public ?int $id = null;
    public ?string $question_prompt = null;
    public ?string $category_name = null;
    public ?string $survey_id = null;
    public ?bool $required = null;

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

        if(isset($data['required']))
        {
            $this->required = filter_var($data['required'], FILTER_VALIDATE_BOOLEAN);
        }
        else
        {
            $this->required = false;
        }

    }

    public function save($survey_id)
    {
        $db = Database::$db;
        $db->createQuestion($this, $survey_id);
        echo $this->id;

    }

    public function update()
    {
        $db = Database::$db;
        $db->updateQuestion($this);
    }

    public function getRequired()
    {
        return $this->required;
    }


}