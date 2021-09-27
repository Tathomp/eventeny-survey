<label><?php echo $question->question_prompt ?></label>
<Br>    

<?php foreach ($question->options as $option) { ?>
    <input id="<?php echo $option->id ?>" name="<?php echo $question->id ?>-" type="radio" value=<?php echo $option->choice ?>>
    <label for="<?php echo $option->id ?>"><?php echo $option->choice  ?></label>
    <Br>    
<?php } ?>
