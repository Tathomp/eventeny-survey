<div class="content-holder">
    <h1 class="content-header">Your Events</h1>
    <div class="subheader">
        Select an event to manage its surveys.
    </div>
    <div class="table content-body">
        <table>
            <thead>
            <tr>
                <th>Events</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($events as $event) { ?>
                <tr>
                    <td>
                        <?php echo $event['name'] ?>
                    </td>
                    <td>
                        <form action="/events/survey" method="POST">
                            <button class="button" type="submit" name="event_id" value="<?php echo $event['id'] ?>">Surveys</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>
    


