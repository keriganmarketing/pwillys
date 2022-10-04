<?php

namespace Includes\Modules\Core;

/**
 * Basic Object for extending into specific ones. this Object can function on it's own, so the best way to make another WP eature is to extend this and change the public variables as needed, cutting out the repetitiveness of making classic CPTs.
 *
 * Each custom post type or Taxonomy created using this model will have available ACF fields, custom sortable columns and REST endpoints for a collection of objects or a singleton version.
 *
 * Happy editing.
 */
class PostObject
{

  public $postType = 'object';
  public $queryVar = 'objects';
  public $menuName = 'Objects';
  public $singularName = 'Objects';
  public $pluralName = 'Objects';
  public $capabilities = ['title', 'editor', 'revisions', 'page-attributes', 'custom-fields'];
  public $hierarchical = false;
  public $adminOnly = false;
  public $restCollection = '/objects';
  public $restSingleton = '/object';
  public $menuIcon = 'media-default';
  public $enableTaxonomy = true;
  public $taxonomy = 'group';
  public $taxGroups = ['object'];
  public $taxSingularName = 'Item';
  public $taxPluralName = 'Group';
  public $taxHierarchical = false;
  public $taxPublic = true;
  public $taxRewrite = false;
  public $taxQueryVar = false;
  public $hideSlugs = false;

  // Data
  public $adminColumns = [
    'title'     => 'Title',
    'body_text' => 'Intro Text',
    'author'    => 'Author',
    'date'      => 'Created',
  ];

  public $customFields = [
    'meta_key'
  ];

  public function __construct()
  {
    // Silence is golden.
  }

  /**
   * Set up our Object and hook it into WP for optimum administration experience
   *
   * @return void
   */
  public function use()
  {

    (new PostType(
      $this->postType,
      $this->queryVar,
      $this->hierarchical,
      $this->adminOnly
    ))->labels(
      $this->menuName,
      $this->singularName,
      $this->pluralName,
      $this->menuIcon
    )->capabilities(
      $this->capabilities
    )->make();

    if($this->enableTaxonomy){
      new Taxonomy(
        $this->taxonomy,
        $this->taxSingularName,
        $this->taxPluralName,
        [$this->postType],
        $this->taxPublic,
        $this->taxRewrite,
        $this->taxQueryVar
      );

      if ( function_exists( 'acf_add_local_field_group' ) ) {
        $this->registerTaxonomyFields();
      }
      
    }

    if ( function_exists( 'acf_add_local_field_group' ) ) {
      add_action( 'init', [$this, 'registerFields'] );
    }

    add_action('rest_api_init', [$this, 'registerRoutes']);

    if($this->hideSlugs){
      add_action( 'admin_menu' , function(){
        remove_meta_box('slugdiv', $this->postType, 'normal');
      });
    }

    $this->mount();

    add_filter( 'manage_' . $this->postType . '_posts_columns', [$this, 'setPostColumns'] );
    add_action( 'manage_' . $this->postType . '_posts_custom_column', [$this, 'setColumnContent'], 10, 2);
    add_filter( 'manage_edit-' . $this->postType . '_sortable_columns', [$this, 'setSortableColumns'] );
    add_action( 'pre_get_posts', [$this, 'setOrdering'] );
  }

  /**
   * Add REST API routes
   *
   * @return void
   */
  public function registerRoutes()
  {
    register_rest_route(
      'kma/v1',
      $this->restCollection,
      [
        'methods' => 'GET',
        'callback' => [$this, 'restCollection'],
        'permission_callback' => '__return_true'
      ]
    );

    register_rest_route(
      'kma/v1',
      $this->restSingleton,
      [
        'methods' => 'GET',
        'callback' => [$this, 'restSingleton'],
        'permission_callback' => '__return_true'
      ]
    );

    $this->addRestRoutes();
  }

  /**
   * Get a Collection of Objects using the REST API
   *
   * @param HTTP_REQUEST $request
   * @return json
   */
  public function restCollection($request)
  {
    $this->params = $request->get_params();

    $args = [
      'posts_per_page' => isset($this->params['limit']) && $this->params['limit'] ? $this->params['limit'] : -1,
      'order'          => isset($this->params['order']) && $this->params['order'] ? $this->params['order'] : 'DESC',
      'orderby'        => isset($this->params['orderby']) && $this->params['orderby'] ? $this->params['orderby'] : 'date',
    ];

    if ( isset($this->params['category']) && $this->params['category'] ) {
      $args['tax_query'] = [
        [
          'taxonomy'         => $this->taxonomy,
          'field'            => 'slug',
          'terms'            => $this->params['category'],
          'include_children' => false,
        ],
      ];
    }

    return rest_ensure_response($this->query($args));
  }

  /**
   * Query WP for a single Object and package for REST Response
   *
   * @param HTTP_REQUEST $request
   * @return json
   */
  public function restSingleton($request)
  {
    return rest_ensure_response($this->queryBySlug($request->get_param('slug')));
  }

  /**
   * Query WP for posts matching a supplied slug
   *
   * @param String $slug
   * @return json
   */
  public function queryBySlug($slug)
	{
		$posts = get_posts([
			'name'        => $slug,
			'post_type'   => $this->postType,
			'post_status' => 'publish',
			'numberposts' => 1
		]);

		if(is_array($posts) && isset($posts[0])) {
			$restRequest = new \WP_REST_Request();
			$restController = new \WP_REST_Posts_Controller($posts[0]->post_type);

			$post = new \stdClass;
			$post->page = $restController->prepare_item_for_response($posts[0], $restRequest);
			$post->meta = get_post_meta($posts[0]->ID, '', true);
			$post->seo = new \stdClass;
			$post->seo->title = get_post_meta($posts[0]->ID, 'rank_math_title', true);
			$post->seo->description = get_post_meta($posts[0]->ID, 'rank_math_description', true);
			$post->seo->canonical_url = get_post_meta($posts[0]->ID, 'rank_math_canonical_url', true);
			$post->seo->robots = get_post_meta($posts[0]->ID, 'rank_math_robots', true);
			$post->seo->schema = get_post_meta($posts[0]->ID, 'rm_rank_math_schema', true);
      $post->sidebar = new \stdClass;
      $post->sidebar->items = $this->sidebarItems();
      $post->sidebar->title = $this->pluralName;

			return $post;
		}

		return new \WP_Error( 'not_found', __('A '.$this->singularName.' was not found matching the supplied slug'), array( 'status' => 404 ) );
	}

  /**
   * get sidebar items to include in singleton object
   */
  public function sidebarItems( $limit = -1, $orderby = 'menu_order', $order = 'ASC' )
  {
    $output = [];
    foreach($this->query([
      'posts_per_page' => $limit,
      'orderby'        => $orderby,
      'order'          => $order
    ]) as $post){
      $output[] = [
        'link'   => get_permalink($post->ID),
        'title'  => $post->title,
        'slug'   => $post->slug,
        'target' => '_self',
        'ID'     => $post->ID
      ];
    }
    return $output;
  }

  /**
   * queries WP for posts matching criteria in supplied arguments
   *
   * @param Array $args
   * @return Array
   */
  public function query($args = [])
  {
    $request = [
      'posts_per_page' => -1,
      'offset'         => 0,
      'order'          => 'ASC',
      'orderby'        => 'menu_order',
      'post_type'      => $this->postType,
      'post_status'    => 'publish',
      'exclude'        => get_option('page_on_front')
    ];

    $postList = get_posts( array_merge( $request, $args ) );

    $output = [];
    foreach ( $postList as $post ) {
      $object         = new \stdClass;
      $object->ID     = $post->ID;
      $object->title  = $post->post_title;
      $object->slug   = $post->post_name;
      $object->date   = $post->post_date;
      $object->photo  = $post->post_photo;
      $object->link   = get_permalink($post->ID);
      $object->days_ago = $this->getDaysAgo($post->post_date);

      if($this->enableTaxonomy){
        $term = wp_get_object_terms( $post->ID, $this->taxonomy );
        $object->taxonomy = ( isset($term[0]) ? $term[0]->name : '');
      }

      foreach($this->customFields as $metaKey){
        $object->{$metaKey} = get_field($metaKey, $post->ID);
      }

      $output[] = $object;
    }

    return $output;
  }

  public function getObjectSlugs()
  {
    $request = [
      'posts_per_page' => -1,
      'offset'         => 0,
      'post_type'      => $this->postType,
      'post_status'    => 'publish',
    ];

    $posts = get_posts($request);
    $output = [];

    foreach($posts as $post){
      $output[] = $this->queryVar . '/' .$post->post_name;
    }

    return $output;
  }

  /**
   * Create a time elapsed string
   */
  public function getDaysAgo($datetime, $full = false) {
    $now = new \DateTime;
    $ago = new \DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
    );
    foreach ($string as $k => &$v) {
      if ($diff->$k) {
        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
        unset($string[$k]);
      }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }

  /**
   * Filters WP Columns for Object
   *
   * @return Array
   */
  public function setPostColumns()
  {
    $columns = ['cb' => '<input type="checkbox" />'];
    foreach($this->adminColumns as $key => $var){
        $columns[$key] = $var;
    }
    $columns['date'] = 'Created';

    return $columns;
  }

  /**
   * Echoes content based on column in admin views
   *
   * @param String $column
   * @param Number $post_id
   * @return void
   */
  public function setColumnContent( $column, $post_id )
  {
    switch ($column) {
      // column name includes image or photo
      case (preg_match('/image|photo/i', $column) ? true : false):
        $image = get_field($column, $post_id);
        if(isset($image['sizes']['thumbnail'])){
          echo '<img src="'.$image['sizes']['thumbnail'].'" width="100" style="border-radius:.5rem;" >';
        }
      break;

      // column name includes email
      case strpos($column,'email'):
        $email = get_post_meta($post_id, $column, true);
        echo '<a href="mailto:' . $email . '" >' . $email . '</a>';
      break;

      // column name includes http/s
      case strpos($column,'http'):
        $http = get_post_meta($post_id, $column, true);
        echo '<a href="' . $http . '" >' . $http . '</a>';
      break;

      // any other column name
      default:
        $content = get_post_meta($post_id, $column, false);
        $i = 0;
        foreach($content as $item){
          $i++;
          echo $item . (count($content) > $i ? ',' : '');
        }
      break;
    }
  }

  /**
   * Filter columns selected for sorting featurses by WP
   *
   * @param Array $columns
   * @return Array
   */
  public function setSortableColumns($columns)
  {
    foreach($this->adminColumns as $key => $var){
      $columns[$key] = $key;
    }

    return $columns;
  }

  /**
   * Augments current post query to add column sorting in admin views
   *
   * @param WP_QUERY_OBJECT $query
   * @return void
   */
  public function setOrdering($query)
  {
    if ( ! is_admin() )
      return;

    $orderby = $query->get( 'orderby');

    foreach($this->adminColumns as $key => $var){
      if ( $key == $orderby ) {
        $meta_query = array(
          'relation' => 'OR',
          array(
            'key' => $key,
            'compare' => 'NOT EXISTS',
          ),
          array(
            'key' => $key,
          ),
        );

        $query->set( 'meta_query', $meta_query );
        $query->set( 'orderby', 'meta_value' );
      }
    }
  }

  /**
   * Register ACF Fields for Object
   * Intended for use when extending
   *
   * @return void
   */
  public function registerFields()
  {
  }

  /** Register ACF fields on Object Taxonomy
   * Intended for use when extending
   *
   * @return void
   */
  public function registerTaxonomyFields()
  {
  }

  /**
   * Add additional REST routes to default singleton and collection presets
   * Intended for use when extending
   *
   * @return void
   */
  public function addRestRoutes()
  {
  }

  /** Hook to allow extended objects to easily run functions on mount
   *
   * @return void
   */
  public function mount()
  {

  }
}

