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
<div class="card social-module review-module tripadvisor"  tabindex="0">
    <div class="card-content">
        <div class="review single has-text-centered" >
            <strong class="open-quote dimbo">&ldquo;</strong>
            <p class="review-text"><?= $feed['content']; ?>&rdquo;</p>
            <p class="review-author">
                <strong class="name">&mdash; <?= $feed['author']; ?></strong>
                <span class="rating">rated <?= $stars; ?></span>
                <span class="source">on <?= $feed['location']; ?></span>
                <span class="when"><?= $when; ?></span>
            </p>
        </div>
    </div>
    <div class="card-bottom">
        <a class="button is-primary is-large is-rounded has-shadow" target="_blank" href="https://www.facebook.com/pineapplewillys/reviews/">Read More Reviews</a>
    </div>
</div>