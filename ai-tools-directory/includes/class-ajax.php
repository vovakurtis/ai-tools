<?php
namespace AI_Tools_Directory;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ajax {
	public static function register(): void {
		add_action( 'wp_ajax_ai_tools_directory_filter', array( __CLASS__, 'handle_filters' ) );
		add_action( 'wp_ajax_nopriv_ai_tools_directory_filter', array( __CLASS__, 'handle_filters' ) );
	}

	public static function handle_filters(): void {
		check_ajax_referer( 'ai_tools_directory_filters', 'nonce' );

		$query = self::get_filtered_query();

		ob_start();
		Template_Loader::render( 'parts/tool-grid.php', array( 'query' => $query ) );
		$markup = ob_get_clean();

		wp_send_json_success(
			array(
				'markup' => $markup,
			)
		);
	}

	public static function get_filtered_query(): WP_Query {
		$settings = Admin::get_settings();
		$sort = isset( $_GET['sort'] ) ? sanitize_text_field( wp_unslash( $_GET['sort'] ) ) : $settings['default_sort'];

		$tax_query = array( 'relation' => 'AND' );
		$taxonomies = array( 'ai_category', 'ai_use_case', 'ai_pricing', 'ai_platform', 'ai_geo', 'ai_industry' );
		foreach ( $taxonomies as $taxonomy ) {
			if ( empty( $_GET[ $taxonomy ] ) ) {
				continue;
			}
			$tax_query[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => array_map( 'sanitize_text_field', (array) wp_unslash( $_GET[ $taxonomy ] ) ),
			);
		}

		$args = array(
			'post_type'      => 'ai_tool',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'tax_query'      => count( $tax_query ) > 1 ? $tax_query : array(),
		);

		switch ( $sort ) {
			case 'popular':
				$args['meta_key'] = 'ai_tool_popularity';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;
			case 'az':
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
				break;
			case 'featured':
				$args['meta_query'] = array(
					array(
						'key'   => 'ai_tool_featured',
						'value' => '1',
					),
				);
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
				break;
			case 'newest':
			default:
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
				break;
		}

		return new WP_Query( $args );
	}
}
