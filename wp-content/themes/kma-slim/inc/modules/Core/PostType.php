<?php

namespace Includes\Modules\Core;

class PostType
{

    public $postType;
    public $menuName;
    public $singularName;
    public $pluralName;
    public $menuIcon;
    public $supports;
    public $hierarchical;
    public $queryvar;
    public $adminOnly;
    public $archiveSlug;

    /**
     * Creates a custom post type
     * @param String postType
     * @param String queryvar Sets the json endpoint and archive urls
     * @param Boolean hierarchical
     * @param Boolean adminOnly
     * @return null
     */
    public function __construct($postType, $queryvar, $hierarchical = false, $adminOnly = false)
    {
        $this->postType = $postType;
        $this->hierarchical = $hierarchical;
        $this->queryvar = $queryvar;
        $this->adminOnly = $adminOnly;
        $this->supports = ['title', 'editor', 'revisions', 'custom-fields'];
        $this->archiveSlug = false;

        return $this;
    }

    /**
     * Adds labels to Custom Post Type
     * @param String menuName
     * @param String singularName
     * @param String pluralName
     * @param String menuIcon
     */
    public function labels($menuName, $singularName, $pluralName, $menuIcon)
    {
        $this->menuName = $menuName;
        $this->singularName = $singularName;
        $this->pluralName = $pluralName;
        $this->menuIcon = $menuIcon;

        return $this;
    }

    /**
     * Adds capabilities to Custom Post Type
     * @param Array supports
     */
    public function capabilities($supports = ['title', 'editor', 'revisions', 'custom-fields'])
    {
        $this->supports = $supports;

        return $this;
    }

    /**
     * Adds the Custom Post Type to WordPress build
     * Add this to the end of build step.
     */
    public function make()
    {
        add_action('init', [$this, 'create']);
        add_filter($this->postType . '_updated_messages', [$this, 'update']);
    }

    public function setArchiveSlug($string)
    {
        $this->archiveSlug = $string;

        return $this;
    }

    public function create()
    {

        $labels = [
            'name' => __($this->menuName),
            'singular_name' => __($this->singularName),
            'all_items' => __($this->menuName),
            'archives' => __($this->menuName . ' Archives'),
            'attributes' => __($this->singularName . ' Attributes'),
            'insert_into_item' => __('Insert into ' . $this->singularName),
            'uploaded_to_this_item' => __('Uploaded to this ' . $this->singularName),
            'featured_image' => _x('Featured Image', $this->postType),
            'set_featured_image' => _x('Set featured image', $this->postType),
            'remove_featured_image' => _x('Remove featured image', $this->postType),
            'use_featured_image' => _x('Use as featured image', $this->postType),
            'filter_items_list' => __('Filter ' . $this->pluralName . ' list'),
            'items_list_navigation' => __($this->menuName . ' list navigation'),
            'items_list' => __($this->menuName . ' list'),
            'new_item' => __('New ' . $this->singularName),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New ' . $this->singularName),
            'edit_item' => __('Edit ' . $this->singularName),
            'view_item' => __('View ' . $this->singularName),
            'view_items' => __('View ' . $this->menuName),
            'search_items' => __('Search ' . $this->menuName),
            'not_found' => __('No ' . $this->pluralName . ' found'),
            'not_found_in_trash' => __('No ' . $this->pluralName . ' found in trash'),
            'parent_item_colon' => __('Parent ' . $this->singularName . ':'),
            'menu_name' => __($this->menuName)
        ];

        $args = [
            'labels' => $labels,
            'public' => !$this->adminOnly,
            'hierarchical' => $this->hierarchical,
            'exclude_from_search' => !$this->adminOnly,
            'publicly_queryable' => !$this->adminOnly,
            'show_ui' => true,
            'show_in_nav_menus' => !$this->adminOnly,
            'supports' => $this->supports,
            'has_archive' => $this->archiveSlug,
            'rewrite' => [
                'slug' => $this->queryvar,
            ],
            'query_var' => $this->postType,
            'menu_icon' => 'dashicons-' . $this->menuIcon,
            'show_in_rest' => true,
            'rest_base' => $this->queryvar,
        ];


        // echo '<pre>',print_r($args),'</pre>';
        
        register_post_type($this->postType, $args);
    }

    /**
     * Sets the post updated messages for the `project` post type.
     *
     * @param  array $messages Post updated messages.
     * @return array Messages for the `project` post type.
     */
    public function update($messages)
    {
        global $post;

        $permalink = get_permalink($post);

        $messages[$this->postType] = array(
            0 => '', // Unused. Messages start at index 1.
            /* translators: %s: post permalink */
            1 => sprintf(__($this->singularName . ' updated. <a target="_blank" href="%s">View ' . $this->singularName . '</a>'), esc_url($permalink)),
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __($this->singularName . ' updated.'),
            /* translators: %s: date and time of the revision */
            5 => isset($_GET['revision']) ? sprintf(__($this->singularName . ' restored to revision from %s'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
            /* translators: %s: post permalink */
            6 => sprintf(__($this->singularName . ' published. <a href="%s">View ' . $this->singularName . '</a>'), esc_url($permalink)),
            7 => __($this->singularName . ' saved.'),
            /* translators: %s: post permalink */
            8 => sprintf(__($this->singularName . ' submitted. <a target="_blank" href="%s">Preview ' . $this->singularName . '</a>'), esc_url(add_query_arg('preview', 'true', $permalink))),
            /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
            9 => sprintf(
                __($this->singularName . ' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview ' . $this->singularName . '</a>'),
                date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)),
                esc_url($permalink)
            ),
            /* translators: %s: post permalink */
            10 => sprintf(__($this->singularName . ' draft updated. <a target="_blank" href="%s">Preview ' . $this->singularName . '</a>'), esc_url(add_query_arg('preview', 'true', $permalink))),
        );

        return $messages;
    }
}
