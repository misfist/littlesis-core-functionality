<?php
/**
 * LittleSis Core Customizations
 *
 * @package    LittleSis_Core
 * @subpackage LittleSis_Core\Includes
 * @since      0.1.2
 * @license    GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class LittleSis_Core_Customization {

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   0.1.0
	 */
	public $_version;

	public function __construct( $version ) {

		/**
		 * Allow Shortcodes in Series Description
		 */
		add_filter( 'series_description', 'do_shortcode' );


		add_action( 'init', array( $this, 'add_shortcodes' ) );

		/**
		 * 
		 */
		add_action( 'init', function() {
			wp_embed_register_handler( 
				'oligarcher', 
				'#https:\/\/littlesis\.org/maps\/([a-z0-9_-]+)\/embedded\/v2$#i', 
				array( $this, 'embed_register_handler' ) );

		} );

	}

	/**
	 * Add Shortcodes
	 *
	 * @since 0.1.2
	 *
	 * @access public
	 *
	 * @uses add_shortcode()
	 *
	 * @return void
	 */
	public function add_shortcodes() {
		add_shortcode( 'embed-iframe', array( $this, 'iframe_embed_shortcode' ) );
	}

	/**
	 * Register oEmbed
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_embed_register_handler/
	 * 
	 * @since 0.1.5
	 *
	 * @param string $matches
	 * @param array $attr
	 * @param $url
	 * @param $rawattr
	 * @return string
	 */
	public function embed_register_handler(  $matches, $attr, $url, $rawattr ) {
		$embed = sprintf( 
			'<iframe src="https://littlesis.org/maps/%1$s/embedded/v2" data-src="https://littlesis.org/maps/%1$s/embedded/v2" height="600" width="100&#37;" scrolling="no"></iframe>',
			esc_attr( $matches[1] ),
			esc_attr( $matches[1] )
		);

		return apply_filters( 'embed_oligarcher', $embed, $matches, $attr, $url, $rawattr );
	}

	/**
	 * Embed iframe shortcode
	 * Adds shortcode that outputs iframe markup
	 *
	 * `[embed-iframe embed_url="https://url.org/embedded"]`
	 *
	 * @since 0.1.2
	 *
	 * @access public
	 *
	 * @param  array $atts
	 * @param string $content
	 *
	 * @return string $html
	 */
	public function iframe_embed_shortcode( $atts, $content = null, $shortcode_tag ) {
		extract( shortcode_atts( array(
			'embed_url'      => ''
		), $atts, $shortcode_tag ));

		$html = sprintf(  '<iframe src="%s" data-src="%s" width="100&#37;" height="600" scrolling="no"></iframe>',
			esc_url( $embed_url ),
			esc_url( $embed_url )
		);

		/* Enable modifying the output outside of this plugin */
		return apply_filters( 'littlesis_embed_iframe_ouput', $html, $embed_url );
	}

}
