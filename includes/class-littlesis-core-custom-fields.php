<?php
/**
 * LittleSis Core
 *
 * @package    LittleSis_Core
 * @subpackage LittleSis_Core\Includes
 * @since      0.1.1
 * @license    GPL-2.0+
 */

 /**
  * Register Custom Fields
  *
  * @since 0.1.1
  *
  */
 class LittleSis_Core_Custom_Fields {

     private $slug = '';

     /**
      * Initialize all the things
      *
      * @since  0.1.1
      *
      */
     function __construct() {

         if( function_exists( 'acf_add_local_field_group' ) ) {
           $this->register_clone_fields();
           $this->register_field_groups();
           $this->register_settings_fields();
         }
     }

     /**
      * Register Clone Fields
      * Register fields that will be used as clone-able fields
      *
      * @since 0.1.1
      *
      * @depends on ACF 5.5+
      * @link https://www.advancedcustomfields.com/resources/clone/
      *
      * @return void
      */
     public function register_clone_fields() {}

     /**
      * Register Field Groups
      *
      * @since 0.1.1
      *
      * @uses register_field_group()
      *
      * @return void
      */
     public function register_field_groups() {
        acf_add_local_field_group( array (
        	'key' => 'group_related_posts',
        	'title' => __( 'Related Posts', 'littlesis-core' ),
        	'fields' => array (
        		array (
        			'post_type' => array (
        				0 => 'post',
        			),
        			'taxonomy' => array (
        			),
        			'min' => '',
        			'max' => 3,
        			'filters' => array (
        				0 => 'search',
        				1 => 'taxonomy',
        			),
        			'elements' => array (
        				0 => 'featured_image',
        			),
        			'return_format' => 'object',
        			'key' => 'field_related_posts',
        			'label' => __( '', 'littlesis-core' ),
        			'name' => 'related_posts',
        			'type' => 'relationship',
        			'instructions' => '',
        			'required' => 0,
        			'conditional_logic' => 0,
        			'wrapper' => array (
        				'width' => '',
        				'class' => '',
        				'id' => '',
        			),
        		),
        	),
        	'location' => array (
        		array (
        			array (
        				'param' => 'post_type',
        				'operator' => '==',
        				'value' => 'post',
        			),
        		),
        	),
        	'menu_order' => 0,
        	'position' => 'normal',
        	'style' => 'default',
        	'label_placement' => 'top',
        	'instruction_placement' => 'label',
        	'hide_on_screen' => '',
        	'active' => 1,
        	'description' => '',
        ));

     }

     /**
      * Register Settings Fields
      *
      * @since 0.1.1
      *
      * @uses acf_add_local_field_group()
      *
      * @link https://www.advancedcustomfields.com/resources/options-page/
      *
      * @return void
      */
     public function register_settings_fields() {}

     /**
      * Get Page ID
      *
      * @since 0.1.1
      *
      * @uses get_page_by_path()
      *
      * @param  string $slug
      * @return int $page->ID
      */
     public function get_page_id( $slug ) {
         $page = get_page_by_path( $slug );
       	if ( $page ) {
       		return (int) $page->ID;
       	} else {
       		return null;
        }
     }
 }

 new LittleSis_Core_Custom_Fields();
