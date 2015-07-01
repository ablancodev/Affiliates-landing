<?php
/*
 Plugin Name: Affiliates Landing Pages
Plugin URI: http://www.eggemplo.com
Description: Transfers the affiliates parameter from page to page
Author: eggemplo
Version: 1.0
Author URI: http://www.eggemplo.com
*/

class AffiliatesLandingPlugin {

	public static function init() {
		add_action ( 'init', array( __CLASS__, 'wp_init' ) );
	}

	public static function wp_init () {
		add_shortcode ( 'affiliates_landing', array( __CLASS__, 'affiliates_landing' ) );
	}

	public static function affiliates_landing ($attr = array(), $content = null) {
		global $wpdb;

		remove_shortcode( 'affiliates_landing' );
		$content = do_shortcode( $content );
		add_shortcode( 'affiliates_landing', array( __CLASS__, 'affiliates_landing' ) );

		$output = "";
		if ( strlen( $content ) > 0 ) {
			$base_url = trim( $content );
			$base_url = str_replace( '&#038;', '&', $base_url );
			$base_url = strip_tags( $base_url );
			$base_url = preg_replace('/\r|\n/', '', $base_url );
			$base_url = trim( $base_url );
		}
		
		if ( !class_exists("Affiliates_Service" ) ) {
			require_once( AFFILIATES_CORE_LIB . '/class-affiliates-service.php' );
		}
		
		if ( isset( $_GET[get_option( 'aff_pname', AFFILIATES_PNAME )] ) ) {
			$affiliate_id = $_GET[get_option( 'aff_pname', AFFILIATES_PNAME )];
		} else if ( isset( $_POST[get_option( 'aff_pname', AFFILIATES_PNAME )] ) ) {
			$affiliate_id = $_POST[get_option( 'aff_pname', AFFILIATES_PNAME )];
		} else {
			$affiliate_id = Affiliates_Service::get_referrer_id();
		}
		$output .= affiliates_get_affiliate_url( $base_url, $affiliate_id );

		return $output;
	}
}
AffiliatesLandingPlugin::init();
