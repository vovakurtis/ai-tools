<?php
namespace AI_Tools_Directory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {
	private static ?Plugin $instance = null;

	public static function get_instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->includes();	
		$this->hooks();
	}

	private function includes(): void {
		require_once AI_TOOLS_DIRECTORY_PATH . 'includes/class-post-types.php';
		require_once AI_TOOLS_DIRECTORY_PATH . 'includes/class-taxonomies.php';
		require_once AI_TOOLS_DIRECTORY_PATH . 'includes/class-meta-boxes.php';
		require_once AI_TOOLS_DIRECTORY_PATH . 'includes/class-admin.php';
		require_once AI_TOOLS_DIRECTORY_PATH . 'includes/class-frontend.php';
		require_once AI_TOOLS_DIRECTORY_PATH . 'includes/class-ajax.php';
		require_once AI_TOOLS_DIRECTORY_PATH . 'includes/class-schema.php';
		require_once AI_TOOLS_DIRECTORY_PATH . 'includes/class-template-loader.php';
	}

	private function hooks(): void {
		Post_Types::register();
		Taxonomies::register();
		Meta_Boxes::register();
		Admin::register();
		Frontend::register();
		Ajax::register();
		Schema::register();
		Template_Loader::register();
	}
}
