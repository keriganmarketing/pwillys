<?php

use Includes\Modules\KMAInstagram\InstagramController;

?>
<div class="page-wrapper" style="margin-left:-20px;">
    <div class="hero is-dark">
        <div class="hero-body">
            <div class="columns is-vcentered">
                <div class="column">
                    <p class="title">
                        Instagram Settings
                    </p>
                </div>
                <div class="column is-narrow">
                    <?php include(wp_normalize_path(dirname(dirname(__FILE__)) . '/includes/KMASig.php')); ?>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container is-fluid">
            <div class="content">
                <?php

                $instagram = new InstagramController();

                $instagramPageID = (isset($_POST['instagram_page_id']) ? sanitize_text_field($_POST['instagram_page_id']) : get_option('instagram_page_id'));
                $accessToken     = (isset($_POST['instagram_token']) ? sanitize_text_field($_POST['instagram_token']) : get_option('instagram_token'));

                if (isset($_POST['instagram_submit_settings']) && $_POST['instagram_submit_settings'] == 'yes') {
                    update_option('instagram_page_id',
                        isset($_POST['instagram_page_id']) ? sanitize_text_field($_POST['instagram_page_id']) : $instagramPageID);
                    update_option('instagram_token',
                        isset($_POST['instagram_token']) ? sanitize_text_field($_POST['instagram_token']) : $accessToken);
                }

                ?>
                <hr>
                <form enctype="multipart/form-data" name="instagram_settings" id="instagram_settings" method="post"
                      action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                    <input type="hidden" name="instagram_submit_settings" value="yes">

                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="editor-wrapper">
                                <h4 class="wp-heading-inline">Instagram Page ID</h4>
                                <div class="control">
                                    <input type="text" class="input" name="instagram_page_id"
                                           value="<?= $instagramPageID; ?>" size="40">
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="editor-wrapper">
                                <h4 class="wp-heading-inline">Instagram Access Token</h4>
                                <div class="control">
                                    <input type="text" class="input" name="instagram_token"
                                           value="<?= $accessToken; ?>" size="40">
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="submit"><input class="button is-primary" type="submit" name="Submit"
                                             value="<?php _e('Update Settings') ?>"/></p>

                </form>
                <hr>
                <div class="columns is-multiline">
                    <?php


                    ?>
                </div>
            </div>
        </div>
    </section>
</div>