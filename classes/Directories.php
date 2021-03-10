<?php


namespace Kntnt\Plugin;


trait Directories {

	// This plugin's URL with no trailing slash. If $rel_path is given, with
	// or without leading slash, it is appended with leading slash.
	public static final function plugin_url( $rel_path = '' ) {
		static $plugin_url = null;
		if ( is_null( $plugin_url ) ) {
			$plugin_url = plugins_url( '', Plugin::plugin_dir() . '/' . Plugin::ns() . '.php' );
		}
		return self::str_join( $plugin_url, $rel_path );
	}

	// This plugin's path relative WordPress root, with leading slash but no
	// trailing slash. If $rel_path is given, with or without leading slash,
	// it is appended with leading slash.
	public static final function rel_plugin_dir( $rel_path = '' ) {
		return self::str_join( substr( Plugin::plugin_dir(), strlen( ABSPATH ) - 1 ), ltrim( $rel_path, '/' ), '/' );
	}

	// The WordPress' upload directory relative file system root, with leading
	// slash but no trailing slash. If $rel_path is given, with or without
	// leading slash, it is appended with leading slash.
	// Based on _wp_upload_dir().
	public static function upload_dir( $rel_path = '' ) {
		static $upload_dir = null;
		if ( is_null( $upload_dir ) ) {
			$upload_path = trim( get_option( 'upload_path' ) );
			if ( empty( $upload_path ) || 'wp-content/uploads' === $upload_path ) {
				$upload_dir = WP_CONTENT_DIR . '/uploads';
			}
			else if ( 0 !== strpos( $upload_path, ABSPATH ) ) {
				$upload_dir = path_join( ABSPATH, $upload_path );
			}
			else {
				$upload_dir = $upload_path;
			}
		}
		return self::str_join( $upload_dir, ltrim( $rel_path, '/' ), '/' );
	}

	public static function rel_upload_dir( $rel_path = '' ) {
		static $upload_dir = null;
		if ( is_null( $upload_dir ) ) {
			$upload_dir = substr( self::upload_dir(), strlen( ABSPATH ) );
		}
		return self::str_join( $upload_dir, ltrim( $rel_path, '/' ), '/' );
	}

	// The URL of the upload directory. If $rel_path is given, with or without
	// leading slash, it is appended with leading slash.
	public static function upload_url( $rel_path = '' ) {
		static $upload_url = null;
		if ( is_null( $upload_url ) ) {
			$upload_url = get_site_url( null, self::rel_upload_dir() );
		}
		return self::str_join( $upload_url, ltrim( $rel_path, '/' ), '/' );
	}

	// The WordPress' root relative file system root, with no trailing slash.
	// If $rel_path is given, with or without leading slash, it is appended
	// with leading slash.
	public static final function wp_dir( $rel_path = '' ) {
		return self::str_join( ABSPATH, ltrim( $rel_path, '/' ), '/' );
	}

}