<label><?php echo $question->question_prompt ?></label>
<Br>    
<input name="<?php echo $question->id ?>" type="text" maxlength="150"
    <?php echo ($question->getRequired() == true ? "required" : "") ?>
><br><br>
