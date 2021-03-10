<?php


namespace Kntnt\Plugin;


trait Shortcodes {

	// A more forgiving version of WordPress' shortcode_atts().
	public static function shortcode_atts( $pairs, $atts, $shortcode = '' ) {

		// $atts can be a string which is cast to an array. An empty string should
		// be an empty array (not an array with an empty element as by casting).
		$atts = $atts ? (array) $atts : [];

		$out = [];
		$pos = 0;

		while ( $name = key( $pairs ) ) {
			$default = array_shift( $pairs );
			if ( array_key_exists( $name, $atts ) ) {
				$out[ $name ] = $atts[ $name ];
			}
			else if ( array_key_exists( $pos, $atts ) ) {
				$out[ $name ] = $atts[ $pos ];
				++ $pos;
			}
			else {
				$out[ $name ] = $default;
			}
		}

		if ( $shortcode ) {
			$out = apply_filters( "shortcode_atts_{$shortcode}", $out, $pairs, $atts, $shortcode );
		}

		return $out;

	}

}