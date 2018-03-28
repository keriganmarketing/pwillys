<?php

namespace Includes\Modules\Menu;

use KeriganSolutions\CPT\CustomPostType;

/**
 * Menu Class
 */

 // Exit if accessed directly.
 if (! defined('ABSPATH')) {
     exit;
 }

class Menu
{
    public function setupAdmin()
    {
        $this->createPostType();
        $this->createAdminColumns();
    }

    /**
     * @return null
     */
    public function createPostType()
    {
        $item = new CustomPostType('Menu Item', array(
            'supports'           => array( 'title', 'revisions', 'page-attributes' ),
            'menu_icon'          => 'dashicons-images-alt2',
            'rewrite'            => array( 'with_front' => false ),
            'hierarchical'       => false,
            'has_archive'        => false,
            'menu_position'      => null,
            'public'             => false,
            'publicly_queryable' => false,
        ));

        $item->addTaxonomy('Menu Category');

        $item->addMetaBox('Entree Info', array(
            'Photo File'      => 'image',
            'Price'           => 'text',
        ));

        $item->addMetaBox(
            'Description',
            array(
                'HTML' => 'wysiwyg',
            )
        );
    }

    /**
     * @return null
     */
    public function createAdminColumns()
    {
        add_filter('manage_menu_item_posts_columns',
            function ($defaults) {
                $defaults = [
                    'cb'       => '<input type="checkbox">',
                    'title'    => 'Item Name',
                    'category' => 'Category',
                    'price'    => 'Price',
                    'date'     => 'Date'
                ];

                return $defaults;
            }, 0);

        add_action('manage_menu_item_posts_custom_column', function ($column_name, $post_ID) {
            switch ($column_name) {
                case 'category':
                    $term = wp_get_object_terms($post_ID, 'menu_category');
                    echo(isset($term[0]->name) ? $term[0]->name : null);
                    break;

                case 'price':
                    $content = get_post_meta($post_ID, 'entree_info_price', true);
                    echo(isset($content) ? $content : null);
                    break;

            }
        }, 0, 2);

        add_action('restrict_manage_posts', function () {
            $type = 'post';
            if (isset($_GET['post_type'])) {
                $type = $_GET['post_type'];
            }

            if ('menu_item' == $type) {

                $values = get_terms([
                    'taxonomy'   => 'menu_category',
                    'hide_empty' => false,
                ]);

                echo '<select name="menu_category">
                    <option value="">All Categories</option>';

                $current_v = isset($_GET['menu_category']) ? $_GET['menu_category'] : '';
                foreach ($values as $label => $value) {
                    printf
                    (
                        '<option value="%s"%s>%s</option>',
                        $value->slug,
                        $value->slug == $current_v ? ' selected="selected"' : '',
                        $value->name
                    );
                }

                echo '</select>';

            }
        });

    }

    /**
     * @param item ( post type category )
     * @return array
     */
    public function getMenuItems($category = '')
    {
        $request = array(
            'posts_per_page' => - 1,
            'offset'         => 0,
            'order'          => 'ASC',
            'orderby'        => 'menu_order',
            'post_type'      => 'menu_item',
            'post_status'    => 'publish',
        );

        if ($category != '') {
            $categoryarray = array(
                array(
                    'taxonomy'         => 'menu_category',
                    'field'            => 'slug',
                    'terms'            => $category,
                    'include_children' => false,
                ),
            );
            $request['tax_query'] = $categoryarray;
        }

        $itemlist = get_posts($request);

        $itemArray=[];
        foreach ($itemlist as $item) {
            $itemArray[] = [
                'id'            => (isset($item->ID)                               ? $item->ID : null),
                'name'          => (isset($item->post_title)                       ? $item->post_title : null),
                'slug'          => (isset($item->post_name)                        ? $item->post_name : null),
                'photo'         => (isset($item->entree_info_photo_file)          ? $item->entree_info_photo_file : null),
                'price'         => (isset($item->entree_info_price)               ? $item->entree_info_price : null),
                'description'   => (isset($item->description_html)                 ? $item->description_html : null),
                'link'          => get_permalink($item->ID),
            ];
        }

        return $itemArray;
    }

    public function getMenuCategories()
    {
        $menu_category_terms = get_terms('menu_category', array(
            'hide_empty' 		=> 1,
            'orderby' 			=> 'menu_order',
            'hierarchical'		=> true,
            'order'         	=> 'ASC',
            'offset'			=> 0
        ));

        return $menu_category_terms;
    }
    /**
     * @param item ( post type category )
     * @return HTML
     */
    public function getMenu()
    {
        $categories = $this->getMenuCategories();

        $output = [];

        foreach($categories as $category) {
            $output[$category->term_id]['menu_items'] = $this->getMenuItems($category->slug);
            $output[$category->term_id]['category_name'] = $category->name;
        }
        return $output;

    }
}
