<?php

namespace WP_Crawler\Model;

class Options {


	/**
	 * set option name
	 *
	 * @var string
	 */
	public $option = '';

	/**
	 * Set option value
	 *
	 * @var [type]
	 */
	public $value;

	/**
	 * Set option name
	 *
	 * @param  string $option
	 * @return void
	 */
	public function option( string $option ) {

		$this->option = $option;
		return $this;

	}

	public function value( $value ) {

		if ( $this->option == '' ) {
			return;
		}

		$this->value = $value;
		return $this;
	}

	/**
	 * Save crawl result
	 *
	 * @return void
	 */
	public function save() {

		if ( $this->option == '' && empty( $this->value ) ) {
			return;
		}

		return update_option( $this->option, $this->value );

	}

	public function read() {

		if ( $this->option == '' ) {
			return;
		}

		return get_option( $this->option );
	}

}
