<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * This class holds an enumerable dictionary of options for the plugin.
 * */
class WP_Sharing_Buttons_Enum {

	public function __construct() {
		/*empty*/
	}

	public static function get_plugin_saved_options( $prefix ) {
		return get_option( $prefix . '_settings' );
	}

	public static function get_social_networks() {
		return array(
			'facebook'  =>
				array(
					'name'            => __( 'Facebook', 'wp-sharing-buttons' ),
					'icon'            => 'fa-facebook-official',
					'width'           => 900,
					'height'          => 500,
					'url'             => '//www.facebook.com/sharer/sharer.php',
					'url_param_name'  => 'u',
					'text_param_name' => 't'
				),
			'twitter'   =>
				array(
					'name'            => __( 'Twitter', 'wp-sharing-buttons' ),
					'icon'            => 'fa-twitter',
					'width'           => 650,
					'height'          => 360,
					'url'             => '//twitter.com/intent/tweet',
					'url_param_name'  => 'url',
					'text_param_name' => 'text'
				),
			'gplus'     =>
				array(
					'name'            => __( 'Google+', 'wp-sharing-buttons' ),
					'icon'            => 'fa-google-plus',
					'width'           => 550,
					'height'          => 500,
					'url'             => '//plus.google.com/share',
					'url_param_name'  => 'url',
					'text_param_name' => ''
				),
			'linkedin'  =>
				array(
					'name'            => __( 'LinkedIn', 'wp-sharing-buttons' ),
					'icon'            => 'fa-linkedin',
					'width'           => 550,
					'height'          => 550,
					'url'             => '//www.linkedin.com/cws/share',
					'url_param_name'  => 'url',
					'text_param_name' => 'title'
				),
			'pinterest' =>
				array(
					'name'            => __( 'Pinterest', 'wp-sharing-buttons' ),
					'icon'            => 'fa-pinterest',
					'width'           => 750,
					'height'          => 590,
					'url'             => '//pinterest.com/pin/create/button/',
					'url_param_name'  => 'url',
					'text_param_name' => 'description'
				),
			'whatsapp'  =>
				array(
					'name'            => __( 'WhatsApp', 'wp-sharing-buttons' ),
					'icon'            => 'fa-whatsapp',
					'width'           => 0,
					'height'          => 0,
					'url'             => 'whatsapp://send',
					'url_param_name'  => '',
					'text_param_name' => 'text'
				),
			'tumblr'    =>
				array(
					'name'            => __( 'Tumblr', 'wp-sharing-buttons' ),
					'icon'            => 'fa-tumblr',
					'width'           => 550,
					'height'          => 550,
					'url'             => '//www.tumblr.com/share/link',
					'url_param_name'  => 'url',
					'text_param_name' => 'name'
				),
			'reddit'    =>
				array(
					'name'            => __( 'Reddit', 'wp-sharing-buttons' ),
					'icon'            => 'fa-reddit',
					'width'           => 550,
					'height'          => 700,
					'url'             => '//www.reddit.com/submit',
					'url_param_name'  => 'url',
					'text_param_name' => 'title'
				)
		);
	}

	public static function get_layout_options() {
		return array(
			'button-style' => array(
				'name'    => __( 'Button Style', 'wp-sharing-buttons' ),
				'options' => array(
					'show-icon'           => __( 'Icon only', 'wp-sharing-buttons' ),
					'show-label'          => __( 'Label only', 'wp-sharing-buttons' ),
					'show-label-and-icon' => __( 'Icon and Label', 'wp-sharing-buttons' )
				)
			),
			'placement'    => array(
				'name'    => __( 'Placement', 'wp-sharing-buttons' ),
				'options' => array(
					'float-left'           => __( 'Float/Sticky on the Left', 'wp-sharing-buttons' ),
					'after-title'          => __( 'After the title', 'wp-sharing-buttons' ),
					'after-content'        => __( 'After the content', 'wp-sharing-buttons' ),
					'over-featured-image'  => __( 'Overlaid on featured image', 'wp-sharing-buttons' ),
					'below-featured-image' => __( 'Below featured image', 'wp-sharing-buttons' )
				)
			)
		);
	}

	public static function get_site_locations() {
		return array(
			'home'       => array( 'name' => __( 'Home page', 'wp-sharing-buttons' ) ),
			'singular'   => array( 'name' => __( 'Single post or page', 'wp-sharing-buttons' ) ),
			'attachment' => array( 'name' => __( 'Attachment page', 'wp-sharing-buttons' ) ),
			'archive'    => array( 'name' => __( 'Category/Archive', 'wp-sharing-buttons' ) )
		);
	}

	public static function render_section_info_for_networks() {
		echo '<p>' . __( 'Setup which Social networks you want to show in the sharing buttons widget.', 'wp-sharing-buttons' ) . '</p>';
	}

	public static function render_section_info_for_layout() {
		echo '<p>' . __( 'Here you can decide where to show the sharing buttons widget as well as decide on the overall layout of it.', 'wp-sharing-buttons' ) . '</p>';
	}

	public static function render_section_info_for_locations() {
		echo '<p>' . __( 'Choose where to show sharing buttons.', 'wp-sharing-buttons' ) . '</p>';
	}
}