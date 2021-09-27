<?php

namespace App\Classes;

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

    public  function getSurvey  ($surveyId)
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
                SELECT survey.name, survey_category.name as survey_category, questions.id as question_id, questions.prompt, 
                question_category.category_name as category, options.id as options_id, options.choice
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

    public  function updateSurvey($surveyModel)
    {
        try
        {
            $statement = $this->pdo->prepare("UPDATE survey 
                SET name=:survName, category=:category, primary_color=:color, coupon_id=:coupon 
                WHERE id=:surveyID");
            $statement->bindValue(":surveyID", $surveyModel->id);
            $statement->bindValue(":survName", $surveyModel->surveyName);
            $statement->bindValue(":color", $surveyModel->primaryColor);
            $statement->bindValue(":category", $surveyModel->surveyCategory);
            $statement->bindValue(":coupon", $surveyModel->couponCode);
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
            $statement = $this->pdo->prepare("INSERT INTO survey (name, event_id, primary_color, category) 
            VALUES (:title, :event_id, :primary_color,
            (SELECT id FROM survey_category WHERE name = :category)) ");
            $statement->bindValue(':title', $survey->surveyName);
            $statement->bindValue(':event_id', $_SESSION['curr_event']);
            $statement->bindValue(':category',$survey->surveyCategory);
            $statement->bindValue(':primary_color',$survey->primaryColor);
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
            $statement = $this->pdo->prepare("INSERT INTO questions (prompt, survey_id, category)
                VALUES(:question_prompt, :surv_id, (SELECT id FROM question_category WHERE category_name = :category)) ");
            $statement->bindValue(':question_prompt', $question->question_prompt);
            $statement->bindValue(':surv_id', $surv_id);
            $statement->bindValue(':category',$question->category_name);
            $statement->execute();

            $question->setId($this->pdo->lastInsertId());
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


    //Coupon Code

    public function getCuoponCode($surveyId)
    {
        $statement = $this->pdo->prepare('SELECT * FROM coupon_codes WHERE (SELECT id FROM survey WHERE id = :surveyId)');
        $statement->bindValue(":surveyId", "$surveyId");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
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


    //Metrics
    public function getMetrics($surveyId)
    {
        $statement = $this->pdo->prepare('
            Select response.choice, response.anonymize_id, questions.id as question_id, questions.prompt, question_category.category_name 
            FROM response 
            INNER JOIN questions ON response.question_id = questions.id 
            INNER JOIN question_category ON questions.category = question_category.id 
            WHERE questions.survey_id = :surveyId');
        $statement->bindValue(":surveyId", $surveyId);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
