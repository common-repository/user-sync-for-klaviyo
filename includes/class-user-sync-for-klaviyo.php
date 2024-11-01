<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://oakandbeech.com
 * @since      1.0.0
 *
 * @package    User_Sync_For_Klaviyo
 * @subpackage User_Sync_For_Klaviyo/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    User_Sync_For_Klaviyo
 * @subpackage User_Sync_For_Klaviyo/includes
 * @author     Oak and Beech <info@oakandbeech.com>
 */
class User_Sync_For_Klaviyo {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      User_Sync_For_Klaviyo_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'USER_SYNC_FOR_KLAVIYO_VERSION' ) ) {
			$this->version = USER_SYNC_FOR_KLAVIYO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'user-sync-for-klaviyo';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - User_Sync_For_Klaviyo_Loader. Orchestrates the hooks of the plugin.
	 * - User_Sync_For_Klaviyo_i18n. Defines internationalization functionality.
	 * - User_Sync_For_Klaviyo_Admin. Defines all hooks for the admin area.
	 * - User_Sync_For_Klaviyo_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-user-sync-for-klaviyo-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-user-sync-for-klaviyo-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-user-sync-for-klaviyo-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-user-sync-for-klaviyo-public.php';

		$this->loader = new User_Sync_For_Klaviyo_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the User_Sync_For_Klaviyo_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new User_Sync_For_Klaviyo_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new User_Sync_For_Klaviyo_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action('admin_menu', $plugin_admin, 'register_admin_menu');
		$this->loader->add_action('admin_init', $plugin_admin, 'settings_init');
		$this->loader->add_filter('plugin_action_links_'.$this->plugin_name.'/user-sync-for-klaviyo.php', $plugin_admin, 'plugin_action_links');

		if($this->check_if_plugin_settings_are_valid() && $this->check_if_user_sync_enabled()){
			$this->loader->add_action('profile_update',$plugin_admin,'update_klaviyo_profile');
			$this->loader->add_action('user_register',$plugin_admin,'create_klaviyo_profile');
			$this->loader->add_action('wp_ajax_sync_all_users',$plugin_admin,'ajax_sync_all_users');
			// custom publicly callable actions, accepts the user id as an arguement
			$this->loader->add_action('usfk_manually_call_create_profile', $plugin_admin, 'create_klaviyo_profile');
			$this->loader->add_action('usfk_manually_call_update_profile', $plugin_admin, 'update_klaviyo_profile');
		}
	}

	
	private function check_if_plugin_settings_are_valid() {
		$options = get_option(USER_SYNC_FOR_KLAVIYO_SETTINGS );
		if (
			isset($options['klaviyo_public_key']) && $options['klaviyo_public_key'] != "" &&
			isset($options['klaviyo_private_key']) && $options['klaviyo_private_key'] != ""
		) {
			return true;
		} else {
			return false;
		}
	}

	private function check_if_user_sync_enabled() {
		$options = get_option(USER_SYNC_FOR_KLAVIYO_SETTINGS);
		if(isset($options['activate_user_sync']) && $options['activate_user_sync'] == 'on')
		{
			return true;
		}
		return false;
	}

	private function should_inject_klaviyo_script()
	{
		$options = get_option( USER_SYNC_FOR_KLAVIYO_SETTINGS);
		$inject_setting = false;
		if(isset($options['inject_klaviyo_script']) && $options['inject_klaviyo_script'] == 'on'){
			$inject_setting = true;
		}
		if($this->check_if_plugin_settings_are_valid() && $inject_setting){
			return true;
		}
		return false;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		if($this->should_inject_klaviyo_script()){
			$options = get_option( USER_SYNC_FOR_KLAVIYO_SETTINGS );
			$plugin_public = new User_Sync_For_Klaviyo_Public( $this->get_plugin_name(), $this->get_version(), $options['klaviyo_public_key'] );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    User_Sync_For_Klaviyo_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
