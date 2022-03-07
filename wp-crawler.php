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
require_once ( ABSPATH . '/wp-admin/includes/file.php' );

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
$wpc_html = new HtmlDocument();

/**
 * init options
 */
$wpc_options = new WP_Crawler\Model\Options;

/**
 * initialize WP REST API
 */
$wpc_crawl = new WP_Crawler\Rest\Crawl( $wpc_html, $wpc_options );

/**
 * Initialize setup
 */
add_action( 'plugins_loaded', array( new WP_Crawler\Setup( $wpc_crawl ), 'wpc_setup' ) );
