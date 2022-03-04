<?php

namespace WP_Crawler\Rest;

class Crawl extends \WP_Crawler\Model\Options {


	/**
	 * hold crawl data
	 *
	 * @var array
	 */
	private $crawl_data = array();

	/**
	 * crawler client
	 *
	 * @var [type]
	 */
	private $html;

    /**
     * wp_file system
     *
     * @var [type]
     */
    private $wp_files;

    /**
     * sitemap html
     *
     * @var string
     */
    private $sitemap_html = '';

	/**
	 * Hold response
	 *
	 * @var array
	 */
	private $response = array();

	public function __construct( $html, $wp_files ) {

		/**
		 * simple html dom parser
		 */
		$this->html = $html;

        /**
         * Wp_file system
         */
        $this->wp_files = $wp_files;
	}

	/**
	 * Return crawl results
	 *
	 * @return void
	 */
	public function get_crawl_result() {

		$this->response = array(
			'result'     => $this->option( 'wpc_crawl_result' )->read(),
			'last_crawl' => $this->option( 'wpc_crawl_time' )->read(),
			'base'       => 'http://' . CRAWL_SITE,
		);

		return $this->response;

	}

	public function crawl() {
      
		try {
			/**
			 * Set url content to crawl
			 */
			$response = wp_remote_get( CRAWL_SITE );

			/**
			 * filter anchor tags from html content
			 */

            if ( !is_array( $response ) && is_wp_error( $response ) ) {
                throw new \Exception('Unable to crawl page at the moment, please check your connection and try again.');
            }

            $this->html->load($response['body']);

            foreach($this->html->find('a') as $element){

                $link_text = $element->plaintext == '' ? $element->href : $element->plaintext;

                $this->crawl_data[] = array(
                    			'link' => $element->href,
                    			'text' => $link_text,
                    		);

                $this->sitemap_html .= '<a href="' . $element->href . '" class="list-group-item list-group-item-action" target="_blank">' . $link_text . '</a>';
            }

			 /**
			 * Save crawl result
			 */
			$this->option( 'wpc_crawl_result' )->value( $this->crawl_data )->save();
			$this->option( 'wpc_crawl_time' )->value( date( 'Y-m-d H:i' ) )->save();

            /**
             * create homepage html
             */

            $this->create_file( WPC_APP_PATH . 'Views/gen/home_gen.html', $response['body'] );

            /**
             * Generate sitemap from sitemap template
             */
            $template = $this->read_file( WPC_APP_PATH . 'Template/sitemap.html' );

		    $template = str_replace( '{content}', $this->sitemap_html, $template );

            $this->create_file( WPC_APP_PATH . 'Views/gen/sitemap_gen.html', $template );

			/**
			 * Set API Response
			 */
			$this->response = array(
				'message' => 'Crawled Successfully',
			);
		} catch ( \Exception $e ) {

			/**
			 * Set API Response
			 */
			$this->response = array(
				'message' => $e->getMessage()
			);
		}

		/**
		 * Return response
		 */
		return $this->response;
	}

    /**
     * Create new file using the wp_filesystem;
     *
     * @param string $file
     * @param string $file_content
     * @return void
     */
    public function create_file( string $file, string $file_content ) {

        $chmod = defined('FS_CHMOD_FILE') ? FS_CHMOD_FILE : 0644;

        return $this->wp_files->put_contents( $file, $file_content, $chmod );

    }

    /**
     * Read file content using the wp_filesystem;
     *
     * @param string $file
     * @return void
     */
    public function read_file( string $file ) {

        return $this->wp_files->get_contents( $file );

    }

	/**
	 * Register API Routes
	 *
	 * @return void
	 */
	public function register_routes() {
        /**
		 * route to crawl page
		 */
		register_rest_route(
			API_NAMESPACE,
			'/crawl',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'crawl' ),
			)
		);

		/**
		 * route to return crawl result
		 */
		register_rest_route(
			API_NAMESPACE,
			'/get_crawl_result',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_crawl_result' ),
			)
		);
	}

}
