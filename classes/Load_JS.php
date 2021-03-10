<?php


namespace Kntnt\Plugin;


class Load_JS {

	public function run() {
		$name = Plugin::ns() . '.js';
		$url = Plugin::plugin_url( "js/$name" );
		wp_enqueue_script( $name, $url, [ 'jquery' ], Plugin::version(), true );
		Plugin::debug( 'Enqueued %s', $url );
	}

}
