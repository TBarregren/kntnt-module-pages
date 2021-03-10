<?php


namespace Kntnt\Plugin;


class Load_CSS {

	public function run() {

		global $post;
		if ( isset( $post->post_content ) && is_singular( Plugin::option( 'post_types' ) ) ) {
			if ( has_shortcode( $post->post_content, 'hello' ) ) {
				add_action( 'wp_enqueue_scripts', [ $this, 'load_css' ] );
			}
		}

	}

	public function load_css() {

		// Load the static CSS file.
		$name = Plugin::ns() . 'css';
		$url = Plugin::plugin_url( "css/$name" );
		wp_enqueue_style( $name, $url, [], Plugin::version() );
		Plugin::debug( 'Enqueued %s', $url );

		// Load the dynamic CSS file.
		$name = Plugin::ns() . '.css';
		$url = Plugin::plugin_url( "css/$name" );
		wp_enqueue_style( $name, $url, [], Plugin::version() );

		if ( ( $info = Plugin::option( 'css_file_info' ) ) && $info['url'] ) {
			$name = Plugin::ns() . '-dynamic.css';
			wp_enqueue_style( $name, $info['url'], [], (string) $info['modified'] );
			Plugin::debug( 'Enqueued %s.', $info['url'] );
		}

		Plugin::debug( 'Enqueued %s', $url );

	}

}
