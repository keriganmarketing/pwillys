<?php
$band = $terms[0];
$band->photo = get_term_meta( $band->term_id, 'bands_band_photo', true );
$band->description = get_term_meta( $band->term_id, 'bands_band_description', true );
?>
<div class="columns is-multiline">
    <div class="column is-4">
        <img src="<?= $band->photo; ?>" alt="<?= $band->name; ?>">

        <h2 class="dimbo">Show Times</h2>
        <p class="title is-4"><?= $event['recurr_readable']; ?></p>
        <p class="subtitle"><?= $event['time']; ?></p>

    </div>
    <div class="column is-8">
        <?= $band->description; ?>
    </div>
</div>

<?php
echo '<pre>',print_r($band),'</pre>';
?>