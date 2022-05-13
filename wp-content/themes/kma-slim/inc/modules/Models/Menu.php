<?php
namespace Includes\Modules\Models;

use Includes\Modules\Core\PostObject;

class Menu extends PostObject {

  public $postType = 'menu-item';
  public $queryVar = 'menu';
  public $menuName = 'Menu';
  public $singularName = 'Menu Item';
  public $pluralName = 'Menu';
  public $capabilities = ['title', 'revisions', 'page-attributes', 'custom-fields'];
  public $hierarchical = false;
  public $adminOnly = true;
  public $restCollection = '/menu';
  public $restSingleton = '/menu';
  public $menuIcon = 'list-view';
  public $enableTaxonomy = true;
  public $taxonomy = 'menu-group';
  public $taxGroups = ['menu-item'];
  public $taxSingularName = 'Menu Category';
  public $taxPluralName = 'Menu Categories';
  public $taxHierarchical = false;
  public $taxPublic = true;
  public $taxRewrite = false;
  public $taxQueryVar = false;
  public $hideSlugs = false;
  public $contentACFEnabled = false;
  public $headerACFEnabled = false;

  // Data
  public $adminColumns = [
    'title'         => 'Item',
    'photo'         => 'Photo',
    'price'         => 'Price',
    'date'          => 'Created'
  ];

  public $customFields = [ 
    'name_english',
    'name_spanish',
    'description_english',
    'description_spanish',
    'price',
    'photo'
  ];

  public function getCategories ()
  {
    $output = [];

    foreach (get_categories('taxonomy='.$this->taxonomy.'&type='.$this->postType) as $category) {
      $category->name_english = get_field('name_english', $this->taxonomy . '_' . $category->cat_ID);
      $category->name_spanish = get_field('name_spanish', $this->taxonomy . '_' . $category->cat_ID); 
      $category->description_english = get_field('description_english', $this->taxonomy . '_' . $category->cat_ID);
      $category->description_spanish = get_field('description_spanish', $this->taxonomy . '_' . $category->cat_ID);
      $category->disclaimer_english = get_field('disclaimer_english', $this->taxonomy . '_' . $category->cat_ID);
      $category->disclaimer_spanish = get_field('disclaimer_spanish', $this->taxonomy . '_' . $category->cat_ID);
      $category->photo = get_field('photo', $this->taxonomy . '_' . $category->cat_ID);
      $category->items = $this->query(
        ['tax_query' => [
            [
              'taxonomy'         => $category->taxonomy,
              'field'            => 'slug',
              'terms'            => $category->slug,
              'include_children' => false,
            ]
          ]
        ]);
      $output[] = $category;
    }

    return $output;
  }

  /**
   * Register ACF fields
   *
   * @return void
   */
  public function registerFields()
  {
    acf_add_local_field_group(array(
      'key' => 'group_'.$this->postType.'_details',
      'title' => $this->singularName . ' Info',
      'location' => array(
          array(
              array(
                  'param' => 'post_type',
                  'operator' => '==',
                  'value' => $this->postType,
              ),
          ),
      ),
      'menu_order' => 0,
      'position' => 'acf_after_title',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
    ));

    // name (spanish)
    acf_add_local_field(array(
      'key' => $this->postType . '_english_name',
      'label' => 'Item Name',
      'name' => 'name_english',
      'type' => 'text',
      'parent' => 'group_'.$this->postType.'_details',
      'instructions' => 'English version',
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
    ));

    // name (spanish)
    acf_add_local_field(array(
      'key' => $this->postType . '_spanish_name',
      'label' => 'Item Name',
      'name' => 'name_spanish',
      'type' => 'text',
      'parent' => 'group_'.$this->postType.'_details',
      'instructions' => 'Spanish version',
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
    ));

    // price
    acf_add_local_field(array(
      'key' => $this->postType . '_price',
      'label' => 'Title',
      'name' => 'price',
      'type' => 'text',
      'parent' => 'group_'.$this->postType.'_details',
      'instructions' => 'Optional',
      'wrapper' => array(
        'width' => '33',
        'class' => '',
        'id' => '',
      ),
    ));

    acf_add_local_field(array(
      'key' => $this->postType . '_description_english',
      'label' => 'Description',
      'name' => 'description_english',
      'type' => 'wysiwyg',
      'parent' => 'group_'.$this->postType.'_details',
      'instructions' => 'English version',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '50',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'tabs' => 'all',
      'toolbar' => 'basic',
      'media_upload' => 0,
      'delay' => 0,
    ));

    acf_add_local_field(array(
      'key' => $this->postType . '_description_spanish',
      'label' => 'Description',
      'name' => 'description_spanish',
      'type' => 'wysiwyg',
      'parent' => 'group_'.$this->postType.'_details',
      'instructions' => 'Spanish version',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '50',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'tabs' => 'all',
      'toolbar' => 'basic',
      'media_upload' => 0,
      'delay' => 0,
    ));
    

    // Image
    acf_add_local_field(array(
      'key' => $this->postType . '_photo',
      'label' => 'Photo',
      'name' => 'photo',
      'type' => 'image',
      'parent' => 'group_'.$this->postType.'_details',
      'instructions' => 'Optional',
      'required' => 0,
      'wrapper' => array(
        'width' => '50',
        'class' => '',
        'id' => '',
      ),
      'menu_order' => 0,
      'return_format' => 'array',
      'preview_size' => 'medium',
      'library' => 'all',
      'min_width' => 0,
      'min_height' => 0,
      'max_width' => 0,
      'max_height' => 0,
    ));

    acf_add_local_field_group(array(
      'key' => 'group_627e5b8a86378',
      'title' => 'Menu Settings',
      'fields' => array(
        array(
          'key' => 'field_627e5b9dc21eb',
          'label' => 'English Disclaimer',
          'name' => 'english_disclaimer',
          'type' => 'wysiwyg',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '50',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'tabs' => 'all',
          'toolbar' => 'basic',
          'media_upload' => 0,
          'delay' => 1,
        ),
        array(
          'key' => 'field_627e5bbfc21ec',
          'label' => 'Spanish Disclaimer',
          'name' => 'spanish_disclaimer',
          'type' => 'wysiwyg',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '50',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'tabs' => 'all',
          'toolbar' => 'basic',
          'media_upload' => 0,
          'delay' => 1,
        ),
        array(
          'key' => 'field_627e5bddc21ed',
          'label' => 'English Menu PDF Download',
          'name' => 'english_menu_pdf_download',
          'type' => 'file',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '50',
            'class' => '',
            'id' => '',
          ),
          'return_format' => 'array',
          'library' => 'all',
          'min_size' => '',
          'max_size' => '',
          'mime_types' => '',
        ),
        array(
          'key' => 'field_627e5c0fc21ee',
          'label' => 'Spanish Menu PDF Download',
          'name' => 'spanish_menu_pdf_download',
          'type' => 'file',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '50',
            'class' => '',
            'id' => '',
          ),
          'return_format' => 'array',
          'library' => 'all',
          'min_size' => '',
          'max_size' => '',
          'mime_types' => '',
        ),
        array(
          'key' => 'field_627e5c21c21ef',
          'label' => 'English Allergen Menu Guide',
          'name' => 'english_allergen_menu_guide',
          'type' => 'file',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '50',
            'class' => '',
            'id' => '',
          ),
          'return_format' => 'array',
          'library' => 'all',
          'min_size' => '',
          'max_size' => '',
          'mime_types' => '',
        ),
        array(
          'key' => 'field_627e5c3ec21f0',
          'label' => 'Spanish Allergen Menu Guide',
          'name' => 'spanish_allergen_menu_guide',
          'type' => 'file',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '50',
            'class' => '',
            'id' => '',
          ),
          'return_format' => 'array',
          'library' => 'all',
          'min_size' => '',
          'max_size' => '',
          'mime_types' => '',
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'page',
            'operator' => '==',
            'value' => '15',
          ),
        ),
      ),
      'menu_order' => 0,
      'position' => 'acf_after_title',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => true,
      'description' => '',
      'show_in_rest' => 0,
    ));
      
  }

  public function removeDefaultDescription()
  {
    echo "<style> .term-description-wrap { display:none; } </style>";
  }

  public function registerTaxonomyFields()
  {
    add_action( $this->taxonomy . "_edit_form", [$this,'removeDefaultDescription']);
    add_action( $this->taxonomy . "_add_form", [$this,'removeDefaultDescription']);

    add_filter('manage_edit-' . $this->taxonomy . '_columns', function ( $columns ) {
      if( isset( $columns['description'] ) )
          unset( $columns['description'] );   
      return $columns;
    });

    acf_add_local_field_group(array(
      'key' => $this->taxonomy . '_custom_fields',
      'title' => 'Menu Category Fields',
      'fields' => array(

        array(
          'key' => $this->postType . '_english_name',
          'label' => 'English Name',
          'name' => 'name_english',
          'type' => 'text',
          'parent' => 'group_'.$this->postType.'_details',
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
        ),

        array(
          'key' => $this->taxonomy . '_spanish_name',
          'label' => 'Spanish Name',
          'name' => 'name_spanish',
          'type' => 'text',
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
        ),

        array(
          'key' => $this->taxonomy . '_photo',
          'label' => 'Photo',
          'name' => 'photo',
          'type' => 'image',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'return_format' => 'array',
          'preview_size' => 'medium',
          'library' => 'all',
          'min_width' => '',
          'min_height' => '',
          'min_size' => '',
          'max_width' => '',
          'max_height' => '',
          'max_size' => '',
          'mime_types' => '',
        ),

        array(
          'key' => $this->taxonomy . '_description_english',
          'label' => 'English Description',
          'name' => 'description_english',
          'type' => 'wysiwyg',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'tabs' => 'all',
          'toolbar' => 'basic',
          'media_upload' => 0,
          'delay' => 1,
        ),

        array(
          'key' => $this->taxonomy . '_description_spanish',
          'label' => 'Spanish Description',
          'name' => 'description_spanish',
          'type' => 'wysiwyg',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'tabs' => 'all',
          'toolbar' => 'basic',
          'media_upload' => 0,
          'delay' => 1,
        ),

        array(
          'key' => $this->taxonomy . '_disclaimer_english',
          'label' => 'English Disclaimer',
          'name' => 'disclaimer_english',
          'type' => 'wysiwyg',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'tabs' => 'all',
          'toolbar' => 'basic',
          'media_upload' => 0,
          'delay' => 1,
        ),

        array(
          'key' => $this->taxonomy . '_disclaimer_spanish',
          'label' => 'Spanish Disclaimer',
          'name' => 'disclaimer_spanish',
          'type' => 'wysiwyg',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'tabs' => 'all',
          'toolbar' => 'basic',
          'media_upload' => 0,
          'delay' => 1,
        ),

      ),
      'location' => array(
        array(
          array(
            'param' => 'taxonomy',
            'operator' => '==',
            'value' => 'menu-group',
          ),
        ),
      ),
      'menu_order' => 0,
      'position' => 'acf_after_title',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'active' => true,
      'description' => '',
      'show_in_rest' => 0,
    ));

  }
}
