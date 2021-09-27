

<div class="panel-content content-holder">
    <h1 class="content-header">Event Organizer Portal</h1>
    <div class="content-body">
        <table class="table">
            <thead>
            <tr>
                <th>User</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user) { ?>
                <tr class="">
                    <td class="label">
                        <label>
                            <?php echo $user['full_name'] ?>
                        </label>
                    </td>
                    <td>
                        <form action="/events" method="POST">
                            <button class="button" type="submit" name="user_id" value="<?php echo $user['id'] ?>">Events</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>