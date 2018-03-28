<?php
use Includes\Modules\KMAFacebook\FacebookController;

//$facebook = new FacebookController();
//$feed = $facebook->getReviews(10);

//echo '<pre>',print_r($feed),'</pre>';

$when = human_time_diff(strtotime('now')) . ' ago';
$stars = '';
for($i=0; $i<5; $i++){
    $stars .= '<span class="icon is-small">
                <i class="fa fa-star" aria-hidden="true"></i>
               </span>';
}
?>
<div class="review-module">
    <div class="container">
        <div class="columns is-centered is-justified" >
            <div class="column is-10-tablet is-7-desktop" >
                <div class="review single has-text-centered" >
                    <span class="open-quote">&ldquo;</span>
                    <p class="review-text is-large is-info">&ldquo;Love this place! It’s our must do while in town.  Great food, great view, and get people!&rdquo;</p>
                    <p class="review-author">
                        <span class="name">&mdash; Amanda</span>
                        <span class="rating">rated <?= $stars; ?></span>
                        <span class="source">on Facebook</span>
                        <span class="when"><?= $when; ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>