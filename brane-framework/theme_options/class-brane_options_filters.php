<?php
/**
 * Brane_Options_Filters class definition
 *
 * @package   Brane_Framework
 * @version   1.0.0
 * @author    Branedesign
 */

/**
 * Avoid direct access
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Brane_Options_Filters {

	public function __construct(){
		
		// To change the logo link inside the header of OptionTree.
		add_filter( 'ot_header_logo_link', array( $this, 'header_logo_link' ) );
		// To change the version text inside the header of OptionTree.
		add_filter( 'ot_header_version_text', array( $this, 'header_version_text' ) );
		// To disable add new layout on header options
		add_filter( 'ot_show_new_layout', '__return_false' );
	}

	/**
	 * Option Tree Panel Logo Link
	 *
	 * To change the logo link inside the header of OptionTree.
	 *
	 * @access public
	 */
	public function header_logo_link() {
		return '';
	}

	/**
	 * Option Tree Panel Version Text
	 *
	 * To change the version text inside the header of OptionTree.
	 *
	 * @access public
	 */
	public function header_version_text() {

		return esc_html( wp_get_theme() . ' ' . esc_html__( 'Options','brane_lang') );
	}

}