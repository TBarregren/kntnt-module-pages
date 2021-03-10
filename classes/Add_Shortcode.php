<?php


namespace Kntnt\Podcast_Player;


class Add_Shortcode {

	use Badges;

	public function run() {
		add_shortcode( 'hello', [ $this, 'shortcode' ] );
		Plugin::debug( 'Added shortcode [%s].', 'my_shortcode' );
	}

	public function shortcode( $atts, $content, $tag ) {

		// Fill in missing shortcode attributes.
		$atts = Plugin::shortcode_atts( [
			'name' => '',
			'hello' => __( 'Hello', '' ),
		], $atts, $tag );
		Plugin::debug( 'Shortcode attributes: %s.', $atts );

		// Create the output that the shortcode and $content will be replaced with.
		$content = sprintf( '%s %s', $atts['hello'], $atts['name'] ) . "\n" . $content;
		Plugin::debug( 'Shortcode output: %s.', $content );

		return $content;

	}

}