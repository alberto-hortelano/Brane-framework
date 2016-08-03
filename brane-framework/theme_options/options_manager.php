<?php
/**
 * Manages Framework options
 *
 * Requires necessary options panel files and instance their classes
 * 
 * @package Brane_Framework
 * @since 1.0.0
 * @version 2.0.0
 * @category Core
 * @author Branedesign
 */

/**
 * Avoid direct access
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Options Settings
 *
 * Creates Menu options panel and Loads and updates theme options array
 */
require_once( BRANE_TEMPLATE_DIR . 'framework/theme_options/class-brane_options_settings.php' );

/**
 * Options Filters
 *
 * Modifies default OT inputs for specific options
 */
require_once( BRANE_TEMPLATE_DIR . 'framework/theme_options/class-brane_options_filters.php' );
