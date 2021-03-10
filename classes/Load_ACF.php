<?php


namespace Kntnt\Podcast_Player;


use EnableMediaReplace\Build\PackageLoader;

class Load_ACF {

	public function run() {
		add_action( 'acf/init', [ $this, 'add_options_sub_page' ] );
		add_action( 'acf/init', [ $this, 'add_fields' ] );
	}

	public function add_options_sub_page() {
		Plugin::debug();
		acf_add_options_sub_page( [
			'parent_slug' => 'options-general.php',
			'menu_slug' => 'kntnt-plugin',
			'menu_title' => __( 'Kntnt Podcast Player', 'kntnt-plugin' ),
			'page_title' => __( 'Kntnt Podcast Player', 'kntnt-plugin' ),
		] );
	}

	public function add_fields() {
		Plugin::debug();
		acf_add_local_field_group( [
			'key' => 'group_6030ddadee290',
			'title' => __('Kntnt Plugin', 'kntnt-plugin'),
			'fields' => [
				[
					'key' => 'field_6030ddc2d46d3',
					'label' => __('Text', 'kntnt-plugin'),
					'name' => 'text',
					'type' => 'textarea',
					'instructions' => __('This text area is used by Kntnt Plugin for demonstration purpose.', 'kntnt-plugin'),
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => [
						'width' => '',
						'class' => '',
						'id' => '',
					],
					'default_value' => '',
					'placeholder' => '',
					'maxlength' => '',
					'rows' => '',
					'new_lines' => 'wpautop',
				],
			],
			'location' => [
				[
					[
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'post',
					],
					[
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'page',
					],
				],
			],
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		] );
	}

}