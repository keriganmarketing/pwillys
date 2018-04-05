<div class="column is-6-tablet is-4-widescreen is-3-fullhd is-variable">
    <div class="card has-text-centered">
        <div class="card-image">
            <a href="<?= $event['link']; ?>" >
            <img src="<?= $event['photo']; ?>" alt="<?= $event['name']; ?>">
            </a>
        </div>
        <div class="card-content">
            <h3 class="title is-3 dimbo is-white"><?= $event['name']; ?></h3>
            <p class="date"><?= $event['recurr_readable']; ?></p>
            <p class="time"><?php echo $event['time']; ?></p>
<!--            <p class="location">--><?php //echo $event['location']; ?><!--</p>-->
            <p class="event-button"><a class="button is-primary is-rounded has-shadow" >More Info</a></p>
        </div>
        <a class="tile-link" href="<?= $event['link']; ?>" ></a>
    </div>
</div>