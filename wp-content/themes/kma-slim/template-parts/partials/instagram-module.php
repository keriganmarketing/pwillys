<?php
use Includes\Modules\KMAInstagram\InstagramController;

$instagram = new InstagramController();
$photos = $instagram->getFeed(9);

if(count(json_decode($photos))>0){
?>
<div class="card social-module instagram has-text-centered">
    <div class="card-content">
        <h2 class="title dimbo is-primary is-3">Follow Us</h2>
        <p class="subtitle">on Instagram</p>
        <insta-gallery :photos='<?= $photos; ?>' ></insta-gallery>
    </div>
    <div class="card-bottom">
        <a target="_blank" href="https://www.instagram.com/pwillys/" class="button is-primary is-large is-rounded has-shadow">View more Photos</a>
    </div>
</div>
<?php } ?>