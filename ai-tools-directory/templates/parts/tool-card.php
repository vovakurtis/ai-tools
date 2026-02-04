<?php
/**
 * Tool card.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_the_ID();
$short_description = get_post_meta( $post_id, 'ai_tool_short_description', true );
$pricing = get_the_terms( $post_id, 'ai_pricing' );
$categories = get_the_terms( $post_id, 'ai_category' );
$featured = get_post_meta( $post_id, 'ai_tool_featured', true );
$website = get_post_meta( $post_id, 'ai_tool_website_url', true );
?>
<article class="ai-tool-card <?php echo $featured ? 'is-featured' : ''; ?>">
	<header>
		<h3 class="ai-tool-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( $pricing ) : ?>
			<span class="ai-tool-card__badge"><?php echo esc_html( $pricing[0]->name ); ?></span>
		<?php endif; ?>
	</header>

	<?php if ( $categories ) : ?>
		<div class="ai-tool-card__tags">
			<?php foreach ( $categories as $category ) : ?>
				<span><?php echo esc_html( $category->name ); ?></span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $short_description ) : ?>
		<p><?php echo esc_html( $short_description ); ?></p>
	<?php endif; ?>

	<div class="ai-tool-card__actions">
		<a class="ai-tool-card__cta" href="<?php echo esc_url( $website ? $website : get_permalink() ); ?>" target="_blank" rel="noopener">
			<?php esc_html_e( 'Try Tool', 'ai-tools-directory' ); ?>
		</a>
		<a class="ai-tool-card__details" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'View Details', 'ai-tools-directory' ); ?>
		</a>
		<button class="ai-tool-card__save" type="button" data-ai-tool-save data-tool-id="<?php echo esc_attr( (string) $post_id ); ?>">
			<?php esc_html_e( 'Save', 'ai-tools-directory' ); ?>
		</button>
	</div>
</article>
