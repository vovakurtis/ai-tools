<?php
/**
 * Filter bar for directory.
 *
 * @var array $filters
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sort_options = array(
	'newest'   => __( 'Newest', 'ai-tools-directory' ),
	'popular'  => __( 'Popular', 'ai-tools-directory' ),
	'az'       => __( 'A-Z', 'ai-tools-directory' ),
	'featured' => __( 'Featured', 'ai-tools-directory' ),
);
?>
<form class="ai-tools-directory__filters" data-directory-filters>
	<div class="ai-tools-directory__filters-row">
		<?php foreach ( $filters as $taxonomy => $terms ) : ?>
			<label>
				<span class="screen-reader-text"><?php echo esc_html( $taxonomy ); ?></span>
				<select name="<?php echo esc_attr( $taxonomy ); ?>">
					<option value=""><?php echo esc_html( ucwords( str_replace( '_', ' ', $taxonomy ) ) ); ?></option>
					<?php foreach ( $terms as $term ) : ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		<?php endforeach; ?>

		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'Sort', 'ai-tools-directory' ); ?></span>
			<select name="sort">
				<?php foreach ( $sort_options as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</div>
</form>
