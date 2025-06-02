<?php
namespace ZoloPro\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use ZoloPro\Traits\SingletonTrait;
use Zolo\Helpers\ZoloHelpers;
use Zolo\API\GetPostsV1;

/**
 * Zolo ajax Pro
 */
class ZoloAjax {

	use SingletonTrait;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_zolo_ajax_post_tab', [ $this, 'get_ajax_post_tab' ] );
		add_action( 'wp_ajax_nopriv_zolo_ajax_post_tab', [ $this, 'get_ajax_post_tab' ] );
	}

	/**
	 * Post jax tab frontend
	 *
	 * @return void
	 */
	public function get_ajax_post_tab() {
		if ( ! wp_verify_nonce( ZoloHelpers::ge_nonce_id(), ZoloHelpers::get_nonce_text() ) ) {
			wp_send_json_error( esc_html__( 'Session Expired!!', 'zoloblocks-pro' ) );
		}
		$catId         = sanitize_text_field( wp_unslash( $_POST['catId'] ?? '' ) );
		$settings_json = sanitize_text_field( wp_unslash( $_POST['settings'] ?? '' ) );
		$settings      = json_decode( $settings_json, true );
		$postQuery     = $settings['postQuery'] ?? [];

		if ( ! empty( $catId ) && 'all' !== $catId ) {
			$postQuery['postTermId']   = $catId;
			$postQuery['postTaxonomy'] = $settings['postTaxonomy'] ?? 'category';
		}

		$post_results = apply_filters( 'zolo_post_tab_results', GetPostsV1::zolo_posts_query( $postQuery ) );

		$data = $this->get_ajax_post_content( $post_results, $settings );
		wp_send_json_success( $data );
	}

	/**
	 * Post tab ajax content
	 *
	 * @param array $post_results .
	 * @param array $settings .
	 * @return false|string
	 */
	public function get_ajax_post_content( $post_results, $settings ) {
		ob_start();
		ZoloHelpers::views(
			'post-tab-content',
			[
				'settings'     => $settings,
				'post_results' => $post_results,
			]
		);
		return ob_get_clean();
	}
}
