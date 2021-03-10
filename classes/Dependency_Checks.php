<?php


namespace Kntnt\Plugin;


trait Dependency_Checks {

	static private $unsatisfied_dependencies = null;

	// Returns an array of dependency groups. Each dependency group is an array
	// with alternative plugins. Each plugin is a key/value-pair, where the key
	// is the path to the plugin file relative to the plugins directory,
	// and the value is the name of the plugin. If one of the plugins in a
	// dependency group is active, then the dependency on that dependency group
	// is satisfied. If all dependency groups are satisfied, the dependencies
	// of this plugin is satisfied.
	abstract protected static function dependencies();

	final public static function is_dependencies_satisfied() {
		return ! self::unsatisfied_dependencies();
	}

	// Returns dependencies() except satisfied dependencies.
	final public static function unsatisfied_dependencies() {

		if ( null === self::$unsatisfied_dependencies ) {

			self::$unsatisfied_dependencies = [];
			$dependencies = static::dependencies();

			if ( isset( $dependencies['theme'] ) ) {
				$themes = [ get_option( 'stylesheet' ) ];
				$dependency_group = $dependencies['theme'];
				if ( ! array_intersect( array_keys( $dependency_group ), $themes ) ) {
					self::$unsatisfied_dependencies['theme'][] = $dependency_group;
				}
			}

			if ( isset( $dependencies['plugins'] ) ) {
				$plugins = (array) get_option( 'active_plugins', [] );
				foreach ( $dependencies['plugins'] as $dependency_group ) {
					if ( ! array_intersect( array_keys( $dependency_group ), $plugins ) ) {
						self::$unsatisfied_dependencies['plugins'][] = $dependency_group;
					}
				}
			}

			if ( self::$unsatisfied_dependencies && Plugin::uses( 'Logger' ) ) {
				Plugin::error( static::unsatisfied_dependencies_message() );
			}

		}

		return self::$unsatisfied_dependencies;

	}

	// Returns a message listing missing dependencies.
	// Override to provide a customized message.
	public static function unsatisfied_dependencies_message() {
		$msg = '';
		$unsatisfied_dependencies = [];
		if ( isset( self::unsatisfied_dependencies()['theme'] ) ) {
			$unsatisfied_dependencies += self::unsatisfied_dependencies()['theme'];
		}
		if ( isset( self::unsatisfied_dependencies()['plugins'] ) ) {
			$unsatisfied_dependencies += self::unsatisfied_dependencies()['plugins'];
		}
		if ( $unsatisfied_dependencies ) {
			$n = 0;
			foreach ( $unsatisfied_dependencies as &$dependency ) {
				$n += count( $dependency );
				$dependency = join( ' ' . __( 'or', 'kntnt' ) . ' ', $dependency );
			}
			$items = join( ' ' . __( ', and', 'kntnt' ) . ' ', $unsatisfied_dependencies );
			$msg = sprintf( _n( '%s: %s is required.', '%s: %s are required', $n, 'kntnt' ), strtoupper( $type ), $items );
		}
		return $msg;
	}

}