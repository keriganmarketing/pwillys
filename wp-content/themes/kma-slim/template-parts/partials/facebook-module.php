<?php
use Includes\Modules\KMAFacebook\FacebookController;

$facebook = new FacebookController();
$feed = $facebook->getFbPosts(1);

// echo '<pre>',print_r($feed),'</pre>';

if(count($feed) > 0){
    $fbPost    = $feed[0];
    $isVideo   = ($fbPost->media_type == 'video');
    $postImage = ($fbPost->full_image_url != '' ? $fbPost->full_image_url : ( $fbPost->video_image != '' ? $fbPost->video_image : '' ));
    $hasImage  = $postImage != '';
    $postImage = ($fbPost->full_image_url != '' ? $fbPost->full_image_url : ( $fbPost->video_image != '' ? $fbPost->video_image : '' ));
    $date      = date('M j',strtotime($fbPost->post_date)) . ' at ' . date('g:i a',strtotime($fbPost->post_date));
?>
    <div class="card social-module facebook has-text-centered <?php ($hasImage == true ? 'has-image' : 'no-image'); ?>" tabindex="0">
        <?php if($fbPost->media_type == 'video'){ ?>
            <div class="card-image">
                <a target="_blank" href="<?php echo $fbPost->url; ?>" rel="noopener" >
                    <img 
                        src="<?php echo $fbPost->image_src; ?>" 
                        alt="<?php echo wp_trim_words($fbPost->description, 20, '...'); ?>">
                </a>
            </div>

            <div class="card-content">
                <?php if($fbPost->description != 'Click here to read more on Facebook') {?>
                <p class="post-text"><?php echo wp_trim_words($fbPost->description, 20, '...'); ?></p>
                <?php } ?>
                <p class="posted-on">Posted on <?php echo $date; ?></p>
            </div>
            <div class="card-bottom">
                <a class="button is-primary is-large is-rounded has-shadow" target="_blank" href="<?php echo $fbPost->post_link; ?>">Watch on Facebook</a>
            </div>

        <?php } else { ?>
            <?php if ($fbPost->full_image_url != '') { ?>
                <div class="card-image">
                    <?php if($fbPost->url != ''){ ?>
                        <a target="_blank" href="<?php echo $fbPost->url; ?>" >
                    <?php } ?>
                    <img src="<?php echo $fbPost->full_image_url; ?>" alt="Image shared from Facebook">
                    <?php if($fbPost->url != ''){ ?>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="card-content">
                <?php if($fbPost->description != 'Click here to read more on Facebook') {?>
                <p class="post-text"><?php echo wp_trim_words($fbPost->description, 20, '...'); ?></p>
                <?php } ?>
                <p class="posted-on">Posted on <?php echo $date; ?></p>
            </div>
            <div class="card-bottom">
                <a class="button is-primary is-large is-rounded has-shadow" target="_blank" href="<?php echo $fbPost->post_link; ?>">Read more on Facebook</a>
            </div>
        
        <?php } ?>

    
        
    </div>

<?php } ?>