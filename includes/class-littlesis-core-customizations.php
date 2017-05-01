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
		 * Commented Out
		 * This filter is causing a fatal memory exhaustion error
		 * @since 0.1.4
		 */
		//add_filter( 'list_terms_exclusions', array( $this, 'list_terms_exclusions' ), 10, 2 );

		/**
		 * Allow Shortcodes in Series Description
		 */
		add_filter( 'series_description', 'do_shortcode' );


		add_action( 'init', array( $this, 'add_shortcodes' ) );

		$this->register_shortcode_ui();

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
	 * Embed iframe shortcode
	 * Adds shortcode that outputs iframe markup
	 *
	 * `[embed-iframe embed_url="https://url.org/embedded" link_url="embed_url="https://url.org" link_text="View Map on LittleSis"]`
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
			'embed_url'      => '',
			'link_url'       => '',
			'link_text'      => __( 'View This Map on LittleSis', 'littlesis-core' )
		), $atts, $shortcode_tag ));

		$html = sprintf(  '<div class="iframe-container"><iframe src="%s" data-src="%s" height="600" scrolling="no"></iframe></div><p class="source-link"><a href="%s" class="btn btn-primary" target="_blank">%s</a></p>',
			esc_url( $embed_url ),
			esc_url( $embed_url ),
			esc_url( $link_url ),
			esc_attr( $link_text )
		);

		/* Enable modifying the output outside of this plugin */
		return apply_filters( 'littlesis_embed_iframe_ouput', $html, $embed_url, $link_url, $link_text );
	}

	/**
	 * Add Register Shortcode UI Action
	 *
	 * @since 0.1.2
	 *
	 * @return void
	 */
	public function register_shortcode_ui() {
		add_action( 'register_shortcode_ui', array( $this, 'iframe_embed_shortcode_ui' ) );
	}

	/**
	 * Create Shortcode UI
	 * Adds UI interface for `[embed-iframe]` shortcode
	 *
	 * @since 0.1.2
	 *
	 * @access public
	 *
	 * @uses shortcode_ui_register_for_shortcode()
	 *
	 * @return void
	 */
	public function iframe_embed_shortcode_ui() {

		$fields = array(
			array(
				'label'  => esc_html__( 'Embed URL', 'littlesis-core' ),
				'attr'   => 'embed_url',
				'type'   => 'url',
				'encode' => false,
				'meta'   => array(
					'placeholder' => esc_html__( 'https://url.org/embedded', 'littlesis-core' ),
					'data-test'   => 1,
				),
			),
			array(
				'label'  => esc_html__( 'Link URL', 'littlesis-core' ),
				'attr'   => 'link_url',
				'type'   => 'url',
				'encode' => false,
				'meta'   => array(
					'placeholder' => esc_html__( 'https://url.org', 'littlesis-core' ),
					'data-test'   => 1,
				),
			),
			array(
				'label'  => esc_html__( 'Link Text', 'littlesis-core' ),
				'attr'   => 'link_text',
				'type'   => 'text',
				'encode' => false,
				'meta'   => array(
					'placeholder' => esc_html__( 'View Map on LittleSis', 'littlesis-core' ),
					'data-test'   => 1,
				),
			),
		);

		$args = array(
			'label' 					=> esc_html__( 'Embed iframe', 'littlesis-core' ),
			'listItemImage' 	=> 'dashicons-media-code',
			'post_type'				=> array( 'post' ),
			'attrs' 					=> $fields,
		);

		/* Enable modifying arguments outside of this plugin */
	 	shortcode_ui_register_for_shortcode( 'embed-iframe', $args );
	 }

	/**
	 * Remove Uncategorized for Category Terms Lists
	 *
	 * @since 0.1.2
	 *
	 * @uses list_terms_exclusions filter hook
	 * @link https://developer.wordpress.org/reference/hooks/list_terms_exclusions/
	 *
	 * @param  string $exclusion
	 * @param  array $args
	 * @param  array $taxonomies
	 * @return string $exclusions
	 */
	function list_terms_exclusions( $exclusions, $args ) {
	  $children = implode( ',', get_term_children( 1, 'category' ) );
	  $children = ( empty( $children ) ? '' : ",$children" );
	  return $exclusions . " AND (t.term_id NOT IN (1{$children}))";
	}

}
