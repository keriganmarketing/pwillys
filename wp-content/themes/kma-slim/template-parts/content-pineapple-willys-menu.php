<?php

use Includes\Modules\Layouts\Layouts;
use Includes\Modules\Models\Menu;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */

$menu           = new Menu();
$menuCategories = $menu->getCategories();

$englishDisclaimer = get_field('english_disclaimer');
$spanishDisclaimer = get_field('spanish_disclaimer') ? get_field('spanish_disclaimer') : $englishDisclaimer;
$englishMenuPDF = get_field('english_menu_pdf_download');
$spanishMenuPDF = get_field('spanish_menu_pdf_download') ? get_field('spanish_menu_pdf_download') : $englishMenuPDF;
$englishAllergenPDF = get_field('english_allergen_menu_guide');
$spanishAllergenPDF = get_field('spanish_allergen_menu_guide') ? get_field('spanish_allergen_menu_guide') : $englishAllergenPDF;

include(locate_template('template-parts/sections/top.php'));
?>
    <div id="mid">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
            <section id="content" class="section support">
                <div class="container">
                    <div class="entry-content content">
                        <?php the_content(); ?>

                        <food-menu 
                            <?php if($englishDisclaimer){ ?>
                            english-disclaimer='<?php echo $englishDisclaimer; ?>'
                            <?php } ?>
                            <?php if($spanishDisclaimer){ ?>
                            spanish-disclaimer='<?php echo $spanishDisclaimer; ?>'
                            <?php } ?>
                            <?php if($englishMenuPDF){ ?>
                            :english-menu-pdf='<?php echo json_encode($englishMenuPDF); ?>'
                            <?php } ?>
                            <?php if($spanishMenuPDF){ ?>
                            :spanish-menu-pdf='<?php echo json_encode($spanishMenuPDF); ?>'
                            <?php } ?>
                            <?php if($englishAllergenPDF){ ?>
                            :english-allergen-pdf='<?php echo json_encode($englishAllergenPDF); ?>'
                            <?php } ?>
                            <?php if($spanishAllergenPDF){ ?>
                            :spanish-allergen-pdf='<?php echo json_encode($spanishAllergenPDF); ?>'
                            <?php } ?>
                        ></food-menu>
                        
                    </div>
                </div>
            </section>
        </article>
        <?php include(locate_template('template-parts/sections/social-media.php')); ?>
    </div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>