<?php
/*
 * Plugin Name:       WP Sharing Buttons
 * Plugin URI:        https://contextive.com/wp-sharing-buttons
 * Description:       Adds sharing configurable sharing buttons
 * Version:           1.0.0
 * Author:            Amit Ashckenazi
 * Author URI:        https://contextive.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-sharing-buttons
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class WP_Sharing_Buttons {

	protected $prefix = 'wp';
	protected $version = '1.0.0';

	public function __construct() {
		$this->init();
	}

	/*
	 * Init the plugin and requiring based on the use case, admin or front end
	 * */
	public function init() {
		load_plugin_textdomain( 'wp-sharing-buttons', false, basename( dirname( __FILE__ ) ) . '/languages' );
		if ( is_admin() ) {
			require_once plugin_dir_path( __FILE__ ) . '/admin/wp-sharing-buttons-admin.class.php';
			$plugin_admin = new WP_Sharing_Buttons_Admin( $this->prefix );
			$plugin_admin->init();
		} else {
			require_once plugin_dir_path( __FILE__ ) . '/public/wp-sharing-buttons-frontend.class.php';
			$plugin_public = new WP_Sharing_Buttons_Frontend( $this->prefix );
			$plugin_public->init();
		}
		$this->add_actions();
		$this->add_filters();
	}

	protected function add_actions() {

	}

	protected function add_filters() {
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_settings_link' ) );
	}

	/*
	 * Adding settings link to the plugin 'row' on the the plugins page
	 * */
	public function add_settings_link( $links ) {
		$settings_link = array( '<a href="' . admin_url( 'options-general.php?page=wp-sharing-buttons' ) . '">' .
		                        __( 'Settings', 'wp-sharing-buttons' ) . '</a>' );
		return array_merge( $links, $settings_link );
	}
}

new WP_Sharing_Buttons();
