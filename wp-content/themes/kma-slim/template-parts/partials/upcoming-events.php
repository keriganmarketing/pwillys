<?php

use Includes\Modules\Events\Events;

$events      = new Events();
$eventsArray = $events->getHomePageEvents(4);

if (count($eventsArray) > 1) { ?>
    <div class="next-event-large">
        <div class="container">
            <div class="events-grid columns is-multiline is-aligned is-justified is-8">
                <?php foreach ($eventsArray as $event) { ?>
                    <?php include(locate_template('template-parts/partials/mini-event.php')); ?>
                <?php } ?>
            </div>
            <p class="has-text-centered"><a class="button is-primary is-rounded is-large has-shadow" href="/events/" >More Events & Specials</a></p>
        </div>
    </div>
<?php }else{ ?>

<?php } ?>