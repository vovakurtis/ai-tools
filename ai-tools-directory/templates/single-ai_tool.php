<?php
/**
 * Single template for AI Tool.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$post_id = get_the_ID();
$website = get_post_meta( $post_id, 'ai_tool_website_url', true );
$short_description = get_post_meta( $post_id, 'ai_tool_short_description', true );
$full_description = get_post_meta( $post_id, 'ai_tool_full_description', true );
$pricing_notes = get_post_meta( $post_id, 'ai_tool_pricing_notes', true );
$pros = get_post_meta( $post_id, 'ai_tool_pros', true );
$cons = get_post_meta( $post_id, 'ai_tool_cons', true );
$alternatives = get_post_meta( $post_id, 'ai_tool_alternatives', true );
$faq = get_post_meta( $post_id, 'ai_tool_faq', true );
$categories = get_the_terms( $post_id, 'ai_category' );
$use_cases = get_the_terms( $post_id, 'ai_use_case' );
$pricing = get_the_terms( $post_id, 'ai_pricing' );
$platforms = get_the_terms( $post_id, 'ai_platform' );
$geo = get_the_terms( $post_id, 'ai_geo' );
$industries = get_the_terms( $post_id, 'ai_industry' );
$model_type = get_post_meta( $post_id, 'ai_tool_model_type', true );
$similar_tools = null;
if ( $categories ) {
	$category_ids = wp_list_pluck( $categories, 'term_id' );
	$similar_tools = new WP_Query(
		array(
			'post_type'      => 'ai_tool',
			'post_status'    => 'publish',
			'posts_per_page' => 4,
			'post__not_in'   => array( $post_id ),
			'tax_query'      => array(
				array(
					'taxonomy' => 'ai_category',
					'field'    => 'term_id',
					'terms'    => $category_ids,
				),
			),
		)
	);
}
?>
<main class="ai-tool-single">
	<article <?php post_class( 'ai-tool-single__content' ); ?>>
		<header class="ai-tool-single__header">
			<h1><?php the_title(); ?></h1>
			<?php if ( $short_description ) : ?>
				<p class="ai-tool-single__summary"><?php echo esc_html( $short_description ); ?></p>
			<?php endif; ?>
			<?php if ( $website ) : ?>
				<a class="ai-tool-single__cta" href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener">
					<?php esc_html_e( 'Visit Official Website', 'ai-tools-directory' ); ?>
				</a>
			<?php endif; ?>
		</header>

		<section class="ai-tool-single__meta">
			<?php if ( $model_type ) : ?>
				<div><strong><?php esc_html_e( 'Model Type:', 'ai-tools-directory' ); ?></strong> <?php echo esc_html( $model_type ); ?></div>
			<?php endif; ?>
			<?php if ( $pricing ) : ?>
				<div><strong><?php esc_html_e( 'Pricing:', 'ai-tools-directory' ); ?></strong> <?php echo esc_html( implode( ', ', wp_list_pluck( $pricing, 'name' ) ) ); ?></div>
			<?php endif; ?>
			<?php if ( $platforms ) : ?>
				<div><strong><?php esc_html_e( 'Platforms:', 'ai-tools-directory' ); ?></strong> <?php echo esc_html( implode( ', ', wp_list_pluck( $platforms, 'name' ) ) ); ?></div>
			<?php endif; ?>
			<?php if ( $geo ) : ?>
				<div><strong><?php esc_html_e( 'GEO Availability:', 'ai-tools-directory' ); ?></strong> <?php echo esc_html( implode( ', ', wp_list_pluck( $geo, 'name' ) ) ); ?></div>
			<?php endif; ?>
		</section>

		<section class="ai-tool-single__section">
			<h2><?php esc_html_e( 'Overview', 'ai-tools-directory' ); ?></h2>
			<?php if ( $full_description ) : ?>
				<p><?php echo esc_html( $full_description ); ?></p>
			<?php else : ?>
				<?php the_content(); ?>
			<?php endif; ?>
		</section>

		<?php if ( $use_cases ) : ?>
			<section class="ai-tool-single__section">
				<h2><?php esc_html_e( 'Use Cases', 'ai-tools-directory' ); ?></h2>
				<ul>
					<?php foreach ( $use_cases as $use_case ) : ?>
						<li><?php echo esc_html( $use_case->name ); ?></li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<?php if ( $industries ) : ?>
			<section class="ai-tool-single__section">
				<h2><?php esc_html_e( 'Industries', 'ai-tools-directory' ); ?></h2>
				<ul>
					<?php foreach ( $industries as $industry ) : ?>
						<li><?php echo esc_html( $industry->name ); ?></li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<?php if ( $pros || $cons ) : ?>
			<section class="ai-tool-single__section">
				<h2><?php esc_html_e( 'Pros & Cons', 'ai-tools-directory' ); ?></h2>
				<div class="ai-tool-single__columns">
					<?php if ( $pros ) : ?>
						<div>
							<h3><?php esc_html_e( 'Pros', 'ai-tools-directory' ); ?></h3>
							<ul>
								<?php foreach ( preg_split( '/\r\n|\r|\n/', $pros ) as $pro ) : ?>
									<li><?php echo esc_html( $pro ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
					<?php if ( $cons ) : ?>
						<div>
							<h3><?php esc_html_e( 'Cons', 'ai-tools-directory' ); ?></h3>
							<ul>
								<?php foreach ( preg_split( '/\r\n|\r|\n/', $cons ) as $con ) : ?>
									<li><?php echo esc_html( $con ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $pricing_notes ) : ?>
			<section class="ai-tool-single__section">
				<h2><?php esc_html_e( 'Pricing', 'ai-tools-directory' ); ?></h2>
				<p><?php echo esc_html( $pricing_notes ); ?></p>
			</section>
		<?php endif; ?>

		<?php if ( $alternatives ) : ?>
			<section class="ai-tool-single__section">
				<h2><?php esc_html_e( 'Alternatives', 'ai-tools-directory' ); ?></h2>
				<p><?php echo esc_html( $alternatives ); ?></p>
			</section>
		<?php endif; ?>

		<?php if ( $similar_tools instanceof WP_Query && $similar_tools->have_posts() ) : ?>
			<section class="ai-tool-single__section">
				<h2><?php esc_html_e( 'Similar Tools', 'ai-tools-directory' ); ?></h2>
				<div class="ai-tools-directory__grid">
					<?php while ( $similar_tools->have_posts() ) : ?>
						<?php $similar_tools->the_post(); ?>
						<?php AI_Tools_Directory\Template_Loader::render( 'parts/tool-card.php' ); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $faq ) : ?>
			<section class="ai-tool-single__section">
				<h2><?php esc_html_e( 'FAQ', 'ai-tools-directory' ); ?></h2>
				<?php foreach ( preg_split( '/\r\n\r\n|\r\r|\n\n/', $faq ) as $faq_block ) : ?>
					<p><?php echo esc_html( $faq_block ); ?></p>
				<?php endforeach; ?>
			</section>
		<?php endif; ?>

		<footer class="ai-tool-single__footer">
			<?php if ( $categories ) : ?>
				<div>
					<strong><?php esc_html_e( 'Categories:', 'ai-tools-directory' ); ?></strong>
					<?php echo esc_html( implode( ', ', wp_list_pluck( $categories, 'name' ) ) ); ?>
				</div>
			<?php endif; ?>
		</footer>
	</article>
</main>
<?php
get_footer();
