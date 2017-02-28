<?php
/**
 * Plugin Name:     LittleSis Core Functionality
 * Plugin URI:      https://github.com/misfist/littlesis-core-functionality
 * Description:     Core functions for the LittleSis project.
 * Author:          Pea
 * Author URI:      https://github.com/misfist
 * Text Domain:     littlesis-core
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Littlesis_Core_Functionality
 */

 if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Plugin Directory
 *
 * @since 0.1.0
 */
define( 'LITTLESIS_CORE_DIR', dirname( __FILE__ ) );
define( 'LITTLESIS_CORE_DIR_URL', plugin_dir_url( __FILE__ ) );

require_once( 'includes/class-littlesis-core.php' );
require_once( 'admin/class-littlesis-core-admin.php' );

/**
 * Returns the main instance of LittleSis_Core to prevent the need to use globals.
 *
 * @since  0.1.0
 * @return object LittleSis
 */
function LittleSis_Core() {
  $instance = LittleSis_Core::instance( __FILE__, '0.1.0' );

 	return $instance;
}

LittleSis_Core();
