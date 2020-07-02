<?php
use Includes\Modules\KMAFacebook\FacebookController;

$facebook = new FacebookController();
$feed = $facebook->getFbPosts(1);

// echo '<pre>',print_r($feed),'</pre>';

if(count($feed) > 0){
    $fbPost   = $feed[0];
    $isVideo  = ($fbPost->status_type == 'added_video');
    $hasImage = ($fbPost->full_image_url != '' && $isVideo == false);
    $date     = date('M j',strtotime($fbPost->post_date)) . ' at ' . date('g:i a',strtotime($fbPost->post_date));
?>
    <div class="card social-module facebook has-text-centered <?= ($hasImage == true ? 'has-image' : 'no-image'); ?>">
        <?php if ($hasImage == true) { ?>
            <div class="card-image">
                <img src="<?= $fbPost->full_image_url; ?>">
            </div>
        <?php } ?>
        <?php if ($isVideo == true) { ?>
            <div class="card-video">
                <iframe
                        src="<?= $fbPost->video_url; ?>"
                        style="border:none;overflow:hidden"
                        scrolling="no"
                        frameborder="0"
                        allowTransparency="true"
                        allowFullScreen="true"
                        width="100%"
                        height="225">
                </iframe>
            </div>
        <?php } ?>
        <div class="card-content">
            <p class="post-text"><?= $fbPost->post_content; ?></p>
            <p class="posted-on">Posted on <?= $date; ?></p>
        </div>
        <div class="card-bottom">
            <a class="button is-primary is-large is-rounded has-shadow" target="_blank" href="<?= $fbPost->post_link; ?>">Read more on Facebook</a>
        </div>
    </div>

<?php } ?>