<?php
use Includes\Modules\KMAFacebook\FacebookController;

$facebook = new FacebookController();
$feed = $facebook->getFeed(1);

echo '<pre>',print_r($feed),'</pre>';

if($feed){
    $fbPost = $feed->posts[0];
    $isVideo  = ($fbPost->type == 'video');
    $hasImage = ($fbPost->full_picture != '' && $isVideo == false);
    $date     = date('M j',strtotime($fbPost->created_time)) . ' at ' . date('g:i a',strtotime($fbPost->created_time));
?>
    <div class="card social-module facebook has-text-centered <?= ($hasImage == true ? 'has-image' : 'no-image'); ?>">
        <?php if ($hasImage == true) { ?>
            <div class="card-image">
                <img src="<?= $fbPost->full_picture; ?>">
            </div>
        <?php } ?>
        <?php if ($isVideo == true) { ?>
            <div class="card-video">
                <iframe
                        src="<?= $fbPost->link; ?>"
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
            <p class="post-text"><?= $fbPost->message; ?></p>
            <p class="posted-on">Posted on <?= $date; ?></p>
        </div>
        <div class="card-bottom">
            <a class="button is-primary is-large is-rounded has-shadow" target="_blank" href="<?= $fbPost->permalink_url; ?>">Read more on Facebook</a>
        </div>
    </div>

<?php } ?>