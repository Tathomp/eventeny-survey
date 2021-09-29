<?php

namespace App\Classes;

use App\Models\Survey;
use PDO;
use PDOException;

class Database
{
    public $pdo = null;
    public static ?Database $db = null; // This will be the access point for controllers

    public function __construct()
    {
        try
        {
            // Should this be in some kind of enviromental variable?
            $this->pdo = new PDO('mysql:host=localhost;port=3306;dbname=survey_db', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db = $this;
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }

    }

    //Events
    public function getEvents($userId)
    {
        try
        {
            $statement = $this->pdo->prepare('SELECT * FROM events WHERE events.organizer = :userId');
            $statement->bindValue(":userId", "$userId");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }

    }

    //Users
    public function getUsers()
    {
        try
        {
            $statement = $this->pdo->prepare('SELECT * FROM users');
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }
    }


    //Survey
    public function getSurveys($eventId)
    {
        try
        {
            $statement = $this->pdo->prepare('SELECT * FROM survey WHERE survey.event_id = :eventId');
            $statement->bindValue(":eventId", "$eventId");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }
    }

    public  function getSurvey($surveyId)
    {
        try
        {
            $statement = $this->pdo->prepare('SELECT * FROM survey WHERE survey.event_id = :eventId');
            $statement->bindValue(":eventId", "$eventId");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }

    }

    public function getSur($surveyId)
    {
        try
        {
            $statement = $this->pdo->prepare('SELECT * FROM survey WHERE survey.id = :surveyId');
            $statement->bindValue(":surveyId", "$surveyId");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }

    }

    public function getQuestionsForSurvey($surveyId)
    {
        try
        {
            $statement = $this->pdo->prepare('
                SELECT survey.name, survey_category.name as survey_category, survey.primary_color as primary_color, questions.id as question_id, questions.prompt, 
                question_category.category_name as category, questions.required as required, options.id as options_id, options.choice
                FROM survey 
                LEFT JOIN questions ON survey.id = questions.survey_id 
                LEFT JOIN options ON options.question_id = questions.id
                LEFT JOIN question_category ON questions.category = question_category.id 
                LEFT JOIN survey_category ON survey.category = survey_category.id 
                
                WHERE survey.id = :surveyId;
                ');

            $statement->bindValue(":surveyId", "$surveyId");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }
    }

    public  function updateSurvey(Survey $surveyModel)
    {
        try
        {
            $statement = $this->pdo->prepare("UPDATE survey 
                SET name=:survName, primary_color=:color, coupon_id=:coupon, event_id=:event_id,   
                    category=(SELECT id FROM survey_category WHERE name = :category)
                WHERE id=:surveyID");
            $statement->bindValue(":surveyID", $surveyModel->id);
            $statement->bindValue(":survName", $surveyModel->surveyName);
            $statement->bindValue(":color", $surveyModel->primaryColor);
            $statement->bindValue(":category", $surveyModel->surveyCategory);
            $statement->bindValue(":coupon", 1);
            $statement->bindValue(":event_id", $_SESSION['curr_event']);
            $statement->execute();
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }
    }

    public function createSurvey($survey)
    {
        try
        {
            $statement = $this->pdo->prepare("INSERT INTO survey (name, event_id, primary_color, coupon_id, category) 
            VALUES (:title, :event_id, :primary_color, :coupon,
            (SELECT id FROM survey_category WHERE name = :category)) ");
            $statement->bindValue(':title', $survey->surveyName);
            $statement->bindValue(':event_id', $_SESSION['curr_event']);
            $statement->bindValue(':category',$survey->surveyCategory);
            $statement->bindValue(':primary_color',$survey->primaryColor);
            $statement->bindValue(':coupon', intval($survey->getCouponId()));
            $statement->execute();

            return $this->pdo->lastInsertId();
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }
    }

    public function deleteSurvey($sureyId)
    {
        try
        {
            $statement = $this->pdo->prepare("DELETE FROM survey WHERE id=:surveyId");
            $statement->bindValue(":surveyId", $sureyId);
            $statement->execute();
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }
    }


    //Questions
    public function createQuestion($question, $surv_id)
    {
        try
        {
            $statement = $this->pdo->prepare("INSERT INTO questions (prompt, survey_id, required, category)
                VALUES(:question_prompt, :surv_id, :required, (SELECT id FROM question_category WHERE category_name = :category)) ");
            $statement->bindValue(':question_prompt', $question->question_prompt);
            $statement->bindValue(':surv_id', $surv_id);
            $statement->bindValue(':category',$question->category_name);
            $statement->bindValue(':required',$question->required);
            $statement->execute();

            $question->setId($this->pdo->lastInsertId());
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }

    }

    public function updateQuestion($question)
    {
        try{
            $statement = $this->pdo->prepare("UPDATE questions    
                SET prompt=:prompt, required=:required,  
                    category=(SELECT id FROM question_category WHERE category_name = :category)
                WHERE id=:questionId
                ");
            $statement->bindValue(":prompt", $question->question_prompt);
            $statement->bindValue(":required", $question->required);
            $statement->bindValue(":category", $question->category_name);
            $statement->bindValue(":questionId", $question->id);
            $statement->execute();
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }
    }

    //Options
    public function createOption($option)
    {
        try {
            $statement = $this->pdo->prepare("INSERT INTO options (choice, question_id)
                 VALUES(:choice, :questionId);");
            $statement->bindValue(':choice', $option->choice );
            $statement->bindValue(':questionId', $option->questionId);
            $statement->execute();
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }
    }

    public function updateOptions($option)
    {
        try{
            $statement = $this->pdo->prepare("UPDATE options    
                SET choice=:choice
                WHERE id=:optionId");
            $statement->bindValue(":choice", $option->choice);
            $statement->bindValue(":optionId", $option->id);
            $statement->execute();
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }
    }

    // Coupon Code
    public function getCuoponCode($surveyId)
    {
        $statement = $this->pdo->prepare('SELECT * FROM coupon_codes WHERE id = (SELECT coupon_id FROM survey WHERE id = :surveyId)');
        $statement->bindValue(":surveyId", "$surveyId");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    public function createCode($code)
    {
        try {
            $statement = $this->pdo->prepare("INSERT INTO coupon_codes (message, code)
                 VALUES(:message, :code);");
            $statement->bindValue(':message', $code->message );
            $statement->bindValue(':code', $code->code);
            $statement->execute();

            return $this->pdo->lastInsertId();
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }
    }

    public function updateCouponCode($code, $surveyId)
    {
        try{
            $statement = $this->pdo->prepare("UPDATE coupon_codes    
                SET message = :message, code = :code
                WHERE id=(SELECT coupon_id FROM survey WHERE id = :surveyId)");
            $statement->bindValue(":message", $code->getMessage());
            $statement->bindValue(":code", $code->getCode());
            $statement->bindValue(":surveyId", $surveyId);
            $statement->execute();
        }
        catch (PDOException $e)
        {
            print "Error!:". $e."<br/>";
        }
    }

    //Responses
    public function createResponse($response)
    {
        $statement = $this->pdo->prepare("INSERT INTO response (question_id, choice, anonymize_id) 
            VALUES (:questionId, :reply, :anonymize_id)");
        $statement->bindValue(':questionId', $response->question_id);
        $statement->bindValue(':reply', $response->reply);
        $statement->bindValue(':anonymize_id', $response->uuid);

        $statement->execute();
    }


    /*
   *  Makes the database query to gather the needed data to build the metrics view
   *  Input:  $surveyId - string: the name of the view to render
   *  Return: an array containing the responses for a given survey
   */
    public function getMetrics($surveyId)
    {
        $statement = $this->pdo->prepare('
            Select response.choice, response.anonymize_id, questions.id as question_id, questions.prompt, question_category.category_name 
            FROM response 
            INNER JOIN questions ON response.question_id = questions.id 
            INNER JOIN question_category ON questions.category = question_category.id 
            WHERE questions.survey_id = :surveyId
            ORDER BY response.choice DESC
            ');
        $statement->bindValue(":surveyId", $surveyId);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
