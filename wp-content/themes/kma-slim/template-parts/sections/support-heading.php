<div class="sticky-header-pad support"></div>
<section class="support-header">
    <div class="tire-texture"></div>
    <div class="container">
        <div class="header-content">
            <h1 class="title is-1 tandelle is-primary"><?php echo $headline; ?></h1>
            <?php echo($subhead != '' ? '<p class="subtitle">' . $subhead . '</p>' : null); ?>
            <?php if ('post' === get_post_type()) : ?>
                <div class="entry-meta">
                    <?php //kmaslim_posted_on(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

