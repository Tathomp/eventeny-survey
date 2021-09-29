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

        $surveys = $urlRouter->database->getEventSurveys($eventId);

        $urlRouter->renderView('Surveys/index', [
            'surveys' => $surveys
        ]);
    }

    // This is the function that actually pushes the survey, question and coupon data to the database
    // It's called by the create survey button and update survey button on the createSurvey.php view
    public function saveSurvey(UrlRouter $urlRouter)
    {
        $eventId = $_SESSION["curr_event"];

        if(isset($_POST['create']))
        {

            if(isset($_POST['primary'])) // i think this check is redudant
            {

                $coupon = new CouponCode();
                $coupon->loadData($_POST);
                $codeId = $coupon->save(); //We need to grab the new id to pass to the survey

                $survey = new Survey();
                $survey->loadData($_POST);
                $survey->setCouponId($codeId);
                $survey->save();
            }

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
            return;
        }


        $surveys = $urlRouter->database->getEventSurveys($eventId);

        $urlRouter->renderView('Surveys/index', [
            'surveys' => $surveys]);

    }

    // Grabs the data for the chosen survey to populate the create survey page
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

            $urlRouter->renderView('Surveys/create_survey', [
                "surveyModel" => $surveyModel,
                "couponModel" => $couponModel
            ]);
        }
        else
        {
            $urlRouter->accessDenied();
        }

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

    // Displays the chosen survey to the user
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

    // Registers the user's responses
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

    // Deletes the given survey from the database
    public function deleteSurvey(UrlRouter $urlRouter)
    {
        $urlRouter->database->deleteSurvey($_POST['survey-id']);
        $eventId = $_SESSION["curr_event"];
        $surveys = $urlRouter->database->getEventSurveys($eventId);

        $urlRouter->renderView('Surveys/index', [
            'surveys' => $surveys
        ]);
    }

}