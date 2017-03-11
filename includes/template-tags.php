<?php
/**
 * LittleSis Core Template Tags
 *
 * @package    LittleSis_Core
 * @subpackage LittleSis_Core\Includes
 * @since      0.1.2
 * @license    GPL-2.0+
 */

/**
 * Display Image with Markup
 *
 * @since 0.1.2
 *
 * @param  int $post_id
 * @param  string  $size
 * @return void
 */
function littlesis_core_the_post_thumbnail( $post_id = null, $size = 'thumbnail' ) {
  $post_id = ( $post_id ) ? (int) $post_id : get_the_ID();

  $image_id = get_post_thumbnail_id( $post_id );
  $caption = get_post_field( 'post_excerpt', $image_id );
  $image = sprintf(' <figure class="featured-image size-%s %s"><a href="%s" rel="bookmark">%s</a></figure>',
    $size,
    ( has_post_thumbnail( $post_id ) ) ? esc_attr( ' has-thumbnail' ) : esc_attr( ' no-thumbnail' ),
    get_permalink(  $post_id ),
    get_the_post_thumbnail( $post_id, $size )
  );

  echo $image;
}

/**
 * Display Related Posts
 *
 * @since 0.1.2
 *
 * @uses littlesis_core_get_combined_related_posts()
 * @uses LittleSis_Template_Loader class
 *
 * @param int  $post_id
 * @param int $posts_per_page
 * @return void
 */
function littlesis_core_related_posts( $post_id = null, $posts_per_page = 3 ) {
  $post_id = ( $post_id ) ? (int) $post_id : get_the_ID();

  $related_posts = LittleSis_Core_Related_Posts::get_combined_related_posts( $post_id, $posts_per_page );

  $related_posts_template = new LittleSis_Template_Loader;

  if( !empty( $related_posts ) || !is_wp_error( $related_posts ) ) {

    $related_posts_template->set_template_data( $related_posts );
    $related_posts_template->get_template_part( 'related-posts' );

  }

  return;

}
