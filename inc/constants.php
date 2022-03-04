<?php
defined( 'ABSPATH' ) || exit;

/**
 * define plugin name
 */
define( 'WPC_APP', 'wp-crawler' );

/**
 * define plugin core path
 */
define( 'WPC_APP_PATH', plugin_dir_path( __FILE__ ) );

/**
 * define WP API Namespace
 */
define( 'API_NAMESPACE', 'wpcrawler/v1' );

/**
 * Site to crawl
 */
define( 'CRAWL_SITE', home_url() );


