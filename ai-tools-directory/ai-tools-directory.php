<?php
/**
 * Plugin Name: AI Tools Directory
 * Description: SEO-optimized AI tools directory with filters, GEO pages, and monetization-ready features.
 * Version: 1.0.0
 * Author: AI Tools Directory
 * Text Domain: ai-tools-directory
 * Domain Path: /languages
 * Requires at least: 6.2
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AI_TOOLS_DIRECTORY_VERSION', '1.0.0' );
define( 'AI_TOOLS_DIRECTORY_PATH', plugin_dir_path( __FILE__ ) );
define( 'AI_TOOLS_DIRECTORY_URL', plugin_dir_url( __FILE__ ) );

require_once AI_TOOLS_DIRECTORY_PATH . 'includes/class-plugin.php';

function ai_tools_directory_init() {
	return AI_Tools_Directory\Plugin::get_instance();
}

add_action( 'plugins_loaded', 'ai_tools_directory_init' );
