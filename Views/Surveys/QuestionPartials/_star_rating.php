<style>
    .selected{
        color: <?php echo $surveyModel->primaryColor; ?>;
    }
</style>

<label><?php echo $question->question_prompt ?></label>

<div id="star-rating">
    <textarea hidden id="<?php echo $question->id ?>" name="<?php echo $question->id ?>" value="1" type="text"></textarea>
    <i id="<?php echo $question->id ?>1"  class="fa fa-star selected" onclick="starClicked(this)" ></i>
    <i id="<?php echo $question->id ?>2"  class="fa fa-star" onclick="starClicked(this)" ></i>
    <i id="<?php echo $question->id ?>3"  class="fa fa-star" onclick="starClicked(this)" ></i>
    <i id="<?php echo $question->id ?>4"  class="fa fa-star" onclick="starClicked(this)" ></i>
    <i id="<?php echo $question->id ?>5"  class="fa fa-star" onclick="starClicked(this)" ></i>


</div>

