<?php 

namespace App\Controllers;

use App\Classes\UrlRouter;


class MetricsController
{
    public function __construct()
    {

    }

    public function downLoadMetrics(UrlRouter $urlRouter)
    {
        $results = $urlRouter->database->getMetrics($_POST['survey-id']);
        $filename="download.csv";
        $fp = fopen($filename, 'w');

        foreach ($results as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

        header("Content-disposition: attachment;filename=$filename");
        readfile($filename);
        unlink($filename);

        echo "<script>window.close();</script>";

    }

    public function surveyMetrics(UrlRouter $urlRouter)
    {
        if( isset($_POST['survey-id']))
        {
            $survey = $urlRouter->database->getSur($_POST['survey-id'])[0];
            $results = $urlRouter->database->getMetrics($_POST['survey-id']);

            $metrics = array();
            $metrics['responses'] = array(); //This will be for counting unique respondents

            $metrics['count'] = 0;

            foreach ($results as $r)
            {

                if(isset($metrics['responses'][$r['anonymize_id']]))
                {
                    $metrics['responses'][$r['anonymize_id']]++;
                }
                else
                {
                    $metrics['responses'][$r['anonymize_id']] = 1; //I don't think we actually need to count this?
                    $metrics['count']++;
                }

                if($r["category_name"] == "Checkbox" ||
                    $r["category_name"] == "Drop Down" ||
                    $r["category_name"] == "Radio")
                {


                    if(isset($metrics[$r["question_id"]]))
                    {
                        if(isset($metrics[$r["question_id"]]["choices"][$r["choice"]]))
                        {
                            $metrics[$r["question_id"]]["choices"][$r["choice"]]++;
                        }
                        else
                        {
                            $metrics[$r["question_id"]]["choices"][$r["choice"]] = 1;
                        }
                    }
                    else
                    {
                        $metrics[$r["question_id"]] = array();
                        $metrics[$r["question_id"]]["prompt"] = $r["prompt"];
                        $metrics[$r["question_id"]]["category_name"] = $r["category_name"];


                        // We only want to display metrics for multiple choice type questions
                        $metrics[$r["question_id"]]["choices"] = array();
                        $metrics[$r["question_id"]]["choices"][$r["choice"]] = 1;
                    }
                }
                else if($r["category_name"] == "Star Rating")
                {
                    if(isset($metrics[$r["question_id"]]))
                    {
                        $metrics[$r["question_id"]]["sum"] +=  intval($r["choice"]);
                        $metrics[$r["question_id"]]["count"]++;
                    }
                    else
                    {
                        $metrics[$r["question_id"]] = array();
                        $metrics[$r["question_id"]]["prompt"] = $r["prompt"];
                        $metrics[$r["question_id"]]["category_name"] = $r["category_name"];
                        $metrics[$r["question_id"]]["sum"] =  intval($r["choice"]);
                        $metrics[$r["question_id"]]["count"] = intval(1);
                    }


                }


            }
            // point to metrics page
            $urlRouter->renderView('Metrics/survey_metrics', [
                'metrics' => $metrics,
                'survey' => $survey
            ]);
        }
        else
        {
            //through out page not found type page
            include __DIR__."/Views/access_denied.php";
        }
    }

}