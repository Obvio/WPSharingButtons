<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __DIR__ ) . '/admin/wp-sharing-buttons-enum.class.php';

class WP_Sharing_Buttons_Frontend {

	protected $prefix;
	protected $render_settings;

	public function __construct( $prefix ) {
		$this->prefix = $prefix;
	}

	/*
	 * Here we get the saved option so we can render the buttons according to it.
	 * */
	public function init() {
		$this->render_settings = WP_Sharing_Buttons_Enum::get_plugin_saved_options( $this->prefix );
		$this->add_actions();
		$this->add_filters();
	}

	protected function add_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/*
	 * Adding the JS in charge of executing the sharing popups.
	 * */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->prefix . '-popup', plugin_dir_url( __FILE__ ) . '/js/wp-sharing-buttons-popup.js',
			array( 'jquery' ), false, true );
	}

	/*
	 * Including both font awsome and the plugin custom/specific css.
	 * */
	public function enqueue_styles() {
		wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . '/css/font-awesome.min.css' );
		wp_enqueue_style( $this->prefix . '-style', plugin_dir_url( __FILE__ ) . '/css/wp-sharing-buttons.css' );
	}

	/*
	 * This logic add filters based the "placement" decided by the user.
	 * */
	protected function add_filters() {
		$placement = $this->render_settings[ $this->prefix . '_layout' ]['placement'];

		switch ( $placement ) {
			case 'after-title':
				add_filter( 'the_title', array( $this, 'render_sharing_buttons' ), 10, 1 );
				break;
			case 'after-content':
			case 'float-left':
				add_filter( 'the_content', array( $this, 'render_sharing_buttons' ), 10, 1 );
				break;
			case 'over-featured-image':
			case 'below-featured-image':
				add_filter( 'post_thumbnail_html', array( $this, 'render_sharing_buttons' ), 10, 1 );
				break;
			default:
				add_filter( 'the_content', array( $this, 'render_sharing_buttons' ), 10, 1 );
				break;
		}
	}

	/*
	 * The actual rendering of the buttons based on the chosen button style and its placement.
	 * This function also checks if the the current 'executed context' is in the locations white list (ie. home, single page, etc.)
	 * */
	public function render_sharing_buttons( $input ) {
		$output            = $input;
		$allowed_locations = $this->render_settings[ $this->prefix . '_locations' ];
		$allowed           = false;

		if ( is_home() || is_front_page() ) {
			$allowed = isset( $allowed_locations['home'] );
		}

		if ( is_singular() ) {
			$allowed = isset( $allowed_locations['singular'] );
		}

		if ( is_attachment() ) {
			$allowed = isset( $allowed_locations['attachment'] );
		}

		if ( is_archive() || is_category() ) {
			$allowed = isset( $allowed_locations['archive'] );
		}

		if ( in_the_loop() && $allowed ) {
			$networks_enum   = WP_Sharing_Buttons_Enum::get_social_networks();
			$active_networks = $this->render_settings[ $this->prefix . '_networks' ];
			$button_style    = $this->render_settings[ $this->prefix . '_layout' ]['button-style'];
			$placement       = $this->render_settings[ $this->prefix . '_layout' ]['placement'];

			$buttons = '';
			foreach ( $active_networks as $key => $value ) {
				$buttons .= '<a href="' . $networks_enum[ $key ]['url'] . '" class="wp-sharing-button" ' .
				            'data-name="' . $networks_enum[ $key ]['name'] . '"' .
				            'data-url="' . $networks_enum[ $key ]['url'] . '"' .
				            'data-u="' . $networks_enum[ $key ]['url_param_name'] . '"' .
				            'data-t="' . $networks_enum[ $key ]['text_param_name'] . '"' .
				            'data-width="' . $networks_enum[ $key ]['width'] . '"' .
				            'data-height="' . $networks_enum[ $key ]['height'] . '"' .
				            'data-icon="' . $networks_enum[ $key ]['icon'] . '"' .
				            '><i class="fa ' . $networks_enum[ $key ]['icon'] . '"></i>' .
				            '<span class="wp-sharing-button-label">' . $networks_enum[ $key ]['name'] . '</span></a>';
			}

			$buttons = '<div class="wp-sharing-buttons' .
			           ' wp-sharing-buttons-placement-' . $placement .
			           ' wp-sharing-buttons-style-' . $button_style . '">' . $buttons . '</div>';

			if ( $placement === 'over-featured-image' ) {
				$output = '<div class="wp-sharing-buttons-image-wrapper">' . $input . $buttons . '</div>';
			} else {
				$output = $input . $buttons;
			}
		}
		return $output;
	}
}