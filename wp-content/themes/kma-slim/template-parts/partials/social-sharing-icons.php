<div class="kma-social-share">
    <p class="kma-social-share-label dimbo is-primary"><?php echo $label; ?></p>
    <div class="kma-social-share-icons" >
    <social-sharing-icons
        network="email"
        icon="envelope"
        class="share-network email"
        url="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>"
        title="<?php echo the_title(); ?>"
    ></social-sharing-icons>
    <social-sharing-icons
        network="facebook"
        icon="facebook"
        class="share-network facebook"
        url="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>"
        title="<?php echo the_title(); ?>"
    ></social-sharing-icons>
    <social-sharing-icons
        network="linkedin"
        icon="linkedin"
        class="share-network linkedin"
        url="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>"
        title="<?php echo the_title(); ?>"
    ></social-sharing-icons>
    <social-sharing-icons
        network="twitter"
        icon="twitter"
        class="share-network twitter"
        url="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>"
        title="<?php echo the_title(); ?>"
    ></social-sharing-icons>
    </div>
</div>