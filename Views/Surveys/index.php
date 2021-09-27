<div class="content-holder">
    <h1 class="content-header">Event Surveys</h1>
    <a href="/events/survey/create">
        <button class="button">Create New Survey</button>
    </a>

    <table class="table content-body">
        <thead>
        <tr>
            <th>Survey Name</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($surveys as $survey) { ?>
            <tr>
                <td>
                    <?php echo $survey['name'] ?>
                </td>
                <td>
                    <form action="/events/survey/update" method="POST">
                        <button class="button" name="survey-id" value="<?php echo $survey['id'] ?>">Edit Survey</button>
                    </form>
                    <form action="/events/survey/take" method="GET">
                        <button class="button" name="survey-id" value="<?php echo $survey['id'] ?>">Take Survey</button>
                    </form>
                    <form action="/events/survey/metrics" method="POST">
                        <button class="button" name="survey-id" value="<?php echo $survey['id'] ?>">Survey Metrics</button>
                    </form>
                    <form action="/events/survey/delete" method="POST">
                        <button class="button" name="survey-id" value="<?php echo $survey['id'] ?>">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
