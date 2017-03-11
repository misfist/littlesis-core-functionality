<?php
/**
 * LittleSis Core Template Loader
 *
 * @package    LittleSis_Core
 * @subpackage LittleSis_Core\Includes
 * @since      0.1.2
 * @license    GPL-2.0+
 */

if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
  require LITTLESIS_CORE_DIR . '/includes/libs/gamajo/template-loader/class-gamajo-template-loader.php';
}

/**
 * Custom Template Loader
 * Enables
 */
class LittleSis_Template_Loader extends Gamajo_Template_Loader {

  /**
   * Prefix for filter names.
   *
   * @since 0.1.2
   *
   * @var string
   */
  protected $filter_prefix = 'littlesis_core';

  /**
   * Directory name where custom templates for this plugin should be found in the theme.
   *
   * For example: 'your-plugin-templates'.
   *
   * @since 0.1.2
   *
   * @var string
   */
  protected $theme_template_directory = 'related-posts';

  /**
   * Reference to the root directory path of this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * e.g. YOUR_PLUGIN_TEMPLATE or plugin_dir_path( dirname( __FILE__ ) ); etc.
   *
   * @since 0.1.2
   *
   * @var string
   */
  protected $plugin_directory = LITTLESIS_CORE_DIR;

  /**
   * Directory name where templates are found in this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * e.g. 'templates' or 'includes/templates', etc.
   *
   * @since 1.1.0
   *
   * @var string
   */
  protected $plugin_template_directory = 'templates';

}
