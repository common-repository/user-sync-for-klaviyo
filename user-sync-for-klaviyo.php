<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://oakandbeech.com
 * @since             1.0.0
 * @package           User_Sync_For_Klaviyo
 *
 * @wordpress-plugin
 * Plugin Name:       User Sync For Klaviyo
 * Plugin URI:        https://oakandbeech.com/plugins
 * Description:       This plugin allows you to sync your WordPress Users and their data to Klaviyo
 * Version:           1.1.0
 * Author:            Oak and Beech
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       user-sync-for-klaviyo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('USER_SYNC_FOR_KLAVIYO_VERSION', '1.2.0');
define('USER_SYNC_FOR_KLAVIYO_SETTINGS', 'user_sync_for_klaviyo_settings');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-user-sync-for-klaviyo-activator.php
 */
function activate_user_sync_for_klaviyo()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-user-sync-for-klaviyo-activator.php';
	User_Sync_For_Klaviyo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-user-sync-for-klaviyo-deactivator.php
 */
function deactivate_user_sync_for_klaviyo()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-user-sync-for-klaviyo-deactivator.php';
	User_Sync_For_Klaviyo_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_user_sync_for_klaviyo');
register_deactivation_hook(__FILE__, 'deactivate_user_sync_for_klaviyo');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-user-sync-for-klaviyo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_user_sync_for_klaviyo()
{

	$plugin = new User_Sync_For_Klaviyo();
	$plugin->run();
}
run_user_sync_for_klaviyo();
