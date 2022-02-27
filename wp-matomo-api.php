<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           MatomoAPI
 *
 * @wordpress-plugin
 * Plugin Name:       Wordpress MatomoAPI
 * Description:       Connect MatomoAPI
 * Version:           1.0.0
 * Author:            Grégory COLLIN
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-matomo-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
use D4rk0s\WpMatomoAPI\WpMatomoAPI;

if ( ! defined('WPINC' ) ) {
	die;
}

register_activation_hook(__FILE__, [WpMatomoAPI::class, 'pluginActivationSequence']);
register_deactivation_hook(__FILE__, [WpMatomoAPI::class, 'pluginDeactivationSequence']);
add_action( 'cli_init', [WpMatomoAPI::class, 'registerCommand'] );