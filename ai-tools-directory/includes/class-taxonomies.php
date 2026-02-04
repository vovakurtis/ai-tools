<?php
namespace AI_Tools_Directory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Taxonomies {
	public static function register(): void {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ) );
	}

	public static function register_taxonomies(): void {
		self::register_taxonomy(
			'ai_category',
			__( 'AI Categories', 'ai-tools-directory' ),
			__( 'AI Category', 'ai-tools-directory' ),
			'ai-category'
		);

		self::register_taxonomy(
			'ai_use_case',
			__( 'Use Cases', 'ai-tools-directory' ),
			__( 'Use Case', 'ai-tools-directory' ),
			'ai-use-case'
		);

		self::register_taxonomy(
			'ai_pricing',
			__( 'Pricing Models', 'ai-tools-directory' ),
			__( 'Pricing Model', 'ai-tools-directory' ),
			'ai-pricing'
		);

		self::register_taxonomy(
			'ai_platform',
			__( 'Platforms', 'ai-tools-directory' ),
			__( 'Platform', 'ai-tools-directory' ),
			'ai-platform'
		);

		self::register_taxonomy(
			'ai_geo',
			__( 'GEO Availability', 'ai-tools-directory' ),
			__( 'GEO Availability', 'ai-tools-directory' ),
			'ai-geo'
		);

		self::register_taxonomy(
			'ai_industry',
			__( 'Industries', 'ai-tools-directory' ),
			__( 'Industry', 'ai-tools-directory' ),
			'ai-industry'
		);
	}

	private static function register_taxonomy( string $taxonomy, string $plural, string $singular, string $slug ): void {
		$labels = array(
			'name'              => $plural,
			'singular_name'     => $singular,
			'add_new_item'      => sprintf( __( 'Add New %s', 'ai-tools-directory' ), $singular ),
			'new_item_name'     => sprintf( __( 'New %s Name', 'ai-tools-directory' ), $singular ),
			'edit_item'         => sprintf( __( 'Edit %s', 'ai-tools-directory' ), $singular ),
			'update_item'       => sprintf( __( 'Update %s', 'ai-tools-directory' ), $singular ),
			'view_item'         => sprintf( __( 'View %s', 'ai-tools-directory' ), $singular ),
			'parent_item'       => sprintf( __( 'Parent %s', 'ai-tools-directory' ), $singular ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'ai-tools-directory' ), $singular ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => $slug ),
			'show_in_nav_menus' => true,
		);

		register_taxonomy( $taxonomy, array( 'ai_tool' ), $args );
	}
}
