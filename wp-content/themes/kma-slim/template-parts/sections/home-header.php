<div class="home-header">
    <div class="video-container h-100">
        <video-background 
        img="<?php echo get_stylesheet_directory_uri(); ?>/img/video-backup-mobile.webp"
        :sources="[
            '<?php echo get_stylesheet_directory_uri(); ?>/video/pwillys_video_short.mp4'
            '<?php echo get_stylesheet_directory_uri(); ?>/video/pwillys_video_short.webm',
        ]" 
        tabindex="0"
        aria-label="Promotional Video of Lagoon Pontoon Trips and Adventures"
        ></video-background>
    </div>
    <div class="video-headline has-text-centered">
        <h1 class="title dimbo is-primary"><?php echo $headline; ?></h1>
        <?php echo ($subhead!='' ? '<h2 class="subtitle bernadette">Seafood, Music &&nbsp;Fun</h2>' : null); ?>
        <a class="menu-button button is-primary is-rounded has-shadow" href="/pineapple-willys-menu/">View Our Menu</a>
    </div>
</div>
