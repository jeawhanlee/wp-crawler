<?php

/*
Plugin Name: WP-Crawler
Plugin URI: http://wordpress.org
Description: A WordPress plulgin for crawling and extracting links of homepage.
Version: 1.0
Author: Michael Lee
Author URI: http://wordpress.org
License: GPLv2
*/

defined( 'ABSPATH' ) || exit;

ob_start();

use simplehtmldom\HtmlDocument;

/**
 * include wp file system class
 */
require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

/**
 * Composer autoload
 */
require __DIR__ . '/vendor/autoload.php';

/**
 * Plugin config constants
 */
require __DIR__ . '/inc/constants.php';

/**
 * instatiate simple html dom class; object
 */
$html = new HtmlDocument();

/**
 * instatiate wp_filesystem class; object
 */
$wp_filesystem = new WP_Filesystem_Direct( null );

/**
 * initialize WP REST API
 */
$crawl = new WP_Crawler\Rest\Crawl( $html, $wp_filesystem );

add_action( 'rest_api_init', array( $crawl, 'register_routes' ) );

/**
 * Cron to crawl
 */
add_action('wpc_page_crawl', array( $crawl, 'crawl' ) );

/**
 * Assets (Styles & Scripts) for plugin
 */
$assets = array(
	/**
	 * Register Bootstrap CSS
	 */
	'bootstrap-wpc_style'  => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',

	/**
	 * Register local styling
	 */
	'wpc-style_style'      => plugins_url() . '/' . WPC_APP . '/inc/views/assets/css/wpc.style.css',

	/**
	 * Register Vuejs
	 */
	'vue-wpc_script'       => 'https://cdn.jsdelivr.net/npm/vue@2.6.14',

	/**
	* Register Axios
	*/
   'axios-wpc_script'      => 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js',

	/**
	 * Register local scripting
	 */
	'wpc-script_script'    => plugins_url() . '/' . WPC_APP . '/inc/views/assets/js/wpc.script.js',

	/**
	 * Register Bootstrap JS
	 */
	'bootstrap-wpc_script' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',
);

/**
 * Initialize setup
 */
add_action( 'plugins_loaded', array( new WP_Crawler\Setup( $assets ), 'wpc_setup' ) );
