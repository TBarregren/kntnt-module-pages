<?php


namespace Kntnt\Plugin;


class Abstract_Settings {

	/**
	 * Bootstrap instance of this class.
	 */
	public function run() {
		$ns = Plugin::ns();
		add_action( 'admin_menu', [ $this, 'add_options_page' ] );
		add_filter( "plugin_action_links_$ns/$ns.php", [ $this, 'add_plugin_action_links' ], 10, 2 );
	}

	/**
	 * Add settings page to the option menu.
	 */
	public function add_options_page() {
		add_options_page( $this->page_title(), $this->menu_title(), $this->capability(), Plugin::ns(), [ $this, 'options_page' ] );
	}

	/**
	 * Returns $links with a link to this setting page added.
	 *
	 * @param $actions
	 *
	 * @return mixed
	 */
	public function add_plugin_action_links( $actions ) {
		$ns = Plugin::ns();
		$settings_link_name = __( 'Settings', 'kntnt' );
		$settings_link_url = admin_url( "options-general.php?page={$ns}" );
		$actions[] = "<a href=\"$settings_link_url\">$settings_link_name</a>";
		return $actions;
	}

	/**
	 * Show settings page and update options.
	 */
	public function options_page() {

		// Abort if current user has not permission to access the settings page.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Unauthorized use.', 'kntnt' ) );
		}

		// Update options if the option page is saved.
		if ( $_POST ) {

			$opt = isset( $_POST[ Plugin::ns() ] ) ? ( $_POST[ Plugin::ns() ] ) : [];

			// The need for stripslashes() despite that Magic Quotes were
			// deprecated already in PHP 5.4 is due to WordPress backward
			// compatibility. WordPress roll their won version of "magic
			// quotes" because too much core and plugin code have come to
			// rely on the quotes being there. Jeez…
			$opt = stripslashes_deep( $opt );

			$this->update_options( $opt );

		}

		// Render the option page.
		$this->render_settings_page();

	}

	/**
	 * Returns title used as menu item.
	 */
	protected function menu_title() {
		return Plugin::name();
	}

	/**
	 * Returns title used as head of settings page.
	 */
	protected function page_title() {
		return Plugin::name();
	}

	/**
	 * Returns all fields used on the settings page.
	 */
	protected function fields() {

		$fields['submit'] = [
			'type' => 'submit',
		];

		return $fields;

	}

	/**
	 * Returns necessary capability to access the settings page.
	 */
	protected function capability() {
		return 'manage_options';
	}

	/**
	 * Validates that $value is not empty.
	 *
	 * @param $value mixed The value to validate.
	 * @param $field array The field description.
	 *
	 * @return bool True if and only if $value in non-empty.
	 * @noinspection PhpUnusedParameterInspection
	 */
	protected function validate_required( $value, $field ) {
		return ! empty( $value );
	}

	/**
	 * Validates that $integer is either empty or an integer.
	 *
	 * @param $integer integer The value to validate.
	 * @param $field array The field description.
	 *
	 * @return bool True if and only if $integer is either an empty scalar (e.g.
	 * an empty string but not an empty array) or an integer.
	 */
	protected function validate_integer( $integer, $field ) {
		return empty( $integer ) ||
		       ( false !== filter_var( $integer, FILTER_VALIDATE_INT ) ) &&
		       ( ! isset( $field['min'] ) || intval( $field['min'] ) <= intval( $integer ) ) &&
		       ( ! isset( $field['max'] ) || intval( $field['max'] ) >= intval( $integer ) ) &&
		       ( ! isset( $field['step'] ) || ! ( ( intval( $integer ) - intval( isset( $field['min'] ) ? $field['min'] : 0 ) ) % intval( $field['step'] ) ) );
	}

	/**
	 * Validates that $number is either empty or a number.
	 *
	 * @param $number float The value to validate.
	 * @param $field array The field description.
	 *
	 * @return bool True if and only if $number is either an empty scalar (e.g.
	 * an empty string but not an empty array) or an integer or floating point
	 * number.
	 */
	protected function validate_number( $number, $field ) {
		return empty( $number ) ||
		       is_numeric( $number ) &&
		       ( ! isset( $field['min'] ) || floatval( $field['min'] ) <= floatval( $number ) ) &&
		       ( ! isset( $field['max'] ) || floatval( $field['max'] ) >= floatval( $number ) );
	}

	/**
	 * Validates that $val is an URL.
	 *
	 * @param $url   string The value to validate.
	 * @param $field array The field description.
	 *
	 * @return bool True if and only if $url is a proper formatted URL or empty.
	 * @noinspection PhpUnusedParameterInspection
	 * @noinspection PhpUnused
	 */
	protected function validate_url( $url, $field ) {
		return ( '' == $url ) || ( false !== filter_var( $url, FILTER_VALIDATE_URL ) );
	}

	/**
	 * Validates that $email is an email address.
	 *
	 * @param $email  string The value to validate.
	 * @param $field  array The field description.
	 *
	 * @return bool True if and only if $email is a proper formatted email
	 * address.
	 * @noinspection PhpUnusedParameterInspection
	 * @noinspection PhpUnused
	 */
	protected function validate_email( $email, $field ) {
		return ( '' == $email ) || ( false !== filter_var( $email, FILTER_VALIDATE_EMAIL ) );
	}

	/**
	 * Validates that the value(s) in $values match the options in $options.
	 *
	 * @param       $val    mixed Either a value or an array of values to validate.
	 * @param       $field  array The field description.
	 *
	 * @return bool True if and only if a single value in $value match an option
	 *              in $option or if all values in an array $values of values
	 *              match an option in $option.
	 */
	protected function validate_options( $val, $field ) {
		if ( ! is_array( $val ) ) {
			if ( ! empty( $val ) && ! array_key_exists( $val, $field['options'] ) ) {
				return false;
			}
		}
		else {
			foreach ( $val as $key => $value ) {
				if ( ! array_key_exists( $key, $field['options'] ) ) {
					return false;
				}
			}

		}
		return true;
	}

	/**
	 * A concrete instance
	 *
	 * @param $opt array The option values.
	 * @param $fields array The field description.
	 */
	protected function actions_after_saving( $opt, $fields ) { }

	/**
	 * Render settings page.
	 * @noinspection PhpUnusedLocalVariableInspection
	 */
	private function render_settings_page() {

		// Warn about unsatisfied dependencies.
		if ( Plugin::is_using( 'Dependency_Check' ) && $unsatisfied_dependencies = Plugin::unsatisfied_dependencies() ) {
			$message = sprintf( _n( 'This plugin must be installed and active: %s', 'These plugins must be installed and active: %s', count( $unsatisfied_dependencies ), 'kntnt' ), join( ', ', $unsatisfied_dependencies ) );
			$this->notify_admin( $message, 'warning' );
		}

		// Variables that will be visible for the settings-page template.
		$ns = Plugin::ns();
		$title = $this->page_title();
		$fields = $this->fields();
		$values = Plugin::option();

		// Default values that will be visible for the settings-page template.
		foreach ( $fields as $id => $field ) {

			// Set default if no value is saved.
			if ( ! isset( $values[ $id ] ) ) {
				$values[ $id ] = isset( $field['default'] ) ? $field['default'] : null;
			}

			// Filter saved value before outputting it.
			if ( isset( $field['filter-before'] ) ) {
				$filter = $field['filter-before'];
				$values[ $id ] = $filter( $values[ $id ] );
			}

		}

		// Render settings page; include the settings-page template.
		/** @noinspection PhpIncludeInspection */
		include Plugin::plugin_dir( 'includes/settings-page.php' );

	}

	/**
	 * Validate, sanitize and save field values.
	 *
	 * @param $opt array The option values.
	 */
	private function update_options( $opt ) {

		// Abort if the form's nonce is not correct or expired.
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], Plugin::ns() ) ) {
			wp_die( __( 'Nonce failed.', 'kntnt' ) );
		}

		// Get fields
		$fields = $this->fields();

		// Validate inputted values.
		$validates = true;
		foreach ( $fields as $id => $field ) {

			// A `checkbox` that is not checked will be missing in $opt and
			// needs to added with 0 as value for consistency.
			if ( 'checkbox' == $field['type'] && ! isset( $opt[ $id ] ) ) {
				// TODO: It's not possible to store false.
				$opt[ $id ] = 0;
			}

			// A `checkbox group` with no options selected will be missing in
			// $opt and needs to be added with an empty array as value for
			// consistency.
			if ( 'checkbox group' == $field['type'] && ! isset( $opt[ $id ] ) ) {
				$opt[ $id ] = [];
			}

			// A `select multiple` with no options selected will be missing in
			// $opt and needs to added with an empty array as value for
			// consistency. A `select multiple` with one or more options
			// selected needs special treatment to be consistent with other
			// fields with options.
			if ( 'select multiple' == $field['type'] ) {
				if ( ! isset( $opt[ $id ] ) ) {
					$opt[ $id ] = [];
				}
				else {
					$opt[ $id ] = array_combine( $opt[ $id ], $opt[ $id ] );
				}
			}

			// Validate that required fields have value for the extremely
			// unlikely case that someone else's code tries to fake a settings
			// form post.
			if ( isset( $field['required'] ) ) {
				if ( ! $this->validate_required( $opt[ $id ], $field ) ) {
					$validates = false;
					$this->notify_error( $field );
				}
			}

			// Validate fields with pre-defined options for the extremely
			// unlikely case that someone else's code tries to fake a settings
			// form post.
			if ( isset( $field['options'] ) ) {
				if ( ! $this->validate_options( $opt[ $id ], $field ) ) {
					$validates = false;
					$this->notify_error( $field );
				}
			}

			// Validate fields for which there exists pre-defined baseline
			// validators. More sophisticated validation can be defined in
			// the field settings.
			$validator = 'validate_' . $field['type'];
			if ( method_exists( $this, $validator ) ) {
				if ( ! $this->$validator( $opt[ $id ], $field ) ) {
					$validates = false;
					$this->notify_error( $field );
				}
			}

			// Run provided validators.
			if ( isset( $field['validate'] ) ) {

				$validator = $field['validate'];
				if ( ! $validator( $opt[ $id ], $opt ) ) {
					$validates = false;
					$this->notify_error( $field );
				}

			}

		}

		if ( $validates ) {

			// Filter inputted values.
			foreach ( $fields as $id => $field ) {
				if ( isset( $field['filter-after'] ) ) {
					$filter = $field['filter-after'];
					$opt[ $id ] = $filter( $opt [ $id ] );
				}
			}

			// Keep other options that are not settings.
			$opt = array_merge( Plugin::option( null, [] ), $opt );

			// Save inputted values.
			update_option( Plugin::ns(), $opt );

			// Success notification
			$this->notify_success();

			// Logging
			if ( Plugin::is_using( 'Logger' ) ) {
				Plugin::debug( 'Options saved: %s', $opt );
			}

			// Actions after saving.
			$this->actions_after_saving( $opt, $fields );

		}

	}

	private function notify_error( $field ) {
		if ( isset( $field['validate-error-message'] ) ) {
			$message = $field['validate-error-message'];
		}
		else if ( $field['label'] ) {
			$message = sprintf( __( '<strong>ERROR:</strong> Invalid data in the field <em>%s</em>.', 'kntnt' ), $field['label'] );
		}
		else {
			$message = __( '<strong>ERROR:</strong> Please review the settings and try again.', 'kntnt' );
		}
		$this->notify_admin( $message, 'error' );
	}

	private function notify_success() {
		$message = __( 'Successfully saved settings.', 'kntnt' );
		$this->notify_admin( $message, 'success' );
	}

	private function notify_admin( $message, $type ) {
		echo "<div class=\"notice notice-$type is-dismissible\"><p>$message</p></div>";
	}

}
