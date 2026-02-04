<?php
namespace AI_Tools_Directory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Frontend {
	public static function register(): void {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_shortcode( 'ai_tools_directory', array( __CLASS__, 'render_directory_shortcode' ) );
		add_action( 'wp', array( __CLASS__, 'track_popularity' ) );
	}

	public static function enqueue_assets(): void {
		if ( ! is_post_type_archive( 'ai_tool' ) && ! has_shortcode( get_post_field( 'post_content', get_queried_object_id() ), 'ai_tools_directory' ) ) {
			return;
		}

		wp_enqueue_style(
			'ai-tools-directory',
			AI_TOOLS_DIRECTORY_URL . 'assets/css/ai-tools-directory.css',
			array(),
			AI_TOOLS_DIRECTORY_VERSION
		);

		wp_enqueue_script(
			'ai-tools-directory',
			AI_TOOLS_DIRECTORY_URL . 'assets/js/ai-tools-directory.js',
			array( 'jquery' ),
			AI_TOOLS_DIRECTORY_VERSION,
			true
		);

		wp_localize_script(
			'ai-tools-directory',
			'aiToolsDirectory',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ai_tools_directory_filters' ),
			)
		);
	}

	public static function render_directory_shortcode(): string {
		ob_start();
		$filters = array(
			'ai_category' => get_terms( array( 'taxonomy' => 'ai_category', 'hide_empty' => true ) ),
			'ai_use_case' => get_terms( array( 'taxonomy' => 'ai_use_case', 'hide_empty' => true ) ),
			'ai_pricing'  => get_terms( array( 'taxonomy' => 'ai_pricing', 'hide_empty' => true ) ),
			'ai_platform' => get_terms( array( 'taxonomy' => 'ai_platform', 'hide_empty' => true ) ),
			'ai_geo'      => get_terms( array( 'taxonomy' => 'ai_geo', 'hide_empty' => true ) ),
			'ai_industry' => get_terms( array( 'taxonomy' => 'ai_industry', 'hide_empty' => true ) ),
		);
		?>
		<div class="ai-tools-directory" data-directory>
			<?php Template_Loader::render( 'parts/filter-bar.php', array( 'filters' => $filters ) ); ?>
			<div class="ai-tools-directory__results" data-directory-results>
				<?php Template_Loader::render( 'parts/tool-grid.php', array( 'query' => Ajax::get_filtered_query() ) ); ?>
			</div>
		</div>
		<?php
		return (string) ob_get_clean();
	}

	public static function track_popularity(): void {
		if ( ! is_singular( 'ai_tool' ) ) {
			return;
		}

		$post_id = get_queried_object_id();
		if ( ! $post_id ) {
			return;
		}

		$views = (int) get_post_meta( $post_id, 'ai_tool_popularity', true );
		update_post_meta( $post_id, 'ai_tool_popularity', $views + 1 );
	}
}
