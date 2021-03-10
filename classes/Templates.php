<?php


namespace Kntnt\Plugin;


trait Templates {

	/**
	 * Imports `$template_file` relative this plugin's directory. It it
	 * contains PHP-code, it is evaluated in a context where the each element
	 * of associative array `$template_variables` is converted into a variable
	 * with the name and value of the elements key and value, respectively. The
	 * resulting content is included at the point of execution of this function
	 * if  `$return_template_as_string` is false (default), otherwise returned
	 * as a string.
	 *
	 * @param $template_file Template file relative this plugin's directory
	 * @param array $template_variables Template variables
	 * @param false $return_template_as_string Echo or return the interpolated template
	 *
	 * @return false|string The interpolated template if $return_template_as_string == true.
	 */
	public static final function include_template( $template_file, $template_variables = [], $return_template_as_string = false ) {
		extract( $template_variables, EXTR_SKIP );
		if ( $return_template_as_string ) {
			ob_start();
		}
		require Plugin::plugin_dir( $template_file );
		if ( $return_template_as_string ) {
			return ob_get_clean();
		}
	}

}