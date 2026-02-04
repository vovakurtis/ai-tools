<?php
namespace AI_Tools_Directory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Meta_Boxes {
	private const META_FIELDS = array(
		'website_url'       => 'ai_tool_website_url',
		'short_description' => 'ai_tool_short_description',
		'full_description'  => 'ai_tool_full_description',
		'featured'          => 'ai_tool_featured',
		'model_type'        => 'ai_tool_model_type',
		'rating'            => 'ai_tool_rating',
		'review_summary'    => 'ai_tool_review_summary',
		'pros'              => 'ai_tool_pros',
		'cons'              => 'ai_tool_cons',
		'alternatives'      => 'ai_tool_alternatives',
		'faq'               => 'ai_tool_faq',
		'pricing_notes'     => 'ai_tool_pricing_notes',
	);

	public static function register(): void {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'save_post_ai_tool', array( __CLASS__, 'save_meta_boxes' ) );
	}

	public static function add_meta_boxes(): void {
		add_meta_box(
			'ai-tool-details',
			__( 'AI Tool Details', 'ai-tools-directory' ),
			array( __CLASS__, 'render_meta_box' ),
			'ai_tool',
			'normal',
			'high'
		);
	}

	public static function render_meta_box( 
		\WP_Post $post
	): void {
		wp_nonce_field( 'ai_tool_details_nonce', 'ai_tool_details_nonce' );

		$values = array();
		foreach ( self::META_FIELDS as $field_key => $meta_key ) {
			$values[ $field_key ] = get_post_meta( $post->ID, $meta_key, true );
		}
		$popularity = (int) get_post_meta( $post->ID, 'ai_tool_popularity', true );
		$model_types = array( 'GPT', 'Claude', 'Gemini', 'Custom' );
		?>
		<p>
			<label for="ai-tool-website-url"><strong><?php esc_html_e( 'Official Website URL', 'ai-tools-directory' ); ?></strong></label>
			<input type="url" class="widefat" id="ai-tool-website-url" name="ai_tool_website_url" value="<?php echo esc_attr( $values['website_url'] ?? '' ); ?>">
		</p>
		<p>
			<label for="ai-tool-short-description"><strong><?php esc_html_e( 'Short Description', 'ai-tools-directory' ); ?></strong></label>
			<textarea class="widefat" rows="3" id="ai-tool-short-description" name="ai_tool_short_description"><?php echo esc_textarea( $values['short_description'] ?? '' ); ?></textarea>
		</p>
		<p>
			<label for="ai-tool-full-description"><strong><?php esc_html_e( 'Full SEO Description', 'ai-tools-directory' ); ?></strong></label>
			<textarea class="widefat" rows="6" id="ai-tool-full-description" name="ai_tool_full_description"><?php echo esc_textarea( $values['full_description'] ?? '' ); ?></textarea>
		</p>
		<p>
			<label for="ai-tool-model-type"><strong><?php esc_html_e( 'AI Model Type', 'ai-tools-directory' ); ?></strong></label>
			<select id="ai-tool-model-type" name="ai_tool_model_type" class="widefat">
				<?php foreach ( $model_types as $model_type ) : ?>
					<option value="<?php echo esc_attr( $model_type ); ?>" <?php selected( $values['model_type'] ?? '', $model_type ); ?>>
						<?php echo esc_html( $model_type ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="ai-tool-rating"><strong><?php esc_html_e( 'Rating (1-5)', 'ai-tools-directory' ); ?></strong></label>
			<input type="number" id="ai-tool-rating" name="ai_tool_rating" min="1" max="5" step="0.1" value="<?php echo esc_attr( $values['rating'] ?? '' ); ?>">
		</p>
		<p>
			<label for="ai-tool-review-summary"><strong><?php esc_html_e( 'Review Summary', 'ai-tools-directory' ); ?></strong></label>
			<textarea class="widefat" rows="3" id="ai-tool-review-summary" name="ai_tool_review_summary"><?php echo esc_textarea( $values['review_summary'] ?? '' ); ?></textarea>
		</p>
		<p>
			<label>
				<input type="checkbox" name="ai_tool_featured" value="1" <?php checked( ! empty( $values['featured'] ) ); ?>>
				<strong><?php esc_html_e( 'Featured Tool', 'ai-tools-directory' ); ?></strong>
			</label>
		</p>
		<p>
			<label for="ai-tool-pros"><strong><?php esc_html_e( 'Pros', 'ai-tools-directory' ); ?></strong></label>
			<textarea class="widefat" rows="3" id="ai-tool-pros" name="ai_tool_pros" placeholder="<?php esc_attr_e( 'Bullet points separated by new lines.', 'ai-tools-directory' ); ?>"><?php echo esc_textarea( $values['pros'] ?? '' ); ?></textarea>
		</p>
		<p>
			<label for="ai-tool-cons"><strong><?php esc_html_e( 'Cons', 'ai-tools-directory' ); ?></strong></label>
			<textarea class="widefat" rows="3" id="ai-tool-cons" name="ai_tool_cons" placeholder="<?php esc_attr_e( 'Bullet points separated by new lines.', 'ai-tools-directory' ); ?>"><?php echo esc_textarea( $values['cons'] ?? '' ); ?></textarea>
		</p>
		<p>
			<label for="ai-tool-alternatives"><strong><?php esc_html_e( 'Alternatives', 'ai-tools-directory' ); ?></strong></label>
			<textarea class="widefat" rows="3" id="ai-tool-alternatives" name="ai_tool_alternatives" placeholder="<?php esc_attr_e( 'Comma-separated tool names.', 'ai-tools-directory' ); ?>"><?php echo esc_textarea( $values['alternatives'] ?? '' ); ?></textarea>
		</p>
		<p>
			<label for="ai-tool-faq"><strong><?php esc_html_e( 'FAQ', 'ai-tools-directory' ); ?></strong></label>
			<textarea class="widefat" rows="4" id="ai-tool-faq" name="ai_tool_faq" placeholder="<?php esc_attr_e( 'Q: Question?\nA: Answer', 'ai-tools-directory' ); ?>"><?php echo esc_textarea( $values['faq'] ?? '' ); ?></textarea>
		</p>
		<p>
			<label for="ai-tool-pricing-notes"><strong><?php esc_html_e( 'Pricing Notes', 'ai-tools-directory' ); ?></strong></label>
			<textarea class="widefat" rows="3" id="ai-tool-pricing-notes" name="ai_tool_pricing_notes"><?php echo esc_textarea( $values['pricing_notes'] ?? '' ); ?></textarea>
		</p>
		<p>
			<strong><?php esc_html_e( 'Popularity Counter', 'ai-tools-directory' ); ?></strong><br>
			<?php echo esc_html( number_format_i18n( $popularity ) ); ?>
		</p>
		<?php
	}

	public static function save_meta_boxes( int $post_id ): void {
		if ( ! isset( $_POST['ai_tool_details_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ai_tool_details_nonce'] ) ), 'ai_tool_details_nonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$fields = array(
			'website_url'       => FILTER_VALIDATE_URL,
			'short_description' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'full_description'  => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'rating'            => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'review_summary'    => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'pros'              => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'cons'              => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'alternatives'      => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'faq'               => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'pricing_notes'     => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'model_type'        => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
		);

		foreach ( $fields as $field => $filter ) {
			$input_key = self::META_FIELDS[ $field ];
			if ( ! isset( $_POST[ $input_key ] ) ) {
				continue;
			}
			$value = wp_unslash( $_POST[ $input_key ] );
			$sanitized = false;
			if ( FILTER_VALIDATE_URL === $filter ) {
				$sanitized = esc_url_raw( $value );
			} elseif ( 'rating' === $field ) {
				$rating = (float) $value;
				$rating = max( 1, min( 5, $rating ) );
				$sanitized = (string) $rating;
			} else {
				$sanitized = sanitize_textarea_field( $value );
			}
			update_post_meta( $post_id, $input_key, $sanitized );
		}

		$featured = isset( $_POST[ self::META_FIELDS['featured'] ] ) ? '1' : '0';
		update_post_meta( $post_id, self::META_FIELDS['featured'], $featured );
	}
}
