<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://oakandbeech.com
 * @since      1.0.0
 *
 * @package    User_Sync_For_Klaviyo
 * @subpackage User_Sync_For_Klaviyo/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    User_Sync_For_Klaviyo
 * @subpackage User_Sync_For_Klaviyo/includes
 * @author     Oak and Beech <info@oakandbeech.com>
 */
class User_Sync_For_Klaviyo_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'user-sync-for-klaviyo',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
