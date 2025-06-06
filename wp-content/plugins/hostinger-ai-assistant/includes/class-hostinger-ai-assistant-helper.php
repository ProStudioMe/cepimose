<?php

class Hostinger_Ai_Assistant_Helper {
    public const HOMEPAGE_DISPLAY = 'page';

	/**
	 *
	 * Check if plugin is active
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static function is_plugin_active( $plugin_slug ): bool {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		foreach ( $active_plugins as $active_plugin ) {
			if ( strpos( $active_plugin, $plugin_slug . '.php' ) !== false ) {
				return true;
			}
		}

		return false;
	}

	public static function get_api_token(): string {
		$api_token  = '';
		$token_file = HOSTINGER_AI_ASSISTANT_WP_AI_TOKEN;

		if ( file_exists( $token_file ) && ! empty( file_get_contents( $token_file ) ) ) {

			$api_token = file_get_contents( $token_file );
		}

		return $api_token;
	}

	/**
	 *
	 * Get the host info (domain, subdomain, subdirectory)
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function get_host_info(): string {
		$host     = $_SERVER['HTTP_HOST'] ?? '';
		$site_url = get_site_url();
		$site_url = preg_replace( '#^https?://#', '', $site_url );

		if ( ! empty( $site_url ) && ! empty( $host ) && strpos( $site_url, $host ) === 0 ) {
			if ( $site_url === $host ) {
				return $host;
			} else {
				return substr( $site_url, strlen( $host ) + 1 );
			}
		}

		return $host;
	}

	public function ajax_error_message( string $message, string $display_error ): void {
		error_log( 'Error: ' . $message );
		if ( ! empty( $display_error ) ) {
			wp_send_json_error( $display_error );
		}
	}

	public function is_preview_domain(): bool {
		if ( function_exists( 'getallheaders' ) ) {
			$headers = getallheaders();
		}

		if ( isset( $headers['X-Preview-Indicator'] ) && $headers['X-Preview-Indicator'] ) {
			return true;
		}

		return false;
	}

	public function get_url_protocol(): string {
		$protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

		return $protocol;
	}

	public function overwrite_url_host( string $url, string $newHost ): string {
		$parsedUrl = parse_url( $url );

		if ( $parsedUrl === false || ! isset( $parsedUrl['scheme'] ) || ! isset( $parsedUrl['host'] ) ) {
			error_log( 'Error: Invalid URL' );

			return false;
		}

		$parsedUrl['host'] = $newHost;

		$modifiedUrl = $parsedUrl['scheme'] . '://';
		if ( isset( $parsedUrl['user'] ) && isset( $parsedUrl['pass'] ) ) {
			$modifiedUrl .= $parsedUrl['user'] . ':' . $parsedUrl['pass'] . '@';
		}
		$modifiedUrl .= $parsedUrl['host'];
		if ( isset( $parsedUrl['port'] ) ) {
			$modifiedUrl .= ':' . $parsedUrl['port'];
		}
		if ( isset( $parsedUrl['path'] ) ) {
			$modifiedUrl .= $parsedUrl['path'];
		}
		if ( isset( $parsedUrl['query'] ) ) {
			$modifiedUrl .= '?' . $parsedUrl['query'];
		}
		if ( isset( $parsedUrl['fragment'] ) ) {
			$modifiedUrl .= '#' . $parsedUrl['fragment'];
		}

		return $modifiedUrl;
	}

	public function has_taxonomy_for_post_type( string $post_type, string $taxonomy_slug ): bool {
		$taxonomy_object = get_taxonomy( $taxonomy_slug );

		if ( ! $taxonomy_object ) {
			return false;
		}

		return in_array( $post_type, $taxonomy_object->object_type );
	}

	public function post_type_supports_featured_image( string $post_type ): bool {
		$post_type_object = get_post_type_object( $post_type );

		if ( $post_type_object && post_type_supports( $post_type, 'thumbnail' ) ) {
			return true;
		}

		return false;
	}

	public function sanitize_html_string( $htmlString ) {
		$cleanedString = stripslashes( $htmlString );
		$cleanedString = preg_replace( '/\\\\\'/', "'", $cleanedString ); // Replace escaped single quotes

		return $cleanedString;
	}

	public function error_log( string $message ): void {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
			error_log( print_r( $message, true ) );
		}
	}

	public function add_vue_instance(): void {
		ob_start(); ?>
		<div id="vue-app"></div>
		<?php echo ob_get_clean();
	}

    public function get_edit_site_url(): string {
        if ( wp_is_block_theme() ) {
            return '';
        }

        $show_on_front = get_option( 'show_on_front' );
        $front_page_id = get_option( 'page_on_front' );


        if ( $show_on_front === self::HOMEPAGE_DISPLAY && $front_page_id ) {
            return add_query_arg( [
                'post'   => $front_page_id,
                'action' => 'edit',
            ], admin_url( 'post.php' ) );
        }

        return '';
    }
}

$hostiner_helper = new Hostinger_Ai_Assistant_Helper();
