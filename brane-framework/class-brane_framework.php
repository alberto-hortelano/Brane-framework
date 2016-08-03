<?php
/**
 * Avoid direct access
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Brane function
 *
 * Function to return Brane_Framework instance in all the theme
 */
function brane( $args = array() ) {

	static $brane = null;

	if ( is_null( $brane ) ) {

		remove_action( 'after_setup_theme', 'brane', 9 );

		$brane = new Brane_Framework( $args );

	}

	return $brane;
}

/**
 * Ensure brane is initialized
 *
 * This action is removed by brane function to avoid call in case brane instance is created before after_setup_theme,
 * like in functions.php with parameters to extend framework classes
 */
add_action( 'after_setup_theme', 'brane', 9, 0 );

/**
 * Brane_Framework main class Definition
 *
 * Related to Framework Functionality
 *
 * @package   Brane_Framework
 * @version   1.0.0
 * @author    Branedesign
 */
class Brane_Framework {
	/**
	 * Framework Classes.  
	 *
	 * Classes instantiated by the framework, if link is true, the object is stored in this array.
	 *
	 * @access 	private
	 * @var   	integer 
	 */
	public $auxi_classes = array(
		'static' => array( 
		'name' => 'Brane_Autocalls',
		),
		'node' => array( 
			'name' => 'Brane_Node',
			'link' => true
		),
		'tfunc' => array(
			'name' => 'Brane_Template_Functions',
			'link' => true
		),
		'btp' => array(
			'name' => 'Brane_Theme_Panel',
			'link' => true
		),
		'bsi' => array(
			'name' => 'Brane_System_Info',
			'link' => true
		),
		'options_settings' => array(
			'name' => 'Brane_Options_Settings',
			'link' => true
		),
		'options_filters' => array(
			'name' => 'Brane_Options_Filters',
		),
		'dynamic_style' => array(
			'name' => 'Brane_Dynamic_Style',
		)	
	);

	/**
	 * Config 
	 *
	 * Configuration array, by default is set by theme_customs/theme_config.php
	 */
	public $config = array();

	/**
	 * Branejs 
	 *
	 * This variable is accessible from brane.js as brane
	 */
	public $branejs = array();

	/**
	 * Ajax
	 *
	 * Is set to true if in an ajax request
	 */
	public $ajax = false;

	/**
	 * Constructor method.  
	 *
	 * This method adds other methods of the class to specific hooks within WordPress.
	 * It controls the load order of the required files for running the framework.
	 *
	 * @since  	2.0.0
	 * @access 	public
	 * @return 	void
	 */
	function __construct( $auxi_classes = array() ) {

		/**
		 * Template directory as constant
		 */
		if ( ! defined( 'BRANE_TEMPLATE_DIR' ) ) {
			define( 'BRANE_TEMPLATE_DIR', trailingslashit( get_template_directory() ) );
		}

		/**
		 * Template directory as constant
		 */
		if ( ! defined( 'BRANE_TEMPLATE_URI' ) ) {
			define( 'BRANE_TEMPLATE_URI', trailingslashit( get_template_directory_uri() ) );
		}

		/**
		 * Template directory as constant
		 */
		if ( ! defined( 'BRANE_UPLOAD_DIR' ) ) {

			$wp_up_dir = wp_upload_dir();
			define( 
				'BRANE_UPLOAD_DIR', 
				trailingslashit( 
					$wp_up_dir['basedir'] 
				) 
			);

		}

		/**
		 * Template directory as constant
		 */
		if ( ! defined( 'BRANE_UPLOAD_URI' ) ) {

			$wp_up_dir = wp_upload_dir();
			define( 
				'BRANE_UPLOAD_URI', 
				trailingslashit( 
					$wp_up_dir['baseurl'] 
				) 
			);

		}

		$this->config = require_once BRANE_TEMPLATE_DIR . 'theme_customs/bazar_theme_config.php';

		/**
		 * Basic Functions
		 *
		 * Auxiliary functions for the Theme and framework
		 */
		require_once BRANE_TEMPLATE_DIR . 'framework/functions/class-brane_autocalls.php';

		/**
		 * Theme Starter Panel
		 *
		 * Starting Theme Screen and Info
		 */
		require_once BRANE_TEMPLATE_DIR . 'framework/admin/starting_panel/class-brane_theme_panel.php';

		/**
		 * Brane System Info
		 *
		 * Generates System Info Reports
		 */
		require_once BRANE_TEMPLATE_DIR . 'framework/admin/starting_panel/class-brane_system_info.php';


		/**
		 * Template Functions
		 *
		 * Print HTML
		 */
		require_once BRANE_TEMPLATE_DIR . 'framework/functions/class-brane_template_functions.php';

		/**
		 * Brane_Node
		 *
		 * Auxiliary functions for the Theme and framework
		 */
		require_once BRANE_TEMPLATE_DIR . 'framework/class-brane_node.php';

		/**
		 * Theme Options manager
		 *
		 * Loads required files for options handling and class Brane_Theme_Options
		 */
		require_once BRANE_TEMPLATE_DIR . 'framework/theme_options/options_manager.php';

		/**
		 * Dynamic Style
		 *
		 * Prints styles from theme options.
		 */
		require_once BRANE_TEMPLATE_DIR . 'css/css/bazar_dynamic_style.php';

		/**
		 * Mobile Detect
		 *
		 * Plugin Class to detect devices
		 */
		require_once BRANE_TEMPLATE_DIR . 'framework/assets/mobile-detect.php';

		/**
		 * Plugins Compatibility
		 *
		 * Configuration files for Plugins that need specific features
		 */		
		/**
		 * Woocommerce
		 */
		if ( class_exists('Woocommerce') ) { 
			include_once BRANE_TEMPLATE_DIR . 'theme_customs/class-brane_wc_config.php';
		}
		
		/**
		 * Theme extend classes
		 */
		foreach ( $auxi_classes as $alias => $auxi_class ) {

			$this->auxi_classes[ $alias ] = $auxi_class;

			if( ! isset( $auxi_class[ 'no_file' ] ) || ! $auxi_class[ 'no_file' ] ){ 
				require_once BRANE_TEMPLATE_DIR . 'theme_customs/class-'.strtolower($auxi_class[ 'name' ]).'.php';
			}
		}

		$this->run();

	}
	
	/**
	 * Run method.  
	 *
	 * This method adds other methods of the class to specific hooks within WordPress.
	 * It controls the load order of the required files for running the framework.
	 *
	 * @access 	public
	 * @return 	void
	 */
	function run() {
		

		foreach ( $this->auxi_classes as $alias => $auxi_class ) {
			
			if ( isset( $auxi_class[ 'link' ] ) && $auxi_class[ 'link' ] == true ) {

				if( isset( $auxi_class[ 'args' ] ) ){

					$this->$alias = new $auxi_class[ 'name' ]( $auxi_class[ 'args' ] );

				}else{

					$this->$alias = new $auxi_class[ 'name' ];
				}

			}else{

				if( isset( $auxi_class[ 'args' ] ) ){

					new $auxi_class[ 'name' ]( $auxi_class[ 'args' ] );

				}else{

					new $auxi_class[ 'name' ];
				}
			}
		}

		/**
		 * Localized variable for brane.js
		 *
		 */
		$this->branejs[ 'ajaxurl' ] = admin_url( 'admin-ajax.php' );
		
		/**
		 * AJAX
		 */
		
		$this->ajax = ( isset( $_GET[ 'brane_ajax' ] ) )? true: false;
	}

	/**
	 * Ajax callback example
	 */
	public function ajax(  ) {

		echo 'AJAX';

		die('');
	}

	// =====================================================================>
	// 		HELPERS
	// =====================================================================>
	/**
	 * Custom error log
	 *
	 * @access 	public
	 * @uses debug_backtrace(), gettype(), str_replace(), error_log()
	 * @param $var, $comment, $extense
	 * @return 	void 
	 */
	public static function log( $var, $comment = '', $extense = true ){

		if( $extense ) {

			$debug_backtrace = debug_backtrace();

			$type = gettype( $var );

			$file = str_replace( BRANE_TEMPLATE_DIR, '', $debug_backtrace[0]['file'] );

			if ( $type == 'boolean' ) {
				$var = ( $var ) ? 'true' : 'false';
			}

			error_log( $file.' - ' . $debug_backtrace[0]['line'] . '
				- '.$comment.' - '.$type.': '.print_r( $var, true ).'
		---------------------------------------------------------------' );

		}else{
			error_log( print_r( $var, true ) . '-' . $comment );
		}
	}
	
	/**
	 * Get theme options 
	 *
	 * Can be overwriten in case we change options framework
	 *
	 * @access 	public
	 * @uses ot_get_option()
	 * @param $option
	 * @return string 
	 */
	public function get_option( $option ){

		if( !class_exists('OT_Loader') ){
			return;
		}

		return ot_get_option( $option );

	}

	/**
	 * Get template part 
	 *
	 * WordPress get_template_part whith a filter for $slug and a log warning in case no template found
	 *
	 * @access 	public
	 * @uses apply_filters(), do_action(), locate_template(), locate_template(), log()
	 * @param $slug, $name
	 * @return void 
	 */
	public function get_template_part( $slug, $name = null ) {

		$slug = apply_filters( 'brane_get_template_part', 'theme_customs/templates/' . $slug, $name );

        do_action( "get_template_part_{$slug}", $slug, $name );

        $templates = array();
        $name = (string) $name;
        if ( '' !== $name ){
            $templates[] = "{$slug}-{$name}.php";
        }
	
        $templates[] = "{$slug}.php";
	
        $template = locate_template( $templates, true, false );

        if( $template == '' ) $this->log( "BRANE_WARNING: can't find template: ".$slug.'-'.$name );
	}

	// =====================================================================>
	// 		Related to Pages Basic Building / Functionality
	// =====================================================================>

	/**
	 * Post Ancestor
	 *
	 * Auxiliar method for Breadcrums, find single post / page ancestor
	 *
	 * @access public
	 * @uses get_post_ancestors(), get_page_link(), get_the_title()
	 * @return string
	 */
	public function ancestor(){

		global $post;
		
		$ancestors = array_reverse( get_post_ancestors( $post->ID ) );

		$current_ancestors = array();
		
		foreach ( $ancestors as $ancestor_id ) {

			$page_link = get_page_link( $ancestor_id );

			// If there was an error, continue to the next ancestor.
		    if ( is_wp_error( $page_link ) ) {
		        continue;
	    	}
			
			$current_ancestors[ get_the_title( $ancestor_id ) ] = esc_url( $page_link );
		}

		return $current_ancestors;
	}

	/**
	 * Get Categories
	 *
	 * Auxiliar function to get Categories from a post
	 *
	 * @access public
	 * @uses get_post_type(), get_the_category(), get_the_terms(), get_term_link(), is_wp_error() 
	 * @param $postID
	 * @return string
	 */
	public function get_categories( $postID = '' ){

		global $post;
		
		$categories = array();

		$cats = '';

		$post_ID = $post->ID;
		
		if( $postID != ''){
			$post_ID = $postID;
		}

		if( get_post_type( $post_ID ) == 'post' ){

			$cats = get_the_category( $post_ID );

		}else{

			$cats = get_the_terms( $post_ID, get_post_type( $post_ID ) . '_cat' );

		}

		if( $cats != '' ){
			
			foreach ( $cats as $cat ) {
				
				// The $term is an object, so we don't need to specify the $taxonomy.
				$cat_link = get_term_link( $cat );

				// If there was an error, continue to the next term.
			    if ( is_wp_error( $cat_link ) ) {
			        continue;
		    	}

				$categories[ $cat->name ] = esc_url( $cat_link );
			}			

		}

		return $categories;

	}

	/**
	 * Get Categories List
	 *
	 * Auxiliar function to get all Categories
	 *
	 * @access public
	 * @uses  
	 * @return string
	 */
	public function get_categories_list(){

		$cats = get_categories(); 

		$html = '';

		if( $cats != '' ){
			
			foreach ( $cats as $cat ) {
				
				// The $term is an object, so we don't need to specify the $taxonomy.
				$cat_link = get_term_link( $cat );

				// If there was an error, continue to the next term.
			    if ( is_wp_error( $cat_link ) ) {
			        continue;
		    	}

				$categories[ $cat->name ] = esc_url( $cat_link );

			}			

			return $categories;
		}		

		return false;
	}

	/**
	 * Get Category Parents
	 *
	 * Auxiliar function to get Category Parents
	 *
	 * @access public
	 * @param $type
	 * @uses get_post_type(), get_the_category(), get_the_terms(), get_term_link(), is_wp_error() 
	 * @return string
	 */
	public function get_category_parents( $id = false, &$category_parents = array() ){

		$current = false;
		
		if( $id == false ) {
			
			$id = get_cat_ID( single_cat_title( '', false ) );

			$current = true;
			
		}
		
		$parent = get_term( $id, get_query_var( 'taxonomy' ) );
       
        if ( is_wp_error( $parent ) ) {
			return $parent;
        }

        if ( $parent->parent && ( $parent->parent != $parent->term_id )  ) {

            $category_parents[ $parent->slug ] = $this->get_category_parents( $parent->parent, $category_parents );

        }

        $category_parents[ $parent->slug ] = esc_url( get_category_link( $parent->term_id ) );

        return $category_parents;
	}
	
	/**
	 * Custom Post Types on Date Archives
	 *
	 * Allows Date Archives to show Custom Post types 
	 *
	 * @access public
	 * @param $query
	 * @uses is_main_query(), is_date()
	 * @return void
	 */
	public function date_archives_custompt( $query ){

		if ( class_exists('Brane_Post_Type') ) {
			
			if( $query->is_main_query() && $query->is_date() ){

	        	$query->set( 'post_type', array( array_keys( Brane_Post_Type::$post_types ) ) );

			}
		}
	}
	
	// =====================================================================>
	// 		Related to Theme Style
	// =====================================================================>

	/**
	 * Css Image Url
	 *
	 * Prevents empty urls on background images
	 *
	 * @access public
	 * @param $url
	 * @return string
	 */
	public function css_image_url( $url ) {

		$html = 'none';

		if( is_string( $url ) && strlen( $url ) ) {

			$url = esc_url( $url );
			
			$html = 'url('.$url.')';
		}
		return $html;
	}

	/**
 	 * Get config
	 *
	 * Returns specified element from $config array, if not found returns default value and logs a brane warning.
	 *
	 * @access public
	 * @param $element, $default
	 * @uses log()
	 * @return mixed
	 */
	public function get_config( $element = 'all', $default = false ) {

		if( is_string( $element ) && $element != 'all' && isset( $this->config[$element] ) ) {

			return $this->config[$element];

		}elseif( $element == 'all' ) {

			return $this->config;

		}

		$this->log( "BRANE_WARNING: can't find configuration: ". $element );

		return $default;
	}
}
