<?php

namespace WP_Crawler;

class Setup {

	/**
	 * Crawl Object
	 *
	 * @var [type]
	 */
	private $wpc_crawl;

	/**
	 * plugin assets ( Styles and Scripts)
	 *
	 * @var array
	 */
	private $assets = array();

	/**
	 * pass crawl object
	 *
	 * @param [type] $wpc_crawl
	 */
	public function __construct( $wpc_crawl ) {

		$this->wpc_crawl = $wpc_crawl;

	}

	/**
	 * Require plugin cdn styles and scripts
	 *
	 * @return void
	 */
	public function load_assets() {
		/**
		 * Assets (Styles & Scripts) for plugin
		 */
		$this->assets = array(
			/**
			 * Register Bootstrap CSS
			 */
			'bootstrap-wpc_style'  => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',

			/**
			 * Register local styling
			 */
			'wpc-style_style'      => plugins_url() . '/' . WPC_APP . '/inc/Views/assets/css/wpc.style.css',

			/**
			 * Register Vuejs
			 */
			'vue-wpc_script'       => 'https://cdn.jsdelivr.net/npm/vue@2.6.14',

			/**
			* Register Axios
			*/
			'axios-wpc_script'     => 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js',

			/**
			 * Register local scripting
			 */
			'wpc-script_script'    => plugins_url() . '/' . WPC_APP . '/inc/Views/assets/js/wpc.script.js',

			/**
			 * Register Bootstrap JS
			 */
			'bootstrap-wpc_script' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',
		);

		$this->wpc_enqueue_assets();
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
		 * Load assets
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );

		/**
		 * add plugin menu
		 */
		add_action( 'admin_menu', array( $this, 'create_menu' ) );

		/**
		 * register CRAWLER routes
		 */
		add_action( 'rest_api_init', array( $this->wpc_crawl, 'register_routes' ) );

		/**
		 * Cron to crawl
		 */
		add_action( 'wpc_page_crawl', array( $this->wpc_crawl, 'crawl' ) );

		/**
		 * Set cron to crawl
		 */
		// verify event has not been scheduled
		if ( ! wp_next_scheduled( 'wpc_page_crawl' ) ) {

			// schedule the event to run hourly
			wp_schedule_event( time(), 'hourly', 'wpc_page_crawl' );

		}
	}

	/**
	 * Enqueue assets (Styles & Scripts)
	 *
	 * @param array $assets
	 * @return void
	 */
	private function wpc_enqueue_assets() {
		/**
		 * Loop through the assets array
		 */
		foreach ( $this->assets as $id => $src ) {

			/**
			* Register assets
			*/
			$asset_type = explode( '_', $id )[1];

			if ( $asset_type == 'style' ) {
				/**
				 * enqueue styles
				 */
				wp_enqueue_style( $id, $src );
			} elseif ( $asset_type == 'script' ) {
				/**
				 * enqueue scripts at the footer
				 */
				wp_enqueue_script( $id, $src, null, null, true );
			}
		}
	}
}
