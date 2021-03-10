<?php


namespace Kntnt\Plugin;


final class Plugin extends Abstract_Plugin {

	// The plugin is extended with following functionalities.
	use Dependency_Checks;
	use Directories;
	use File_Save;
	use Logger;
	use Options;
	use Shortcodes;
	use Templates;

	protected static function dependencies() {
		return [
			'theme' => [
				'kadence' => 'Kadence Theme',
			],
			'plugins' => [
				[
					'advanced-custom-fields/acf.php' => 'Advanced Custom Fields',
					'advanced-custom-fields-pro/acf.php' => 'Advanced Custom Fields Pro',
				],
			],
		];
	}

	public function classes_to_load() {
		return ! self::is_dependencies_satisfied() ? [] : [
			'any' => [
				'plugins_loaded' => [
					'Load_ACF',
				],
			],
			'public' => [
				'wp' => [
					'Add_Shortcode',
					'Load_CSS', // Because Add_Shortcode's CSS is lazy loaded, this must go before wp_enqueue_scripts but come after Add_Shortcode.
				],
				'wp_enqueue_scripts' => [
					'Load_JS',
				],
			],
			'admin' => [
				'init' => [
					'Settings',
				],
			],
		];
	}

}
