<?php

use Includes\Modules\Layouts\Layouts;
use Includes\Modules\Menu\Menu;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */

$menu           = new Menu();
$menuCategories = $menu->getMenu();

include(locate_template('template-parts/sections/top.php'));
?>
    <div id="mid">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
            <section id="content" class="section support">
                <div class="container">
                    <div class="entry-content content">
                        <?php the_content(); ?>

                        <div class="section">
                            <div v-masonry transition-duration="0.3s" item-selector=".menu-category"
                                 class="our-menu columns is-multiline">
                                <?php foreach ($menuCategories as $menuCategory) { ?>
                                    <div v-masonry-tile class="menu-category column is-6 is-4-widescreen">
                                        <h2 class="title is-2 tandelle"><?= $menuCategory['category_name']; ?></h2>
                                        <?php foreach ($menuCategory['menu_items'] as $menuItem) { ?>
                                            <?php include(locate_template('template-parts/partials/mini-menu-item.php')); ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </article>
        <?php include(locate_template('template-parts/sections/social-media.php')); ?>
    </div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>