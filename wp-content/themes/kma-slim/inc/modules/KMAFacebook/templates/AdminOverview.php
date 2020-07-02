<?php

use Includes\Modules\KMAFacebook\FacebookController;

?>
<div class="page-wrapper" style="margin-left:-20px;">
    <div class="hero is-dark">
        <div class="hero-body">
            <div class="columns is-vcentered">
                <div class="column">
                    <p class="title">
                        Facebook Settings
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

                $facebook = new FacebookController();

                $FacebookPageID = (isset($_POST['facebook_page_id']) ? sanitize_text_field($_POST['facebook_page_id']) : get_option('facebook_page_id'));
                $FacebookToken  = (isset($_POST['facebook_token']) ? sanitize_text_field($_POST['facebook_token']) : get_option('facebook_token'));

                if (isset($_POST['facebook_submit_settings']) && $_POST['facebook_submit_settings'] == 'yes') {
                    update_option('facebook_page_id',
                        isset($_POST['facebook_page_id']) ? sanitize_text_field($_POST['facebook_page_id']) : $FacebookPageID);
                    update_option('facebook_token',
                        isset($_POST['facebook_token']) ? sanitize_text_field($_POST['facebook_token']) : $FacebookToken);
                }


                if(!get_option('facebook_token') && !isset($_GET['code'])){ ?>
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

                <form enctype="multipart/form-data" name="facebook_settings" id="facebook_settings" method="post"
                    action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                    <input type="hidden" name="facebook_submit_settings" value="yes">

                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="editor-wrapper">
                                <h4 class="wp-heading-inline">Facebook Page ID</h4>
                                <div class="control">
                                    <input type="text" class="input" name="facebook_page_id" id="fbcompanyid"
                                        value="<?= $FacebookPageID; ?>" size="40">
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="editor-wrapper">
                                <h4 class="wp-heading-inline">Facebook Access Token</h4>
                                <div class="control">
                                    <input type="text" class="input" name="facebook_token" id="facebooktoken"
                                        value="<?= $FacebookToken; ?>" size="40">
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="submit">
                        <input class="button is-info" type="submit" name="Submit"
                            value="<?php _e('Update Settings') ?>" />

                        <a onclick="checkStatus();" class="button is-primary"><?php _e('Renew Authorization') ?></a>

                        <a onclick="forceSync();" class="button is-primary ml-5"><?php _e('Force Sync') ?></a> <span id="syncstatus"></span>
                    </p>

                </form>
                <hr>
                <?php include('facebook-module.php'); ?>
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
            appId: '<?php echo $facebook->appId; ?>',
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
            scope: 'pages_show_list, pages_read_engagement, pages_read_user_content, public_profile'
        });
    }

    function exchangeToken(token) {
        jQuery.ajax({
            url: "/wp-json/kerigansolutions/v1/autoblogtoken?token=" + token
        }).done(function (response) {
            let longLivedUserToken = response.access_token;
            document.getElementById('facebooktoken').value = longLivedUserToken;

            clearError()
            FB.api('/me/accounts', function (response) {
                for (let i = 0; i < response.data.length; i++) {
                    jQuery("#accountoptions").append('<div class="column is-3" >' +
                        '<div class="card" >' +
                        '<p class="title is-4"><strong>' + response.data[i].name + '</strong></p>' +
                        '<p>' + response.data[i].id + '</p>' +
                        '<button class="button is-primary" onclick="chooseCompany(\'' + response
                        .data[i].id + '\')" class="card-footer-item">Select Company</button>' +
                        '</div>' +
                        '</div>');
                }
            });
        });
    }

    function chooseCompany(id) {
        document.getElementById('fbcompanyid').value = id;
        jQuery("#accountoptions").html('');
    }

    function forceSync(){
        jQuery("#syncstatus").text('Please wait...');
        jQuery.ajax({
            url: "/wp-json/kerigansolutions/v1/forcefbsync"
        }).done(function (response) {
            if(response.success == true){
                jQuery("#syncstatus").text('Done!');
            }else{
                jQuery("#syncstatus").text('Error!');
                jQuery("#error").html('<div class="notification is-danger mb-5">' +
                '<button class="delete" onclick="clearError()"></button>' +
                '<p><strong>Error Code ' + response.error.code + ':</strong> ' + response.error
                .message + '</p>' +
                '</div>');
                console.log(response)
            }
        })
    }
</script>