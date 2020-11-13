<?php
use Includes\Modules\KMAFacebook\FacebookController;

$facebook = new FacebookController();
$feed = $facebook->getFbPosts(1);

// echo '<pre>',print_r($feed),'</pre>';

if(count($feed) > 0){
    $fbPost   = $feed[0];
    $isVideo  = ($fbPost->status_type == 'added_video');
    $postImage = ($fbPost->full_image_url != '' ? $fbPost->full_image_url : ( $fbPost->video_image != '' ? $fbPost->video_image : '' ));
    $hasImage = $postImage != '';
    $postImage = ($fbPost->full_image_url != '' ? $fbPost->full_image_url : ( $fbPost->video_image != '' ? $fbPost->video_image : '' ));
    $date     = date('M j',strtotime($fbPost->post_date)) . ' at ' . date('g:i a',strtotime($fbPost->post_date));
?>
    <div class="card social-module facebook has-text-centered <?= ($hasImage == true ? 'has-image' : 'no-image');?>" tabindex="0">
        <?php if ($hasImage == true && $isVideo == false) { ?>
            <div class="card-image">
                <img src="<?= $postImage; ?>" alt="Image shared from Facebook">
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
            <?php if($fbPost->post_content != 'Click here to read more on Facebook') {?>
            <p class="post-text"><?= $fbPost->post_content; ?></p>
            <?php } ?>
            <p class="posted-on">Posted on <?= $date; ?></p>
        </div>
        <div class="card-bottom">
            <a class="button is-primary is-large is-rounded has-shadow" target="_blank" href="<?= $fbPost->post_link; ?>">Read more on Facebook</a>
        </div>
    </div>

<?php } ?>