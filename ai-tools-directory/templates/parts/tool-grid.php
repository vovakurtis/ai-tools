<?php
/**
 * Tool grid results.
 *
 * @var WP_Query $query
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="ai-tools-directory__grid">
	<?php if ( $query->have_posts() ) : ?>
		<?php while ( $query->have_posts() ) : ?>
			<?php $query->the_post(); ?>
			<?php AI_Tools_Directory\Template_Loader::render( 'parts/tool-card.php' ); ?>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No tools matched your filters.', 'ai-tools-directory' ); ?></p>
	<?php endif; ?>
</div>
