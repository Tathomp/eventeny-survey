<style>
    .selected{
        color: #08A09C;
    }
</style>

<label><?php echo $question->question_prompt ?></label>

<div id="star-rating">
    <textarea hidden id="<?php echo $question->id ?>" name="<?php echo $question->id ?>" value="" type="text"></textarea>
    <i id="<?php echo $question->id ?>1"  class="fa fa-star" onclick="starClicked(this)" ></i>
    <i id="<?php echo $question->id ?>2"  class="fa fa-star" onclick="starClicked(this)" ></i>
    <i id="<?php echo $question->id ?>3"  class="fa fa-star" onclick="starClicked(this)" ></i>
    <i id="<?php echo $question->id ?>4"  class="fa fa-star" onclick="starClicked(this)" ></i>
    <i id="<?php echo $question->id ?>5"  class="fa fa-star" onclick="starClicked(this)" ></i>


</div>

<script  type="text/javascript">

</script>
