<?php

namespace WP_Crawler;

class Setup {


	/**
	 * Require plugin cdn styles and scripts
	 *
	 * @return void
	 */
	public function __construct( $assets ) {

		/**
		 * Loop through the assets array
		 */
		foreach ( $assets as $id => $src ) {

			 /**
			 * Register assets
			 */
			$asset_type = explode( '_', $id )[1];

			if ( $asset_type == 'style' ) {
				/**
				 * Register styles
				 */
				wp_register_style( $id, $src );
				wp_enqueue_style( $id );
			} elseif ( $asset_type == 'script' ) {
				/**
				 * Register scripts
				 */
				wp_register_script( $id, $src, null, null, true );
				wp_enqueue_script( $id );
			}
		}

	}

	/**
	 * add plugin section to menu
	 *
	 * @return void
	 */
	public function create_menu() {
		return add_menu_page(
			'WP-Crawler',
			'WP Crawler',
			'manage_options',
			'wp_crawler',
			array( $this, 'render' )
		);
	}

	/**
	 * render plugin frontend
	 *
	 * @return void
	 */
	public function render() {

		/**
		 * require view display
		 */
		include_once WPC_APP_PATH . 'Views/display.php';

	}

	/**
	 * plugin gateway
	 *
	 * @return void
	 */
	public function wpc_setup() {

		/**
		 * add plugin menu
		 */
		add_action( 'admin_menu', array( $this, 'create_menu' ) );

        /**
         * Set cron to crawl
         */
        //verify event has not been scheduled
        if ( !wp_next_scheduled( 'wpc_page_crawl' ) ) {
        
            //schedule the event to run hourly
            wp_schedule_event( time(), 'hourly', 'wpc_page_crawl' );
            
        }
	}
}
