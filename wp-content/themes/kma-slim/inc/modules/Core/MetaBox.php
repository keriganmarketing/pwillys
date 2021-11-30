<?php

namespace Includes\Modules\Core;

class MetaBox
{

    public $postType;
    public $fieldName;
    public $fieldLabel;
    public $type;
    public $callback;

    /**
     * Creates a custom post type
     * @param String postType
     * @param String fieldName
     * @param String fieldLabel
     * @param String type (text, email, longtext)
     * @param Function callbackFunction
     * @return null
     */
    public function __construct($postType, $fieldName, $fieldLabel, $type = 'text', $callbackFunction = null)
    {
        $this->postType = $postType;
        $this->fieldName = $fieldName;
        $this->fieldLabel = $fieldLabel;
        $this->type = $type;
        $this->callback = $callbackFunction != null ? $callbackFunction : [$this, $this->type . '_metabox'];

        add_action('add_meta_boxes', [$this, 'createMetaBox']);
    }

    /**
     * Creates a WordPress meta box
     */
    public function createMetaBox()
    {
        add_meta_box(
            $this->fieldName,
            $this->fieldLabel,
            $this->callback,
            $this->postType,
            'normal',
            'default'
        );
    }

    function email_metabox()
    {
        global $post;
        $email = get_post_meta($post->ID, $this->fieldName, true);
        echo '<a class="custom-meta-data email" href="mailto:' . $email . '">' . $email .'</a>';
    }

    function text_metabox()
    {
        global $post;
        $text = get_post_meta($post->ID, $this->fieldName, true);
        echo '<span class="custom-meta-data text">' . $text .'</span>';
    }

    function longtext_metabox()
    {
        global $post;
        $longtext = get_post_meta($post->ID, $this->fieldName, true);
        echo '<p class="custom-meta-data longtext">'. $longtext . '</p>';
    }

    function data_metabox()
    {
        global $post;
        $data = get_post_meta($post->ID, $this->fieldName, true);
        echo '<div class="custom-meta-data raw">' . $data . '</div>';
    }
}
