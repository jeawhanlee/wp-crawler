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
    private $client = '';

    /**
     * Hold response
     *
     * @var array
     */
    private $response = array();

    public function __construct($client) {

        /**
         * Goutte Crawler
         *
         * @return void
         */
        $this->client = $client;
    }

    /**
     * Return crawl results
     *
     * @return void
     */
    public function get_crawl_result(){

        $this->response = array(
            'result' => $this->option( 'wpc_crawl_result' )->read(),
            'last_crawl' => $this->option( 'wpc_crawl_time' )->read(),
            'base' => 'http://' . CRAWL_SITE
        );

        return $this->response;

    }

    public function crawl(){

        try{
            /**
             * Set method and url to crawl
             */
            $crawler = $this->client->request( 'GET', 'http://' . CRAWL_SITE );

            /**
             * Crawl and filter anchor tags
             */
            $crawler->filter( 'a' )->each(
                function ( $node ) {
                    $this->crawl_data[] = [
                        'link' => $node->attr( 'href' ),
                        'text' => $node->text(),
                    ];
                }
            );

             /**
             * Save crawl result
             */
            $this->option( 'wpc_crawl_result' )->value( $this->crawl_data )->save();
            $this->option( 'wpc_crawl_time' )->value( date( 'Y-m-d H:i' ) )->save();

            /**
             * Set API Response
             */
            $this->response = array(
                'message' => 'Crawled Successfully'
            );
        }
        catch(Exception $e){

            /**
             * Set API Response
             */
            $this->response = array(
                'message' => 'Cannot crawl page at the moment, check your internet connection and try again'
            );
        }
        

        /**
         * Return response
         */
        return $this->response;
    }

    /**
     * Register API Routes
     *
     * @return void
     */
    public function register_routes(){

        register_rest_route( API_NAMESPACE, '/crawl', array(
        'methods' => 'GET',
        'callback' => [$this, 'crawl'],
        ) );

        /**
         * route to return crawl result
         */
        register_rest_route( API_NAMESPACE, '/get_crawl_result', array(
        'methods' => 'GET',
        'callback' => [$this, 'get_crawl_result'],
        ) );
    }

}