<?php
/**
 * LittleSis Core Admin
 *
 * @package    LittleSis
 * @subpackage LittleSis\Admin
 * @since      0.1.0
 * @license    GPL-2.0+
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link 		https://codex.wordpress.org/Settings_API
 *
 * @package    LittleSis
 * @subpackage LittleSis\Admin
 * @author     Pea <pea@misfist.com>
 */
class LittleSis_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The Setting Name
	 * Used for page name and setting name
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $setting_name    The setting that will be registered.
	 */
	private $setting_name = '';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->add_options_page();
		$this->add_fields();

		if( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		add_filter( 'mce_buttons_2', array( $this, 'customize_wysiwyg_buttons' ) );

	}

	/**
	 * Add an Options Page
	 *
	 * @since 0.1.0
	 *
	 * @uses acf_add_options_sub_page()
	 */
	public function add_options_page() {

		if( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_sub_page( array(
				'page_title' 	=> __( 'Filter Categories' ),
				'menu_title'	=> __( 'Filter Categories' ),
				'parent_slug'	=> 'edit.php',
			));
		}
	}

	/**
	 * Add Fields
	 *
	 * @since 0.1.0
	 *
	 * @uses acf_add_local_field_group()
	 */
	public function add_fields() {

		if( function_exists( 'acf_add_local_field_group' ) ) {
			acf_add_local_field_group( array(
				'key' => 'group_58b4c88d6f958',
				'title' => __( 'Filter Categories', 'littlesis-core' ),
				'fields' => array (
					array (
						'taxonomy' => 'category',
						'field_type' => 'multi_select',
						'multiple' => 0,
						'allow_null' => 0,
						'return_format' => 'id',
						'add_term' => 0,
						'load_terms' => 0,
						'save_terms' => 0,
						'key' => 'field_58b4c89c2be7d',
						'label' => __( 'Category Terms', 'littlesis-core' ),
						'name' => 'littlesis_category_terms',
						'type' => 'taxonomy',
						'instructions' => __( 'Select the categories to use for the homepage post filter.', 'littlesis-core' ),
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
							'param' => 'options_page',
							'operator' => '==',
							'value' => 'acf-options-filter-categories',
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

	}

	/**
	 * Get Settings
	 * Get the name of the settings
	 *
	 * @since    0.1.0
	 */
	public function get_setting_name() {
		return $this->setting_name;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LittleSis_Core as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LittleSis_Core will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, LITTLESIS_CORE_DIR_URL . 'assets/css/admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LittleSis_Core as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LittleSis_Core will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, LITTLESIS_CORE_DIR_URL . 'assets/js/admin.js', array( 'jquery-chosen' ), $this->version, false );
	}

	/**
	 * Create Quicktags
	 *
	 * @since 1.0.3
	 *
	 * @uses admin_print_footer_script
	 * @link https://codex.wordpress.org/Quicktags_API
	 *
	 * @return void
	 */
	public function quicktags() {
		if ( wp_script_is( 'quicktags' ) ) { ?>


		<?php
		}
	}

	/**
	 * Custom WYSIWYG Editor Buttons
	 *
	 * @since 1.0.3
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/mce_buttons,_mce_buttons_2,_mce_buttons_3,_mce_buttons_4
	 *
	 * @param array $buttons
	 * @return array $buttons
	 */
	public function customize_wysiwyg_buttons( $buttons ) {
		$remove = array(
			'formatselect',
			'forecolor',
		 	'strikethrough'
		);

		return array_diff( $buttons, $remove );
	}

	/**
	 * Sanitize Input
	 *
	 * @since    0.1.0
	 *
	 * @param string $string
	 * @return sanitized string $string
	 */
	public function sanitize_string( $string ) {
		return sanitize_text_field( $string );
	}

}
