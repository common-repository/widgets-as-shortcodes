<?php
/**
Plugin Name: Widgets as Shortcodes
Plugin URI: http://OTWthemes.com
Description:  Use WordPress default widgets as shortcodes. Nice and easy interface. Insert anywhere in your site - page/post editor, sidebars, template files.
Author: OTWthemes.com
Version: 1.9

Author URI: http://themeforest.net/user/OTWthemes
*/

load_plugin_textdomain('otw_wssw',false,dirname(plugin_basename(__FILE__)) . '/languages/');

load_plugin_textdomain('otw-shortcode-widget',false,dirname(plugin_basename(__FILE__)) . '/languages/');

$wp_wssw_tmc_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_wssw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_wssw' ) )
);

$wp_wssw_agm_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_wssw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_wssw' ) )
);

$wp_wssw_cs_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_wssw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_wssw' ) )
);

$otw_wssw_plugin_id = '82c66a40c8bd9e23dda1f92425425ba7';
$otw_wssw_plugin_url = plugin_dir_url( __FILE__);
$otw_wssw_css_version = '1.8';
$otw_wssw_js_version = '1.8';

$otw_wssw_plugin_options = get_option( 'otw_wssw_plugin_options' );

//include functons
require_once( plugin_dir_path( __FILE__ ).'/include/otw_wssw_functions.php' );

//otw components
$otw_wssw_shortcode_component = false;
$otw_wssw_form_component = false;
$otw_wssw_validator_component = false;

//load core component functions
@include_once( 'include/otw_components/otw_functions/otw_functions.php' );

if( !function_exists( 'otw_register_component' ) ){
	wp_die( 'Please include otw components' );
}

//register form component
otw_register_component( 'otw_form', dirname( __FILE__ ).'/include/otw_components/otw_form/', $otw_wssw_plugin_url.'include/otw_components/otw_form/' );

//register validator component
otw_register_component( 'otw_validator', dirname( __FILE__ ).'/include/otw_components/otw_validator/', $otw_wssw_plugin_url.'include/otw_components/otw_validator/' );

//register shortcode component
otw_register_component( 'otw_shortcode', dirname( __FILE__ ).'/include/otw_components/otw_shortcode/', $otw_wssw_plugin_url.'include/otw_components/otw_shortcode/' );

/** 
 *call init plugin function
 */
add_action('init', 'otw_wssw_init' );
?>