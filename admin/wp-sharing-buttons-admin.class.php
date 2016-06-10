<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . '/wp-sharing-buttons-enum.class.php';

class WP_Sharing_Buttons_Admin {

	protected $options;
	protected $prefix;

	public function __construct( $prefix ) {
		$this->prefix = $prefix;
	}

	/*
	 * Initing the Admin, and setting default settings in case we don't have any.
	 * */
	public function init() {
		$this->options = WP_Sharing_Buttons_Enum::get_plugin_saved_options( $this->prefix );

		if ( false == get_option( $this->prefix . '_settings' ) ) {
			add_option( $this->prefix . '_settings',
				array(
					$this->prefix . '_networks' => array( 'facebook'  => 1, 'twitter'   => 1 ),
					$this->prefix . '_layout' => array( 'button-style'  => 'show-label-and-icon', 'placement' => 'after-title' ),
					$this->prefix . '_locations' => array( 'singular'  => 1 )
				)
			);
		}
		$this->add_actions();
	}

	protected function add_actions() {
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_screen' ) );
		add_action( 'admin_init', array( $this, 'register_options_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'include_admin_css' ) );
	}

	/*
	 * Admin custom CSS for tidying up the <table> and <select>.
	 * */
	public function include_admin_css() {
		wp_enqueue_style( $this->prefix . '-admin-style', plugin_dir_url( __FILE__ ) . '/css/wp-sharing-buttons-admin.css' );
	}

	/*
	 * This holds the plugin settings page.
	 * */
	public function add_plugin_admin_screen() {
		add_options_page( __( 'WP Sharing Buttons', 'wp-sharing-buttons' ),
			__( 'WP Sharing Buttons', 'wp-sharing-buttons' ),
			'manage_options',
			'wp-sharing-buttons',
			array( $this, 'add_settings_page' ) );
	}

	/*
	 * Registering the settings and options accroding to the options api (with sanitation)
	 * */
	public function register_options_settings() {
		register_setting(
			$this->prefix,
			$this->prefix . '_settings',
			array( $this, 'sanitize_options' )
		);

		$this->add_networks_settings_section();
		$this->add_layout_settings_section();
		$this->add_locations_settings_section();
	}

	/*
	 * Possible social networks section
	 * */
	protected function add_networks_settings_section() {
		$section_id = $this->prefix . '_networks';

		add_settings_section( $section_id, __( 'Social Networks Settings', 'wp-sharing-buttons' ),
			array( 'WP_Sharing_Buttons_Enum', 'render_section_info_for_networks' ), $this->prefix );

		foreach ( WP_Sharing_Buttons_Enum::get_social_networks() as $key => $value ) {
			add_settings_field(
				$key,
				$value['name'],
				array( $this, 'make_generic_checkbox_field' ),
				$this->prefix,
				$section_id,
				array(
					'id'      => $key,
					'section' => $section_id
				)
			);
		}
	}

	/*
	 * The "Look and Feel" of the buttons
	 * */
	protected function add_layout_settings_section() {
		$section_id = $this->prefix . '_layout';

		add_settings_section( $section_id, __( 'Layout and Visual Settings', 'wp-sharing-buttons' ),
			array( 'WP_Sharing_Buttons_Enum', 'render_section_info_for_layout' ), $this->prefix );

		foreach ( WP_Sharing_Buttons_Enum::get_layout_options() as $key => $value ) {
			add_settings_field(
				$key,
				$value['name'],
				array( $this, 'make_generic_dropdown_field' ),
				$this->prefix,
				$section_id,
				array(
					'id'      => $key,
					'section' => $section_id,
					'options' => $value['options'],
					'class'   => 'wp-layout-settings-row'
				)
			);
		}
	}

	/*
	 * Locations white listing
	 * */
	protected function add_locations_settings_section() {
		$section_id = $this->prefix . '_locations';

		add_settings_section( $section_id, __( 'Location Settings', 'wp-sharing-buttons' ),
			array( 'WP_Sharing_Buttons_Enum', 'render_section_info_for_locations' ), $this->prefix );

		foreach ( WP_Sharing_Buttons_Enum::get_site_locations() as $key => $value ) {
			add_settings_field(
				$key,
				$value['name'],
				array( $this, 'make_generic_checkbox_field' ),
				$this->prefix,
				$section_id,
				array(
					'id'      => $key,
					'section' => $section_id,
				)
			);
		}
	}

	/*
	 * This function creates a checkbox with the correct "name" for the form.
	 * */
	public function make_generic_checkbox_field( $option ) {
		$checked = isset( $this->options[ $option['section'] ][ $option['id'] ] )
			? checked( $this->options[ $option['section'] ][ $option['id'] ], 1, false ) : "";
		echo '<input type="checkbox" name="' . $this->prefix . '_settings' . '[' . $option['section'] . '][' . $option['id'] . ']" ' .
		     $checked . ' value="1">';
	}

	/*
	 * This function creates a <select> with the correct "name" for the form.
	 * */
	public function make_generic_dropdown_field( $option ) {
		$select = '<select name="' . $this->prefix . '_settings' . '[' . $option['section'] . '][' . $option['id'] . ']">';
		foreach ( $option['options'] as $key => $value ) {
			$selected = isset( $this->options[ $option['section'] ][ $option['id'] ] ) ?
				selected( $key, $this->options[ $option['section'] ] [ $option['id'] ], false ) : "";
			$select .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
		}
		$select .= '</select>';
		echo $select;
	}

	/*
	 * Sanitation for our custom option, checkboxes are sanitized as Booleans
	 * and <select> as lower case Strings with dashes/underscores.
	 * */
	public function sanitize_options( $input ) {
		if ( isset( $input[ $this->prefix . '_networks' ] ) ) {
			foreach ( $input[ $this->prefix . '_networks' ] as $key => $value ) {
				$input[ $this->prefix . '_networks' ][ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
			}
		}
		if ( isset( $input[ $this->prefix . '_layout' ] ) ) {
			foreach ( $input[ $this->prefix . '_layout' ] as $key => $value ) {
				$input[ $this->prefix . '_layout' ][ $key ] = sanitize_key( $value );
			}
		}
		if ( isset( $input[ $this->prefix . '_locations' ] ) ) {
			foreach ( $input[ $this->prefix . '_locations' ] as $key => $value ) {
				$input[ $this->prefix . '_locations' ][ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
			}
		}
		return $input;
	}

	/*
	 * This function renders the actual settings form.
	 * */
	public function add_settings_page() {
		echo '<div class="wrap">' .
		     '<h2>' . __( 'WP Sharing Buttons', 'wp-sharing-buttons' ) . '</h2>' .
		     '<form action="options.php" method="post">';
		settings_fields( $this->prefix );
		do_settings_sections( $this->prefix );
		submit_button();
		echo '</form></div>';
	}
}