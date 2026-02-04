<?php
/**
 * Archive template for AI Tools.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main class="ai-tools-archive">
	<header class="ai-tools-archive__header">
		<h1><?php esc_html_e( 'AI Tools Directory', 'ai-tools-directory' ); ?></h1>
		<p><?php esc_html_e( 'Discover AI tools by category, use case, pricing, and geography.', 'ai-tools-directory' ); ?></p>
	</header>

	<?php echo do_shortcode( '[ai_tools_directory]' ); ?>
</main>
<?php
get_footer();
