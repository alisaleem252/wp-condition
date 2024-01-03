<?php
/*
Plugin Name: WordPress Site Condition
Plugin URI: https://gigsix.com
Description: Display WP-Condition in Chart for Database Performance, Memory Performance, Site Performance, and Social Performance. Requires PHP 5.2.0+
Version: 4.0.0
Author: alisaleem252
Author URI: http://thesetemplates.info
*/

	defined( 'ABSPATH' ) || exit;
	define('wpcondi_ABSPATH', dirname(__FILE__) );
	define('wpcondi_URL', plugin_dir_url( __FILE__ ) );
	define('wpcondi_serviceURL', 'https://alisaleem252.com/#hire-me' );

	require_once(wpcondi_ABSPATH.'/includes/helper.php');
	require_once(wpcondi_ABSPATH.'/includes/class.WP_Page_Condition_Stats.php');

$WP_Page_Condition_Stats = new WP_Page_Condition_Stats();