<div class="columns is-multiline">
    <div class="column is-4">
        <p class="title is-4"><?= $event['recurr_readable']; ?></p>
        <p class="subtitle"><?= $event['time']; ?></p>
        <?php if ($event['location'] != '') { ?>
            <p class="location">
            <a href="https://www.google.com/maps/dir/29.9516777,-85.4236099/The+Wicked+Wheel+Bar+%26+Grill,+10025+Hutchison+Blvd,+Panama+City+Beach,+FL+32408">
                <span class="icon">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                </span> <?= $event['location']; ?></a>
            </p>
        <?php } ?>
        <?php if ($event['tickets'] != '') { ?>
            <p class="tickets">
                <a class="button is-info is-rounded has-shadow" href="<?= $show['tickets']; ?>">Buy Tickets</a>
            </p>
        <?php } ?>
        <p></p>
        <?= $event['content']; ?>
    </div>
    <div class="column is-8">
        <img src="<?= $event['full_image']; ?>" alt="<?= $headline; ?>">
    </div>
</div>

<?php //echo '<pre>',print_r($event),'</pre>'; ?>