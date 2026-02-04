<?php
namespace AI_Tools_Directory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin {
	public static function register(): void {
		add_action( 'admin_menu', array( __CLASS__, 'register_settings_page' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	public static function register_settings_page(): void {
		add_submenu_page(
			'edit.php?post_type=ai_tool',
			__( 'Directory Settings', 'ai-tools-directory' ),
			__( 'Directory Settings', 'ai-tools-directory' ),
			'manage_options',
			'ai-tools-directory-settings',
			array( __CLASS__, 'render_settings_page' )
		);
	}

	public static function register_settings(): void {
		register_setting(
			'ai_tools_directory_settings',
			'ai_tools_directory_settings',
			array( 'sanitize_callback' => array( __CLASS__, 'sanitize_settings' ) )
		);

		add_settings_section(
			'ai_tools_directory_sorting',
			__( 'Sorting & Featured', 'ai-tools-directory' ),
			'__return_false',
			'ai_tools_directory_settings'
		);

		add_settings_field(
			'default_sort',
			__( 'Default Sort Order', 'ai-tools-directory' ),
			array( __CLASS__, 'render_default_sort_field' ),
			'ai_tools_directory_settings',
			'ai_tools_directory_sorting'
		);

		add_settings_field(
			'featured_limit',
			__( 'Featured Tools Limit', 'ai-tools-directory' ),
			array( __CLASS__, 'render_featured_limit_field' ),
			'ai_tools_directory_settings',
			'ai_tools_directory_sorting'
		);

		add_settings_section(
			'ai_tools_directory_seo',
			__( 'SEO Defaults', 'ai-tools-directory' ),
			'__return_false',
			'ai_tools_directory_settings'
		);

		add_settings_field(
			'default_meta_description',
			__( 'Default Meta Description', 'ai-tools-directory' ),
			array( __CLASS__, 'render_meta_description_field' ),
			'ai_tools_directory_settings',
			'ai_tools_directory_seo'
		);
	}

	public static function sanitize_settings( array $input ): array {
		$output = array();

		$output['default_sort'] = isset( $input['default_sort'] )
			? sanitize_text_field( $input['default_sort'] )
			: 'newest';

		$output['featured_limit'] = isset( $input['featured_limit'] )
			? absint( $input['featured_limit'] )
			: 8;

		$output['default_meta_description'] = isset( $input['default_meta_description'] )
			? sanitize_textarea_field( $input['default_meta_description'] )
			: '';

		return $output;
	}

	public static function render_settings_page(): void {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'AI Tools Directory Settings', 'ai-tools-directory' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'ai_tools_directory_settings' );
				do_settings_sections( 'ai_tools_directory_settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public static function render_default_sort_field(): void {
		$options = self::get_settings();
		$choices = array(
			'newest'   => __( 'Newest', 'ai-tools-directory' ),
			'popular'  => __( 'Most Popular', 'ai-tools-directory' ),
			'az'       => __( 'A - Z', 'ai-tools-directory' ),
			'featured' => __( 'Featured', 'ai-tools-directory' ),
		);
		?>
		<select name="ai_tools_directory_settings[default_sort]">
			<?php foreach ( $choices as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $options['default_sort'] ?? 'newest', $value ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	public static function render_featured_limit_field(): void {
		$options = self::get_settings();
		?>
		<input type="number" name="ai_tools_directory_settings[featured_limit]" min="1" max="50" value="<?php echo esc_attr( $options['featured_limit'] ?? 8 ); ?>">
		<?php
	}

	public static function render_meta_description_field(): void {
		$options = self::get_settings();
		?>
		<textarea class="large-text" rows="3" name="ai_tools_directory_settings[default_meta_description]"><?php echo esc_textarea( $options['default_meta_description'] ?? '' ); ?></textarea>
		<?php
	}

	public static function get_settings(): array {
		$defaults = array(
			'default_sort'            => 'newest',
			'featured_limit'          => 8,
			'default_meta_description' => '',
		);

		return wp_parse_args( get_option( 'ai_tools_directory_settings', array() ), $defaults );
	}
}
