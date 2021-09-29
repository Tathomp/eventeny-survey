<label><?php echo $question->question_prompt ?></label>
<Br>

<?php foreach ($question->options as $option) { ?>
    <div>
        <label class="checkbox" for="<?php echo $question->id ?>-<?php echo $option->id ?>">

            <input type="checkbox"
                   id="<?php echo $question->id ?>-<?php echo $option->id ?>"
                   name="<?php echo $question->id ?>-<?php echo $option->id ?>"
                   value="<?php echo $option->choice ?>"
                    style="background-color: <?php echo $surveyModel->primaryColor; ?>">

            <span><?php echo $option->choice ?></span>
        </label>
    </div>

<?php } ?>
<br>
