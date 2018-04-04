<?php
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');
$headerImage = ($post->page_information_header_image != '' ? $post->page_information_header_image : (isset($parentHeader) ? $parentHeader : '' ));
?>
<div class="sticky-header-pad support"></div>
<section class="support-header" style="background-image: url('<?= $headerImage; ?>');" >
    <div class="container">
        <div class="header-content has-text-centered">
            <h1 class="title is-1 bernadette is-primary"><?php echo $headline; ?></h1>
            <?php echo($subhead != '' ? '<p class="subtitle">' . $subhead . '</p>' : null); ?>
            <?php if ('post' === get_post_type()) : ?>
                <div class="entry-meta">
                    <?php //kmaslim_posted_on(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

