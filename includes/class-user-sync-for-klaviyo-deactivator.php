<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://oakandbeech.com
 * @since      1.0.0
 *
 * @package    User_Sync_For_Klaviyo
 * @subpackage User_Sync_For_Klaviyo/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    User_Sync_For_Klaviyo
 * @subpackage User_Sync_For_Klaviyo/includes
 * @author     Oak and Beech <info@oakandbeech.com>
 */
class User_Sync_For_Klaviyo_Deactivator {

	/**
	 * Clean up and delete our options from the database
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// only clean up is to delete the options
		delete_option(USER_SYNC_FOR_KLAVIYO_SETTINGS);
	}

}
