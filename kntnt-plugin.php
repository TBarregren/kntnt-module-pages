<?php

/**
 * Plugin main file.
 *
 * @wordpress-plugin
 * Plugin Name:       Kntnt Plugin
 * Plugin URI:        https://github.com/Kntnt/kntnt-plugin
 * GitHub Plugin URI: https://github.com/Kntnt/kntnt-plugin
 * Description:       Does nothing but keeps the most up-to-date version of typically building blocks of a plugin by Kntnt
 * Version:           1.0.3
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Requires PHP:      7.1
 */


namespace Kntnt\Plugin;

// Uncomment following line to debug this plugin.
define( 'KNTNT_PLUGIN_DEBUG', true );

require 'autoload.php';

defined( 'WPINC' ) && new Plugin;
