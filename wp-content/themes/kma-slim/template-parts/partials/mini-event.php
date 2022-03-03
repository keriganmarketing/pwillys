<div class="column is-6-tablet is-4-widescreen is-variable is-fullheight">
    <div class="card has-text-centered" style="height:100%; background-color:#2EAD64">
        <a class="card-image" href="<?= $event->event_link; ?>" style="background-image: url('<?= $event->full_image_url; ?>'); background-size:cover; background-position: center;">
            <span class="sr-only"><?= $event->event_name; ?></span>
        </a>
        <div class="card-content is-flex" style="flex-direction:column;flex-grow:1;">
            <h3 class="title is-3 dimbo is-white"><?= $event->event_name; ?></h3>
            <?php if($event->event_date != ''){ ?>
            <p class="date"><?= $event->event_date; ?></p>
            <?php } ?>

            <?php if($event->event_time != '') { ?>
            <p class="date"><?= $event->event_time; ?></p>
            <?php } ?>
            <br>
            <p class="event-button" style="margin-top:auto;"><a class="button is-primary is-rounded has-shadow" >More Info</a></p>
        </div>
        <a class="tile-link" 
            href="<?= $event->event_link; ?>" 
            target="_blank"
            title="More about <?= $event->event_name; ?>" 
            aria-label="More about <?= $event->event_name; ?>"  ></a>
    </div>
</div>