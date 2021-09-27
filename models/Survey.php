<?php

namespace App\Models;
use App\Classes\Database;
use App\Models\Questions\Question;
use App\Models\Questions\QuestionOptions;

class Survey 
{
    public ?int $id = null;
    public ?string $surveyName = "";
    public ?string $surveyCategory = null;
    public ?string $primaryColor = null;
    public ?string $couponCode = null;

    public array $question = array();

    public function __construct()
    {

    }
    public function loadDataFromDB($queryData)
    {
        $this->surveyName = $queryData[0]['name'];
        $this->surveyCategory = $queryData[0]['survey_category'];

        foreach ($queryData as $data)
        {

            if($data["category"] == "Radio"
                || $data["category"] == "Checkbox"
                || $data["category"] == "Drop Down")
            {
                $arrCount = count($this->question);
                if($arrCount > 0)
                {
                    if($this->question[$arrCount - 1]->id == $data['question_id'])
                    {
                        $this->question[$arrCount - 1]->addOption($data);
                        continue;
                    }
                }

                $q = new QuestionOptions();
                $q->loadDataFromDB($data);
                $this->question[] = $q;
            }
            else
            {
                $q = new Question();
                $q->loadData($data);
                $this->question[] = $q;
            }
        }
    }


    public function loadData($data)
    {
        $this->surveyName = $data['survey-name'];
        $this->surveyCategory = $data['survey-category'];
        $this->primaryColor = $data['primary'];

        foreach($data['question'] as $question)
        {
            if(isset($question['option']))
            {
                // Question has options
                $q = new QuestionOptions();
                $q->loadData($question);
            }
            else
            {
                $q = new Question();
                $q->loadData($question);
            }

            $this->question[] = $q;

        }
        
    }

    public function save()
    {
        $db = Database::$db;
        $surv_id = $db->createSurvey($this);

        foreach($this->question as $q)
        {
            $q->save($surv_id);
        }
    }

    public function update()
    {
        $db = Database::$db;
        $surv_id = $db->updateSurvey($this);

        foreach($this->question as $q)
        {
            $q->update($surv_id);
        }
    }

}