<?php

namespace ZoloPro\Helpers;

// Exit if accessed directly.
if (! defined('ABSPATH')) {
	exit;
}

class ZoloProHelpers {
	/**
	 * Zolo Extension Status
	 */
	public static function zolo_extensions() {
		$extension_options = get_option('zolo_extensions_settings');
		$extensions = [];
		if (! is_array($extension_options)) {
			return $extensions;
		}
		foreach ($extension_options as $value) {
			$extensions[$value['name']] = $value['status'];
		}

		return $extensions;
	}

	/**
	 * Check is Zolo Extension enabled or not
	 */
	public static function is_extension_enabled($extension_name) {
		$extensions = self::zolo_extensions();
		return isset($extensions[$extension_name]) ? $extensions[$extension_name] : false;
	}
}
