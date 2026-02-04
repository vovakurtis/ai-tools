<?php
namespace AI_Tools_Directory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Schema {
	public static function register(): void {
		add_action( 'wp_head', array( __CLASS__, 'output_schema' ) );
	}

	public static function output_schema(): void {
		if ( ! is_singular( 'ai_tool' ) ) {
			return;
		}

		$post_id = get_queried_object_id();
		if ( ! $post_id ) {
			return;
		}

		$website = get_post_meta( $post_id, 'ai_tool_website_url', true );
		$pricing = wp_get_post_terms( $post_id, 'ai_pricing', array( 'fields' => 'names' ) );
		$categories = wp_get_post_terms( $post_id, 'ai_category', array( 'fields' => 'names' ) );
		$faq_raw = get_post_meta( $post_id, 'ai_tool_faq', true );
		$faq_items = self::parse_faq( $faq_raw );
		$rating = (float) get_post_meta( $post_id, 'ai_tool_rating', true );
		$review_summary = get_post_meta( $post_id, 'ai_tool_review_summary', true );
		$breadcrumbs = self::build_breadcrumbs( $post_id );

		$offers = array(
			'@type'         => 'Offer',
			'priceCurrency' => 'USD',
			'price'         => '0',
			'category'      => $pricing ? implode( ', ', $pricing ) : 'Free',
		);

		$schema = array(
			'@context'  => 'https://schema.org',
			'@type'     => 'SoftwareApplication',
			'name'      => get_the_title( $post_id ),
			'url'       => $website ?: get_permalink( $post_id ),
			'applicationCategory' => implode( ', ', $categories ),
			'offers'    => $offers,
		);

		$schemas = array( $schema );

		$schemas[] = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Product',
			'name'        => get_the_title( $post_id ),
			'description' => wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ),
			'url'         => get_permalink( $post_id ),
			'category'    => implode( ', ', $categories ),
			'offers'      => $offers,
		);

		if ( $rating > 0 && $review_summary ) {
			$schemas[] = array(
				'@context' => 'https://schema.org',
				'@type'    => 'Review',
				'itemReviewed' => array(
					'@type' => 'SoftwareApplication',
					'name'  => get_the_title( $post_id ),
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $rating,
					'bestRating'  => 5,
					'worstRating' => 1,
				),
				'author' => array(
					'@type' => 'Organization',
					'name'  => get_bloginfo( 'name' ),
				),
				'reviewBody' => $review_summary,
			);
		}

		if ( ! empty( $faq_items ) ) {
			$schemas[] = array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $faq_items,
			);
		}

		if ( ! empty( $breadcrumbs ) ) {
			$schemas[] = array(
				'@context'        => 'https://schema.org',
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $breadcrumbs,
			);
		}

		echo '<script type="application/ld+json">' . wp_json_encode( $schemas, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
	}

	private static function parse_faq( string $faq_raw ): array {
		if ( empty( $faq_raw ) ) {
			return array();
		}

		$lines = preg_split( '/\r\n|\r|\n/', $faq_raw );
		$items = array();
		$question = '';
		foreach ( $lines as $line ) {
			if ( 0 === stripos( trim( $line ), 'q:' ) ) {
				$question = trim( substr( $line, 2 ) );
				continue;
			}
			if ( 0 === stripos( trim( $line ), 'a:' ) && $question ) {
				$answer = trim( substr( $line, 2 ) );
				$items[] = array(
					'@type'          => 'Question',
					'name'           => $question,
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $answer,
					),
				);
				$question = '';
			}
		}

		return $items;
	}

	private static function build_breadcrumbs( int $post_id ): array {
		$breadcrumbs = array(
			array(
				'@type'    => 'ListItem',
				'position' => 1,
				'name'     => get_bloginfo( 'name' ),
				'item'     => home_url( '/' ),
			),
			array(
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => __( 'AI Tools', 'ai-tools-directory' ),
				'item'     => get_post_type_archive_link( 'ai_tool' ),
			),
			array(
				'@type'    => 'ListItem',
				'position' => 3,
				'name'     => get_the_title( $post_id ),
				'item'     => get_permalink( $post_id ),
			),
		);

		return $breadcrumbs;
	}
}
