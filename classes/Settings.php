<?php

namespace Kntnt\Plugin;

class Settings extends Abstract_Settings {

	protected function menu_title() {
		return __( 'Kntnt Plugin', 'kntnt-plugin' );
	}

	protected function fields() {

		$fields['post_types'] = [
			'type' => 'checkbox group',
			'label' => __( "Enabled post types", 'kntnt-plugin' ),
			'description' => __( 'The style below is applied to selected post types.', 'kntnt-lead' ),
			'options' => wp_list_pluck( get_post_types( [ 'public' => true ], 'objects' ), 'label' ),
			'default' => [ 'post' ],
		];

		$fields['css'] = [
			'type' => 'text area',
			'label' => __( 'Style', 'kntnt-plugin' ),
			'cols' => 80,
			'rows' => 8,
		];

		$fields['submit'] = [
			'type' => 'submit',
		];

		return $fields;

	}

	protected final function actions_after_saving( $opt, $fields ) {
		if ( $opt['css'] ) {
			$info = Plugin::save_to_file( $opt['css'], 'css' );
			Plugin::set_option( 'css_file_info', $info );
			Plugin::debug( 'Saved "%s".', $info['file'] );
		}
		else if ( $info = Plugin::option( 'css_file_info' ) ) {
			@unlink( $info['file'] );
			Plugin::delete_option( 'css_file_info' );
			Plugin::debug( 'Deleted "%s".', $info['file'] );
		}
	}

}
