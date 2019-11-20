<?php
use Includes\Modules\KMAInstagram\InstagramController;

$instagram = new InstagramController();
$photos = $instagram->getFeed(9);

if(count(json_decode($photos))>0){
?>
<div class="card social-module instagram has-text-centered">
    <div class="card-content">
        <insta-gallery :photos='<?= $photos; ?>' ></insta-gallery>
    </div>
    <div class="card-bottom">
        <a target="_blank" href="https://www.instagram.com/pwillys/" class="button is-primary is-large is-rounded has-shadow">View more on Instagram</a>
    </div>
</div>
<?php } ?>