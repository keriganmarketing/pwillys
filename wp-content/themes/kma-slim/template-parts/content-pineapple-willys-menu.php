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

include(locate_template('template-parts/sections/top.php'));
?>
    <div id="mid">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
            <section id="content" class="section support">
                <div class="container">
                    <div class="entry-content content">
                        <?php the_content(); ?>

                        <food-menu :categories='<?php echo json_encode($menuCategories); ?>'></food-menu>
                        
                    </div>
                </div>
            </section>
        </article>
        <?php include(locate_template('template-parts/sections/social-media.php')); ?>
    </div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>