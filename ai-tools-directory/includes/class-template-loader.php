<?php
namespace AI_Tools_Directory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Template_Loader {
	public static function register(): void {
		add_filter( 'template_include', array( __CLASS__, 'load_templates' ) );
	}

	public static function load_templates( string $template ): string {
		if ( is_singular( 'ai_tool' ) ) {
			return self::locate_template( 'single-ai_tool.php', $template );
		}

		if ( is_post_type_archive( 'ai_tool' ) ) {
			return self::locate_template( 'archive-ai_tool.php', $template );
		}

		return $template;
	}

	public static function locate_template( string $template_name, string $default ): string {
		$theme_template = locate_template( array( 'ai-tools-directory/' . $template_name, $template_name ) );
		if ( $theme_template ) {
			return $theme_template;
		}

		$plugin_template = AI_TOOLS_DIRECTORY_PATH . 'templates/' . $template_name;
		if ( file_exists( $plugin_template ) ) {
			return $plugin_template;
		}

		return $default;
	}

	public static function render( string $template_name, array $args = array() ): void {
		$path = AI_TOOLS_DIRECTORY_PATH . 'templates/' . $template_name;
		if ( ! file_exists( $path ) ) {
			return;
		}
		extract( $args, EXTR_SKIP );
		include $path;
	}
}
