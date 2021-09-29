<button class="button" type="button" onclick="generatePreview()">Preview Test</button>
<div class="survey-content-holder">
    <div class="row">
        <div class="column">
            <h1 class="survey-content-header">Create Survey</h1>

            <br><br>
            <form action="/events/survey/save" id="dynamicform" method="POST" enctype="multipart/form-data">
                <div class="survey-content-body">
                    <label for="survey-name-input">Survey Name:</label>
                    <input type="text" id="survey-name-input" name="survey-name" value="<?php echo $surveyModel->surveyName; ?>">

                    <br><br>

                    <label for="survey-category">Choose a category:</label>
                    <select name="survey-category" id="survey-category">
                        <option <?php echo ($surveyModel->surveyCategory == "Food" ? "selected" : "") ?> value="Food">Food</option>
                        <option <?php echo ($surveyModel->surveyCategory == "Customer Service" ? "selected" : "") ?> value="Entertainment">Entertainment</option>
                        <option <?php echo ($surveyModel->surveyCategory == "Customer Service" ? "selected" : "") ?> value="Customer Service">Customer Service</option>
                    </select>

                    <br><br>

                    <div>
                        <input type="color" id="primary" name="primary" value="">
                        <label for="head">Primary Color</label>
                    </div>

                    <br><br>
                    <div>
                        <label>Coupon Message</label>
                        <textarea name="message" value="" rows="4" cols="50"><?php echo $couponModel->getMessage(); ?></textarea>
                    </div>
                    <div>
                        <label>Promo Code</label>
                        <input name="code" type="text" maxlength="12" size="14" value="<?php echo $couponModel->getCode(); ?>">
                    </div>

                    <br><br>

                    <button  class="button" type="button" onclick="generateDropDown()">New Question</button>

                    <button class="button" type="submit" name="create">Submit Survey</button>
                    <button class="button" type="submit" name="update" value="<?php echo $surveyModel->id; ?>">Update Survey Form</button>
                    <br>
                </div>
                <div id="question-container"></div>



            </form>
        </div>

        <div class="column">
            <div id="preview"></div>

        </div>
</div>



<script src="/static/js/dynamicform.js" ></script>
<script src="/static/js/formpreview.js" ></script>
    <script  type="text/javascript">
        var jsonData = <?php print json_encode($surveyModel)?>;
        console.log(jsonData);

        populateForm();

    </script>