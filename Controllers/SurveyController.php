<?php
namespace App\Controllers;

use App\Classes\UrlRouter;


use App\Models\CouponCode;
use App\Models\Questions\Question;
use App\Models\Questions\QuestionOptions;
use App\Models\Response;
use App\Models\Survey;
use Exception;

use function App\utils\generateUUID;

include_once( __DIR__ . '/../utils/guid.php');

class SurveyController
{
    public function __construct()
    {
        
    }

    // List all surveys for a given event
    public function index(UrlRouter $urlRouter)
    {

        if( isset($_POST['event_id']))
        {
            $eventId = $_POST['event_id'];
            $_SESSION["curr_event"] = $eventId;
        }
        else
        {
            //through out page not found type page
            $urlRouter->accessDenied();
            return;
        }

        $surveys = $urlRouter->database->getSurveys($eventId);

        $urlRouter->renderView('Surveys/index', [
            'surveys' => $surveys
        ]);
    }

    public function saveSurvey(UrlRouter $urlRouter)
    {
        if(isset($_POST['create']))
        {
            $eventId = null;

            if(isset($_POST['primary'])) // i think this check is redudant
            {
                // We're using this to save data
                $eventId = $_SESSION["curr_event"];
                // we need to write the db call to insert the question

                $coupon = new CouponCode();
                $coupon->loadData($_POST);
                $codeId = $coupon->save(); //We need to grab the new id to pass to the survey
                echo "code id: ". $codeId;
                $survey = new Survey();
                $survey->loadData($_POST);
                $survey->setCouponId($codeId);
                $survey->save();


            }


            $surveys = $urlRouter->database->getSurveys($eventId);

            $urlRouter->renderView('Surveys/index', [
                'surveys' => $surveys
            ]);
        }
        elseif (isset($_POST['update']))
        {
            $survey = new Survey();
            $survey->loadData($_POST);
            $survey->update();

            $coupon = new CouponCode();
            $coupon->loadData($_POST);
            $coupon->update($_POST['update']);
        }
        else
        {
            $urlRouter->accessDenied();
        }
    }


    public function updateSurveyView(UrlRouter $urlRouter)
    {
        if(isset($_POST["survey-id"]))
        {
            $surveyData = $urlRouter->database->getQuestionsForSurvey($_POST['survey-id']);
            $couponData = $urlRouter->database->getCuoponCode($_POST['survey-id']);

            $surveyModel = new Survey();
            $surveyModel->loadDataFromDB($surveyData);
            $surveyModel->id = $_POST['survey-id'];

            $couponModel = new CouponCode();
            $couponModel->setCode($couponData[0]['code']);
            $couponModel->setMessage($couponData[0]['message']);

        }
        else
        {
            $surveyModel = new Survey();
            $couponModel = new CouponCode();
        }


        $urlRouter->renderView('Surveys/create_survey', [
            "surveyModel" => $surveyModel,
            "couponModel" => $couponModel
        ]);
    }


    public function createSurvey(UrlRouter $urlRouter)
    {

        $surveyModel = new Survey();
        $couponModel = new CouponCode();

        $urlRouter->renderView('Surveys/create_survey', [
            "surveyModel" => $surveyModel,
            "couponModel" => $couponModel
        ]);
    }


    public function displaySurvey(UrlRouter $urlRouter)
    {
        $url_components = parse_url($_SERVER['REQUEST_URI']);
        parse_str($url_components['query'], $params);

        $_SESSION['curr_survey'] = $params['survey-id'];

        $surveyData = $urlRouter->database->getQuestionsForSurvey($params['survey-id']);
        $params['data'] = $surveyData;

        $surveyModel = new Survey();

        $surveyModel->loadDataFromDB($surveyData);
        $surveyModel->id = $params['survey-id'];


        $urlRouter->renderView('Surveys/surveyDisplay', [
            //'questionContainer' => $surveyModel->question,
            'surveyModel' => $surveyModel
        ]);
    }

    public function completeSurvey(UrlRouter $urlRouter)
    {
        $uuid = generateUUID();

        foreach(array_keys($_POST) as $questionId)
        {
            $r = new Response();
            $r->loadData($questionId, $_POST[$questionId], $uuid);
            $r->save();

        }


        $data = $urlRouter->database->getCuoponCode($_SESSION['curr_survey'])[0];

        $surveyData  = new CouponCode();
        $surveyData ->loadData($data);

        $urlRouter->renderView('Surveys/thankYouPage', ['surveyData' => $surveyData ]);
    }

    public function deleteSurvey(UrlRouter $urlRouter)
    {
        $urlRouter->database->deleteSurvey($_POST['survey-id']);
        $eventId = $_SESSION["curr_event"];
        $surveys = $urlRouter->database->getSurveys($eventId);

        $urlRouter->renderView('Surveys/index', [
            'surveys' => $surveys
        ]);
    }

}