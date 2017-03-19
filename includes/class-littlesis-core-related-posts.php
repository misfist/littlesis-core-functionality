<?php
/**
 * LittleSis Core Related Posts
 *
 * @package    LittleSis_Core
 * @subpackage LittleSis_Core\Includes
 * @since      0.1.2
 * @license    GPL-2.0+
 */

 /**
  * Register Custom Fields
  *
  * @since 0.1.2
  *
  */
 class LittleSis_Core_Related_Posts {

     private $slug = '';

     /**
      * Initialize all the things
      *
      * @since  0.1.2
      *
      */
     function __construct() {

         if( function_exists( 'acf_add_local_field_group' ) ) {
           $this->register_clone_fields();
           $this->register_field_groups();
         }

     }

     /**
      * Register Clone Fields
      * Register fields that will be used as clone-able fields
      *
      * @since 0.1.2
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
      * @since 0.1.2
      *
      * @access public
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
      * Get Manually Selected Related Posts
      *
      * @since 0.1.2
      *
      * @access public static
      *
      * @param $post_id
      * @return array $related_posts
      */
     public static function get_related_posts( $post_id = null ) {
       $post_id = ( $post_id ) ? (int) $post_id : get_the_ID();

       $posts = get_post_meta( $post_id, 'related_posts', true );

       if( empty( $posts ) || is_wp_error( $posts ) ) {
         return new WP_Error( 'no_posts', __( 'No posts were found for this category', 'littlesis-core' ) );
       }

       $related_posts = array_map( function( $post ) {
         return intval( $post );
       }, $posts );

       return $related_posts;
     }

     /**
      * Get Automatic Related Posts
      * Build list of related posts based on series or category
      *
      * @access public static
      *
      * @since 0.1.2
      *
      * @uses get_transient()
      * @uses set_transient()
      * @link https://codex.wordpress.org/Transients_API
      *
      * @param int $post_id
      * @param int $posts_per_page
      * @return array $related_postsl
      */
     public static function get_auto_related_posts( $post_id = null, $posts_per_page = 3 ) {
       $post_id = ( $post_id ) ? (int) $post_id : get_the_ID();

       $transient = 'related_posts_' . $post_id;

       $related_posts = get_transient( $transient );

       /* Use Cached Posts */
       if( !empty( $related_posts ) && !is_wp_error( $related_posts ) ) {

         return $related_posts;

       } else {

         $posts_per_page = intval( $posts_per_page );

         $args = array(
           'exclude'         => $post_id,
           'posts_per_page'  => $posts_per_page
         );


         /* Create taxonomy query */

         // If post has series and category terms
         if( has_term( '', 'series', $post_id ) &&  has_term( '', 'category', $post_id ) ) {

           $series = wp_get_post_terms( $post_id, 'series', array( 'fields' => 'ids' ) );
           $categories = wp_get_post_terms( $post_id, 'category', array( 'fields' => 'ids' ) );

           $tax_query = array(
             'relation' => 'OR',
             array(
               'taxonomy'          => 'series',
               'terms'             => $series,
               'field'             => 'term_id'
             ),
             array(
               'taxonomy'          => 'category',
               'terms'             => $categories,
               'field'             => 'term_id'
             )
           );
         // If post has only series terms
         } elseif( has_term( '', 'series', $post_id ) ) {

           $series = wp_get_post_terms( $post_id, 'series', array( 'fields' => 'ids' ) );
           $tax_query[] =  array(
              'taxonomy'          => 'series',
              'terms'             => $series,
              'field'             => 'term_id'
            );

         // If post has only category terms
         } elseif( has_term( '', 'category', $post_id ) ) {

           $categories = wp_get_post_terms( $post_id, 'category', array( 'fields' => 'ids' ) );
           $tax_query[] =  array(
             'taxonomy'          => 'category',
             'terms'             => $categories,
             'field'             => 'term_id'
           );

         }

         if( isset( $tax_query ) ) {
           $args['tax_query'] = $tax_query;
         }

         // If has series term
         if( has_term( '', 'series', $post_id ) ) {

           $series = wp_get_post_terms( $post_id, 'series', array( 'fields' => 'ids' ) );

           $series_args = array(
             'taxonomy'          => 'series',
             'terms'             => $series,
             'field'             => 'term_id'
           );

         }

         // If post is not part of series OR there are fewer than 4 (don't count current post), get the categories
         if( !isset( $series ) || ( ( !empty( $series ) && !is_wp_error( $series ) ) && ( count( $series ) < ( $posts_per_page + 1 ) ) ) ) {

           if( has_term( '', 'category', $post_id ) ) {
             $categories = wp_get_post_terms( $post_id, 'category', array( 'fields' => 'ids' ) );

             $series_args = array(
               'taxonomy'          => 'category',
               'terms'             => $categories,
               'field'             => 'term_id'
             );

           }

         }

         // Get the posts
         $posts = get_posts( $args );

         if( empty( $posts ) || is_wp_error( $posts ) ) {
           return new WP_Error( 'no_posts', __( 'No posts were found for this category', 'littlesis-core' ) );
         }

         $related_posts = array_map( function( $post ) {
           return intval( $post->ID );
         }, $posts);

         set_transient( $transient, $related_posts, DAY_IN_SECONDS );

         return $related_posts;

       }

     }

     /**
      * Get Combined Related Posts
      *
      * @since 0.1.2
      *
      * @access public static
      *
      * @uses littlesis_core_get_related_posts()
      * @uses littlesis_core_get_auto_related_posts()
      *
      * @param int $post_id
      * @param int $posts_per_page
      * @return array $related_posts
      */
     public static function get_combined_related_posts( $post_id = null, $posts_per_page = 3 ) {
       $post_id = ( $post_id ) ? (int) $post_id : get_the_ID();

       $posts_per_page = intval( $posts_per_page );

       $related_posts = self::get_related_posts( $post_id );

       /* There are no manually selected related posts */
       if( empty( $related_posts ) || is_wp_error( $related_posts ) ) {

         $related_posts = self::get_auto_related_posts( $post_id, $posts_per_page );

       } elseif ( count( $related_posts ) < $posts_per_page ) { /* If there are fewer than 3 manually added related posts, add auto posts */

         $auto_posts = self::get_auto_related_posts( $post_id );
         $merged = array_merge( $related_posts, $auto_posts );

         $related_posts = array_slice( array_unique( $merged ), 0, $posts_per_page );

       }

       return  $related_posts;
     }

     /**
      * Get Page ID
      *
      * @since 0.1.1
      *
      * @access public
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

 new LittleSis_Core_Related_Posts();
