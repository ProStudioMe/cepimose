<?php

use Zolo\Helpers\ZoloHelpers;

$topclass     = 'zolo-post-tab-wrap';
$preset_style = '';
if ( ! empty( $settings['preset'] ) ) {
	$preset_style = ' zolo-post-' . $settings['preset'];
}

if ( ! empty( $settings['postTitleAnimation'] ) ) {
	$topclass .= ' ' . $settings['postTitleAnimation']; // Add space before concatenating.
}

$wrapper_class = ZoloHelpers::get_wrapper_class( $settings, $topclass );
// get parent classes.
$parentClasses = $settings['parentClasses'] ?? [];
// convert to string.
$parentClasses = implode( ' ', $parentClasses );
// add parent classes to wrapper class.
$wrapper_class .= ' ' . $parentClasses;
$wrapperId      = $settings['zoloId'] ?? '';
$html           = '';
$metaSeparator  = ! empty( $settings['metaSeparator'] ) ? $settings['metaSeparator'] : '';
$data_settings  = ZoloHelpers::extract_settings_keys( $settings, array_keys( $class_object->get_default_attributes() ) );
?>

<div class="<?php echo esc_attr( $wrapper_class ); ?>" <?php if ( ! empty( $wrapperId ) ) { ?>
	id="<?php echo esc_attr( $wrapperId ); ?>" <?php } ?>>

	<div class="post-tab-section">
		<div class="post-tab-title-wrap">
			<?php
			if ( ! empty( $settings['showSectionTitle'] ) ) {
				$section_title = $settings['sectionTitle'] ?? '';
				$section_title = ! empty( $settings['sectionTitleWords'] ) ?
					ZoloHelpers::wordcount( $section_title, $settings['sectionTitleWords'] ) : $section_title;
				?>
				<<?php echo esc_attr( $settings['sectionTitleTag'] ); ?> class="section-title">
					<?php echo esc_html( $section_title ); ?>
					<span class="title-dot"></span>
				</<?php echo esc_attr( $settings['sectionTitleTag'] ); ?>>
			<?php } ?>
		</div>

		<?php if ( ! empty( $settings['showSectionTab'] ) ) : ?>
			<div class="post-tab" data-attributes="<?php echo esc_attr( wp_json_encode( $data_settings ) ); ?>">
				<?php if ( ! empty( $settings['showAllCat'] ) ) : ?>
					<a href="#" data-id="all" class="current"><?php esc_html_e( 'All', 'zoloblocks-pro' ); ?></a>
				<?php endif; ?>
				<?php
				$terms = get_terms(
					[
						'taxonomy' => $settings['postTaxonomy'] ?? 'category',
						'include'  => wp_list_pluck( $settings['postCategory'] ?? [], 'value' ),
						'orderby'  => 'include',
					]
				);
				foreach ( $terms as $key => $term ) :
					?>
					<a href="#" class="" data-id="<?php echo esc_attr( $term->term_id ); ?>"><?php echo esc_html( $term->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="post-tab-content <?php echo esc_attr( $preset_style ); ?>">
		<?php require __DIR__ . '/post-tab-content.php'; ?>
	</div>

</div>
