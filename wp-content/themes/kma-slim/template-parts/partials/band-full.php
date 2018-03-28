<?php

use Includes\Modules\Events\Events;
use Includes\Modules\Social\SocialSettingsPage;

$band                            = $terms[0];
$band->photo                     = get_term_meta($band->term_id, 'bands_band_photo', true);
$band->description               = get_term_meta($band->term_id, 'bands_band_description', true);
$band->website                   = get_term_meta($band->term_id, 'bands_website_link', true);
$band->social_media              = [];
$band->social_media['facebook']  = get_term_meta($band->term_id, 'bands_facebook_link', true);
$band->social_media['twitter']   = get_term_meta($band->term_id, 'bands_twitter_link', true);
$band->social_media['instagram'] = get_term_meta($band->term_id, 'bands_instagram_link', true);
$band->social_media['youtube']   = get_term_meta($band->term_id, 'bands_youtube_link', true);

$hasSocialMedia = false;
foreach ($band->social_media as $key => $var) {
    if ($var != '') {
        $hasSocialMedia = true;
    }
}

$events      = new Events();
$eventsArray = $events->getUpcomingEvents(
    [
        'tax_query' => [
            [
                'taxonomy' => 'bands',
                'field'    => 'slug',
                'terms'    => [$band->slug],
                'operator' => 'IN'
            ]
        ]
    ]
);

$band->show_times = $eventsArray;

?>
<div class="columns is-multiline">
    <div class="column is-4">
        <img src="<?= $band->photo; ?>" alt="<?= $band->name; ?>">

        <h2 class="dimbo title is-primary">Show Times</h2>
        <?php foreach ($band->show_times as $show) { ?>
            <div class="show columns is-multiline">
                <div class="column">
                    <p class="title is-5 is-secondary"><?= $show['recurr_readable']; ?></p>
                    <p class="subtitle is-6"><?= $show['time']; ?></p>
                </div>
                <?php if ($show['tickets'] != '') { ?>
                    <div class="column is-narrow">
                        <a class="button is-info is-rounded has-shadow" href="<?= $show['tickets']; ?>">Buy
                            Tickets</a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

    </div>
    <div class="column is-8">
        <div class="columns is-multiline">
            <?php if ($hasSocialMedia) { ?>
                <div class="column social is-narrow">
                    <?php
                    $socialLinks = new SocialSettingsPage();
                    $socialIcons = $socialLinks->getSocialLinks('svg', 'circle', $band->social_media);
                    if (is_array($socialIcons)) {
                        foreach ($socialIcons as $socialId => $socialLink) {
                            echo '<a class="' . $socialId . '" href="' . $socialLink[0] . '" target="_blank" >' . $socialLink[1] . '</a>';
                        }
                    }
                    ?>
                </div>
            <?php } ?>
            <?php if ($band->website != '') { ?>
                <div class="column website is-narrow">
                    <a class="button is-info is-rounded has-shadow" style="height: 2.6rem"
                       href="<?= $band->website; ?>">Visit Website</a>
                </div>
            <?php } ?>
            <div class="column is-12">
                <?= $band->description; ?>
            </div>
        </div>
    </div>
</div>
