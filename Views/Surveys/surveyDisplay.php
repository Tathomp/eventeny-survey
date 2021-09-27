<div class="content-holder">
    <h1 class="content-header"><?php echo $surveyModel->surveyName ?></h1>

    <div class="content-body">
        <form action="/events/survey/complete" method="POST" enctype="multipart/form-data">
            <?php foreach ($surveyModel->question as $question) { ?>
                <?php
                if($question->category_name == 'Radio')
                {
                    include "QuestionPartials/_radio.php";
                }
                else if  ($question->category_name == 'Checkbox')
                {
                    include "QuestionPartials/_checkbox.php";

                }
                else if  ($question->category_name == 'Drop Down')
                {
                    include "QuestionPartials/_dropdown.php";

                }
                else if  ($question->category_name == 'Star Rating')
                {
                    include "QuestionPartials/_star_rating.php";

                }
                else if  ($question->category_name == 'Short Answer')
                {
                    include "QuestionPartials/_short_answer.php";

                }
                else if  ($question->category_name == 'Paragraph')
                {
                    include "QuestionPartials/_paragraph.php";

                }
                ?>

                <br>
            <?php } ?>
            <br>
            <button class="button" type="submit">Confirm Survey</button>
        </form>
    </div>
</div>


<script src="/static/js/displaySurvey.js" ></script>
