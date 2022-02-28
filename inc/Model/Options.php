<?php

namespace WP_Crawler\Model;

class Options {


	/**
	 * set option name
	 *
	 * @var string
	 */
	protected $option = '';

	/**
	 * Set option value
	 *
	 * @var [type]
	 */
	protected $value;

	/**
	 * Set option name
	 *
	 * @param  string $option
	 * @return void
	 */
	protected function option( string $option ) {

		$this->option = $option;
		return $this;

	}

	protected function value( mixed $value ) {

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
	protected function save() {

		if ( $this->option == '' && empty( $this->value ) ) {
			return;
		}

		return update_option( $this->option, $this->value );

	}

	protected function read() {

		if ( $this->option == '' ) {
			return;
		}

		return get_option( $this->option );
	}

}
