<!-- <pre><?php //print_r($event); ?></pre> -->

<div class="column is-6-tablet is-4-widescreen is-variable is-fullheight">
    <div class="card has-text-centered" style="height:100%; background-color:#2EAD64">
        <a 
            class="card-image" href="<?= $event->event_link; ?>" 
            style="background-image: url('<?= $event->full_image_url; ?>'); background-size:cover; background-position: center;"
            aria-label="More event info about <?= $event->event_name; ?> on Facebook"
            tabindex="0"
        >
            <span class="sr-only"><?= $event->event_name; ?></span>
        </a>
        <div class="card-content is-flex" style="flex-direction:column;flex-grow:1;">
            <h3 class="title is-3 dimbo is-white" tabindex="0"><?= $event->event_name; ?></h3>
            <div style="background-color: #23844c; border-radius: 1rem; padding:1rem; margin:0 0 1rem;" >

            <?php if(count($event->event_times) > 0) { ?>
                <p class="date" tabindex="0"><strong style="color:#FFF">Upcoming Dates:</strong><br>
                <?php foreach(array_slice($event->event_times,0,4,true) as $time) { ?>
                   <?= date('M d', strtotime($time->start_time)); ?>, 
                <?php } ?>
                </p>
                <p class="date" tabindex="0"><?= $event->event_time; ?></p>
            <?php } else { ?>
                <?php if($event->event_date != ''){ ?>
                <p class="date" tabindex="0"><?= $event->event_date; ?></p>
                <?php } ?>
                <?php if($event->event_time != '') { ?>
                <p class="date" tabindex="0"><?= $event->event_time; ?></p>
                <?php } ?>
            <?php } ?>
            </div>

            <p class="event-button" style="margin-top:auto;">
                <a 
                    href="<?= $event->event_link; ?>"
                    class="button is-primary is-rounded has-shadow" 
                    aria-label="More event info about <?= $event->event_name; ?> on Facebook"
                    tabindex="0"
                >More event info</a>
            </p>
        </div>
    </div>
</div>