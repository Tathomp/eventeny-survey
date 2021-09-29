<?php

namespace App\Classes;

class UrlRouter
{
    public array $requestRoutes = array();
    public ?Database $database = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /*
     *  Associates an url pattern with a method in a controller
     *  Input:  $url - string: a particular url pattern to match
     *          $funct - array(Controller Instance, 'functionName') - the function on the controller that we'll call
     *                  for the url pattern.
     */
    public function addRequest(string $url, array $funct)
    {
        $this->requestRoutes[$url] = $funct;
    }

    // Parses the url and calls the function associated with that particular pattern.
    public function resolve()
    {
        $url = $_SERVER['PATH_INFO'] ?? '/';

        $funct = $this->requestRoutes[$url] ?? null;


        if (!$funct) {
            $this->renderView("page_not_found");
            exit;
        }

        echo call_user_func($funct, $this);
    }

    /*
     *  Renders the base layout file and the view passed to it
     *  Input:  $view - string: the name of the view to render
     *          $params - data from the controller to be passed to the view
     */
    public function renderView(string $view, $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();

        include "./Views/$view.php";
        $content = ob_get_clean();
        include "./Views/_layout.php";
    }

    public function accessDenied()
    {
        $this->renderView("access_denied");
    }

}