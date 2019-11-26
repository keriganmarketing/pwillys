<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 7/13/2017
 * Time: 12:02 PM
 */

$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

?>
<div class="column is-4">
    <div class="card">
        <div class="card-image">
            <figure class="image is-16by3">
            <a href="<?php echo get_the_permalink(); ?>">
                <!-- <img src="http://bulma.io/images/placeholders/640x360.png"> -->
                <?php echo the_post_thumbnail('post-thumbnail', ['class' => 'img-fluid']); ?> 
            </a>
            </figure>
        </div>
        <div class="card-content">
            <h2 class="title is-3 dimbo is-primary"><?php echo $headline; ?></h2>
            <?php echo ($subhead!='' ? '<p class="subtitle">'.$subhead.'</p>' : null); ?>
            Posted <?php echo the_date(); ?>

        </div>
        <div class="card-footer">
            <a class="card-footer-item" 
                href="<?php echo get_the_permalink(); ?>">Read Full Article</a>
        </div>
    </div>
</div>
