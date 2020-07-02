<?php

use Includes\Modules\KMAInstagram\InstagramController;

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
                <?php if(!get_option('instagram_token') && !isset($_GET['code'])){ ?>
                <h4 id="authorize-headline" style="display: block;" class="wp-heading-inline">Authorize the App to
                    continue</h4>
                <div id="status"></div>
                <p class="submit" id="authorize">
                    <button onclick="checkStatus()" class="button is-primary"><?php _e('Authorize App') ?></button>
                </p>
                <?php } ?>
                <hr>
                <div id="accountoptions" class="columns is-multiline"></div>
                <div id="error"></div>
                <form enctype="multipart/form-data" name="instagram_settings" id="instagram_settings" method="post"
                    action="admin.php?page=kma-instagram">
                    <input type="hidden" name="instagram_submit_settings" value="yes">

                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="editor-wrapper">
                                <h4 class="wp-heading-inline">Instagram Page ID</h4>
                                <div class="control">
                                    <input type="text" class="input" name="instagram_page_id" id="instagramid"
                                        value="<?= $instagramPageID; ?>" size="40">
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="editor-wrapper">
                                <h4 class="wp-heading-inline">Instagram Access Token</h4>
                                <div class="control">
                                    <input type="text" class="input" name="instagram_token" id="instagramtoken"
                                        value="<?= $accessToken; ?>" size="40">
                                </div>
                            </div>
                        </div>

                    </div>

                    <p class="submit">
                        <input class="button is-info" type="submit" name="Submit"
                            value="<?php _e('Update Settings') ?>" />

                        <a onclick="checkStatus();" class="button is-primary"><?php _e('Renew Authorization') ?></a>
                    </p>

                </form>
                <hr>
                <div class="columns is-multiline">
                <?php if(get_option('instagram_token')){
                    $items = json_decode($instagram->getFeed(6));
                    foreach($items as $item){  ?>
                    <div class="column is-2-desktop">
                        <img src="<?php echo $item->media_url; ?>">
                        <p>Likes: <?php echo $item->like_count; ?><br>
                            Comments: <?php echo $item->comments_count; ?></p>
                        <p><a target="_blank" class="button is-secondary" href="<?php echo $item->permalink; ?>">view on
                                instagram</a></p>
                    </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    function clearError() {
        jQuery("#error").html('');
    }

    window.fbAsyncInit = function () {
        FB.init({
            appId: '2174657569497762',
            cookie: true,
            xfbml: true,
            version: 'v7.0'
        });

        FB.AppEvents.logPageView();
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function checkStatus() {
        FB.login(function (response) {
            if (document.getElementById('authorize-headline')) {
                document.getElementById('authorize-headline').style.display = 'none';
                document.getElementById('authorize').innerHTML = '';
            }
            exchangeToken(response.authResponse.accessToken);
        }, {
            scope: 'instagram_basic,pages_show_list'
        });
    }

    function exchangeToken(token) {
        jQuery.ajax({
            url: "/wp-json/kerigansolutions/v1/instagallerytoken?token=" + token
        }).done(function (response) {
            let longLivedUserToken = response.access_token;
            document.getElementById('instagramtoken').value = longLivedUserToken;

            clearError()
            FB.api('/me/accounts', function (response) {

                for (let i = 0; i < response.data.length; i++) {
                    jQuery("#accountoptions").append('<div class="column is-3" >' +
                        '<div class="card" >' +
                        '<p class="title is-4"><strong>' + response.data[i].name + '</strong></p>' +
                        '<p>' + response.data[i].id + '</p>' +
                        '<button class="button is-primary" onclick="chooseCompany(\'' + response
                        .data[i].id + '\',\'' + response.data[i].access_token + '\',\'' +
                        longLivedUserToken +
                        '\')" class="card-footer-item">Select Company</button>' +
                        '</div>' +
                        '</div>');
                }
            });
        });
    }

    function chooseCompany(id, pagetoken, usertoken) {
        let urlParams = "token=" + pagetoken + '&user=' + id

        jQuery.ajax({
            url: "/wp-json/kerigansolutions/v1/instagramdata?" + urlParams
        }).done(function (response) {
            if (response.instagram_business_account) {
                document.getElementById('instagramid').value = response.instagram_business_account.id;
                jQuery("#accountoptions").html('');
            } else {
                jQuery("#error").html('<div class="notification is-danger mb-5">' +
                    '<button class="delete" onclick="clearError()"></button>' +
                    '<p><strong>Error Code ' + response.error.code + ':</strong> ' + response.error
                    .message + '</p>' +
                    '</div>');
            }
        });

    }
</script>