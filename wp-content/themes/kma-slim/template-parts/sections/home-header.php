<?php
use Includes\Modules\Slider\BulmaSlider;
?>
<div class="home-header">
    <slider>
        <?php
        $slider = new BulmaSlider();
        $slides = $slider->getSlides('home-page-slider');
        $slider = '';

        $i = 0;
        foreach($slides as $slide){
            $slider .= '<slide :id="'.number_format($i).'" image="'.$slide['photo'].'" >
                        <section class="slide-content">'
                            . ($slide['headline'] != '' ? '<h2 class="title is-1 is-primary">'.$slide['headline'].'</h2>' : '')
                            . ($slide['caption'] != '' ? '<p class="slider-subtitle">'.$slide['caption'].'</p>' : '')
                            . ($slide['description'] != '' ? '<div class="slider-description">'.$slide['description'].'</div>' : '')
                            . ($slide['url'] != '' ? '<a class="button is-primary is-rounded has-shadow" href="'.$slide['link'].'">'
                            . ($slide['button_text'] != '' ? $slide['button_text'] : 'More Info') . '</a>' : '') .
                        '</section>
                        </slide>';
            $i++;
        }
        echo $slider;

        ?>
    </slider>
</div>
<div class="menu-button-mobile-only is-hidden-tablet has-text-centered">
    <a class="menu-button button is-primary is-rounded has-shadow" href="/pineapple-willys-menu/">View Our Menu</a>
</div>