<div class="content-holder" style="border: 3px solid    <?php echo $survey['primary_color'] ?>">
    <h1 class="content-header" style="background: <?php echo $survey['primary_color'] ?>">
        <?php echo $survey['name'] ?>
    </h1>
    <div class="content-body">
        <div>
            Total Responses: <?php echo $metrics['count'] ?>
        </div>
        <?php foreach ($metrics as $metric) {
            if( isset($metric["choices"]))
            {
                echo "<h3>". $metric["prompt"] . "</h3>";
                foreach (array_keys($metric["choices"]) as $choice)
                {
                    echo "<div class='row'>";

                    echo "<div class='left-col'>". $choice ."</div>";
                    echo "<div class='middle-bar'><div class='bar-container'><div class='bar' style='width:".
                        strval( ($metric["choices"][$choice] / $metrics['count']) * 100)
                        ."%;
                        background-color: ". $survey["primary_color"] .";
                        '></div> </div></div>";
                    echo "<div class='right-col'>" . $metric["choices"][$choice] . "</div>";
                    echo "</div>";
                }
            }
            else if( isset($metric['count']))
            {
                echo "<h3>". $metric["prompt"] . "</h3>";
            }
        }?>
        <br>
        <form action="/events/survey/metrics/download" method="post" target="_blank">
            <button type="submit" name="survey-id" class="button"
                    style="background-color: <?php echo $survey['primary_color']; ?>"
                    value="<?php echo $survey['id'] ?>">
                Download Data
            </button>
        </form>
    </div>

</div>
