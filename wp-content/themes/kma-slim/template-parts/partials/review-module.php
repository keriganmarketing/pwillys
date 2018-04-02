<?php
//use Includes\Modules\KMAFacebook\FacebookController;
use Includes\Modules\Reviews\Reviews;

//$facebook = new FacebookController();
//$feed = $facebook->getReviews(10);

$reviews = new Reviews();
$feed = $reviews->getRecentReview();

//echo '<pre>',print_r($feed),'</pre>';

$when = human_time_diff(strtotime($feed['date'])) . ' ago';
$stars = '';
for($i=0; $i<floor($feed['rating']); $i++){
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
                    <p class="review-text is-large is-info">&ldquo;<?= $feed['content']; ?>&rdquo;</p>
                    <p class="review-author">
                        <span class="name">&mdash; <?= $feed['author']; ?></span>
                        <span class="rating">rated <?= $stars; ?></span>
                        <span class="source">on <?= $feed['location']; ?></span>
                        <span class="when"><?= $when; ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>