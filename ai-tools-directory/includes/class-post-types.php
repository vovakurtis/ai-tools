<?php
namespace AI_Tools_Directory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Types {
	public static function register(): void {
		add_action( 'init', array( __CLASS__, 'register_ai_tool' ) );
	}

	public static function register_ai_tool(): void {
		$labels = array(
			'name'               => __( 'AI Tools', 'ai-tools-directory' ),
			'singular_name'      => __( 'AI Tool', 'ai-tools-directory' ),
			'add_new'            => __( 'Add New', 'ai-tools-directory' ),
			'add_new_item'       => __( 'Add New AI Tool', 'ai-tools-directory' ),
			'edit_item'          => __( 'Edit AI Tool', 'ai-tools-directory' ),
			'new_item'           => __( 'New AI Tool', 'ai-tools-directory' ),
			'view_item'          => __( 'View AI Tool', 'ai-tools-directory' ),
			'view_items'         => __( 'View AI Tools', 'ai-tools-directory' ),
			'not_found'          => __( 'No AI tools found.', 'ai-tools-directory' ),
			'not_found_in_trash' => __( 'No AI tools found in Trash.', 'ai-tools-directory' ),
			'menu_name'          => __( 'AI Tools', 'ai-tools-directory' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'has_archive'        => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-grid-view',
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author' ),
			'rewrite'            => array( 'slug' => 'ai-tools' ),
			'show_in_nav_menus'  => true,
			'menu_position'      => 20,
		);

		register_post_type( 'ai_tool', $args );
	}
}
