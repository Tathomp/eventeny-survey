<label><?php echo $question->question_prompt ?></label>
<Br>

<select id="<?php echo $question->id ?>" name="<?php echo $question->id ?>-">
    <?php foreach ($question->options as $option) { ?>
    <option id="<?php echo $question->id ?> - <?php echo $option->id ?>" value="<?php echo $option->choice ?>">
        <?php echo $option->choice ?>
    </option>
        <?php } ?>
</select>

