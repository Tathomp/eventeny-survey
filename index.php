<?php

// This is the main index page.
// It's primary purpose is to build the url patterns for a UrlRouter object.


namespace app;

use App\Classes\UrlRouter;
use App\Controllers\SurveyController;
use App\Controllers\UserController;
use App\Controllers\EventController;
use App\Controllers\MetricsController;
use App\Classes\Database;


require_once __DIR__.'/vendor/autoload.php';

session_start();

$database = new Database();
$router = new UrlRouter($database);

$surveyController = new SurveyController();
$userController = new UserController();
$eventController = new EventController();
$metricsController = new MetricsController();

$router->addRequest('/', [$userController, 'index']);
$router->addRequest('/events', [$eventController, 'index']);
$router->addRequest('/events/survey', [$surveyController, 'index']);
$router->addRequest('/events/survey/save', [$surveyController, 'saveSurvey']);
$router->addRequest('/events/survey/take', [$surveyController, 'displaySurvey']);

$router->addRequest('/events/survey/complete', [$surveyController, 'completeSurvey']);
$router->addRequest('/events/survey/delete', [$surveyController, 'deleteSurvey']);
$router->addRequest('/events/survey/create', [$surveyController, 'createSurvey']);
$router->addRequest('/events/survey/update', [$surveyController, 'updateSurvey']);

$router->addRequest('/events/survey/metrics', [$metricsController, 'surveyMetrics']);
$router->addRequest('/events/survey/metrics/download', [$metricsController, 'downLoadMetrics']);

$router->resolve();

