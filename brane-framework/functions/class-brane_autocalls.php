<?php
/**
 * Basic Functions and Definitions
 * 
 * @package Brane_Framework
 * @since 1.0.0
 */

/**
 * Avoid direct access
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Brane_Basic class
 *
 * The Brane_Basic class launches the framework.
 *
 * @since  1.0.0
 * @access public
 */
class Brane_Autocalls {

	public $images;

	public $widgets_to_register;

	function __construct(){

		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'wp_print_footer_scripts' ) );
		
		// Filter to print title on static pages
		add_filter( 'wp_title', array( $this, 'wpdocs_hack_wp_title_for_home') );

		// Remove emojis
		$this->remove_emojis();

		// Remove Query String
		$this->remove_query_string();

		// Add Sidebar Generator to Widget Page		
		if ( is_admin() ) {
			add_action( 'widgets_admin_page', array( $this, 'widget_page_content' ) );
		}

		
	}

	// =====================================================================>
	// 		ACTION GROUPS
	// =====================================================================>

	/**
	 * Functions hooked to after_setup_theme
	 *
	 * @access public
	 * @uses load_theme_textdomain(), register_nav_menus(), brane()->get_config(), set_content_width(), image_sizes(), theme_support()
	 * @return void
	 */
	public function after_setup_theme() {

		// Loads text domain unique for this theme
		load_theme_textdomain( 'brane_lang', BRANE_TEMPLATE_DIR . 'lang' );

		// Register menus
		register_nav_menus( brane()->get_config( 'nav_menus', array() ) );

		// Sets default content width
		$this->set_content_width();

		// Loads custom image sizes
		$this->image_sizes();

		// Adds theme support
		$this->theme_support();
	}


	/**
	 * Adds HTML Sidebars Generator to the widgets page.
	 *
	 * @access public
	 * @uses widget_page_content()
	 * @return void
	 */
	public function widget_page_content() {

		include BRANE_TEMPLATE_DIR . '/framework/admin/widgets.php';

	}

	/**
	 * Functions hooked to widgets_init
	 *
	 * @access public
	 * @uses register_widget_areas()
	 * @return void
	 */
	public function widgets_init() {

		// Register Widget Areas
		$this->register_widget_areas(); 
	}

	/**
 	 * Init general functionality
	 *
	 * Functions hooked to init
	 *
	 * @access public
	 * @return void
	 */
	public function init() {

		// Add here functions hooked to init
	}

	/**
 	 * Enqueue Scripts
	 *
	 * Enqueue scripts files
	 *
	 * @access public
	 * @uses comment_reply_js(), enqueue_scripts()
	 * @return void
	 */
	public function wp_enqueue_scripts() {

		$this->comment_reply_js();
		$this->enqueue_scripts();

	}

	/**
 	 * Enqueue admin functionality
	 *
	 * Enqueue admin scripts files
	 *
	 * @access public
	 * @uses wp_enqueue_script(), wp_localize_script(), 
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook ) {

		// Admin CSS
		wp_enqueue_style( 'adminstyle', get_template_directory_uri() . '/framework/admin/css/admin-style.css', NULL, NULL, 'all' );
		wp_enqueue_style( 'starting_screen', get_template_directory_uri() . '/framework/admin/css/starting-screen.css', NULL, NULL, 'all' );
		wp_enqueue_style( 'theme_options', get_template_directory_uri() . '/framework/admin/css/theme-options.css', NULL, NULL, 'all' );
		wp_enqueue_style( 'fonticonpicker', get_template_directory_uri() . '/framework/admin/assets/iconpicker/jquery.fonticonpicker.min.css', NULL, NULL, 'all' );
		wp_enqueue_style( 'fonticonpicker_theme', get_template_directory_uri() . '/framework/admin/assets/iconpicker/jquery.fonticonpicker.darkgrey.min.css', NULL, NULL, 'all' );

		// Admin Js
		wp_enqueue_style( 'fontawesome', get_template_directory_uri(). '/inc/fonts/font-awesome-4.6.3/css/font-awesome.min.css', NULL, NULL, 'all' ); 
		wp_enqueue_script( 'braneIconsArray', BRANE_TEMPLATE_URI . 'framework/admin/assets/iconpicker/braneIconsArray.js', '',false, true );	
		wp_enqueue_script( 'fonticonpicker', BRANE_TEMPLATE_URI . 'framework/admin/assets/iconpicker/jquery.fonticonpicker.min.js', array('jquery'),false, true );			
		wp_enqueue_script( 'brane_admin', BRANE_TEMPLATE_URI . 'framework/js/brane_admin.js', array('jquery'),false, true );
		wp_localize_script( 'brane_admin', 'brane', array( 'uploadurl' => BRANE_UPLOAD_URI ) );
		
		/**
		 * Enqueue custom sidebars if in its page
		 */
		//if( strpos( $hook, 'brane_custom_sidebars' ) ){
		$current_screen = get_current_screen();

		if( $current_screen->id == 'widgets'){
			wp_enqueue_script( 'brane_custom_sidebars', BRANE_TEMPLATE_URI . 'framework/js/brane_custom_sidebars.js', array('jquery'),false, true );	

			$translation_array = array(
				'remove_confirm' => esc_html__('Are you sure you want to remove ', 'brane_lang'),
				'remove_desc' => esc_html__('This will remove any widgets you have assigned to this sidebar.', 'brane_lang'),				
				'sidebar_name' => esc_html__('Sidebar Name:', 'brane_lang'),
				'alert_msg' => esc_html__('Cannot add sidebar: ', 'brane_lang')
			);

			wp_localize_script( 'brane_custom_sidebars', 'brane_sidebars_strings', $translation_array );
			
		}
	}

	/**
 	 * wp_footer hook
	 *
	 * @access public
	 * @uses inspect_scripts(), wp_localize_script()
	 * @return void
	 */
	public function wp_footer() {

		$this->inspect_scripts();
		wp_localize_script( 'brane', 'brane', brane()->branejs );
	}

	/**
 	 * Prints Footer Scripts
	 *
	 * @access public
	 * @return void
	 */
	public function wp_print_footer_scripts() {
		
		// This Function can be used to print footer scripts

	}

	// =====================================================================>
	// 		AFTER SETUP THEME
	// =====================================================================>
	
	/**
 	 * Content width
	 *
	 * Sets the contet width from the options array 
	 *
	 * @access public
	 * @uses get_config()
	 * @return void
	 */
	public function set_content_width() {
		
	    global $content_width;

	    if ( ! isset( $content_width ) ) {

	        $brane_content_width = brane()->get_config( 'content_width' );

	        if ( is_int( $brane_content_width ) ) {

	            $content_width = $brane_content_width;

	        } else {
	            
	            $content_width = 1366; // Default contet width

	        }
	    }
	}

	// =====================================================================>
	// 		IMAGES
	// =====================================================================>
	/**
 	 * Image Sizes
	 *
	 * Registers each image size from theme-config
	 *
	 * @access public
	 * @uses get_config(), add_image_size()
	 * @return void
	 */
	public function image_sizes() {

		$sizes = brane()->get_config( 'image_sizes', array() );

		foreach ( $sizes as $size_name => $size_values ) {

			add_image_size( $size_name, $size_values['width'], $size_values['height'], ( isset( $size_values['crop'] ) )? $size_values['crop'] : false );

		}
	}
	
	// =====================================================================>
	// 		FIXES
	// =====================================================================>
	/**
	 * Attachment Page Redirection
	 *
	 * Redirects attachment pages to their template, WordPress may fail if postname permalink selected.
	 *
	 * @access public
	 * @param $original_template
	 * @uses Brane_Framework->current_template(), get_template_directory()
	 * @return string
	 */
	public function to_attachment( $original_template ) {

		if ( in_array( 'singular-attachment', brane()->node->current_template() ) ) {
		    return get_template_directory() . '/single-attachment.php';
	  	} else {
		    return $original_template;
		}
	}
	
	/**
	 * Customize the title for the home page, if one is not set.
	 *
	 * @access public
	 * @uses get_bloginfo()
	 * @param string $title The original title.
	 * @return string The title to use.
	 */
	public function wpdocs_hack_wp_title_for_home( $title ){
		
		if ( empty( $title ) && ( is_home() || is_front_page() ) ) {

			$title = get_bloginfo( 'name' ) . ' | ' . get_bloginfo( 'description' );
		}

		return $title;
	}


	/**
	 * Remove emojis
	 * 
	 * Removes default WordPress string to emoticon conversion
	 */
	public function remove_emojis(){

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );

	}
	
	// =====================================================================>
	// 		SCRIPTS
	// =====================================================================>
	/**
 	 * Enqueue Scripts & Styles 
	 *
	 * Requires script and style files for Theme Front-end
	 *
	 * @access public
	 * @uses wp_enqueue_script()
	 * @return void
	 */
	public function enqueue_scripts() {
				
		/**
		 * BOOTSTRAP JS
		 *
		 * Register bootstrap 
		 */
		wp_enqueue_script( 'bootstrap', BRANE_TEMPLATE_URI . 'css/bootstrap/js/bootstrap.min.js', array('jquery'), '3.3.6', true );

		/**
		 * BOOTSTRAP CSS
		 *
		 * Register bootstrap 
		 */
		wp_enqueue_style( 'bootstrap', BRANE_TEMPLATE_URI . 'css/bootstrap/css/bootstrap.min.css', array(), '3.3.6' );
		wp_enqueue_style( 'bootstrap-theme', BRANE_TEMPLATE_URI . 'css/bootstrap/css/bootstrap-theme.min.css', array( 'bootstrap' ), '3.3.6' );
		
		/**
		 * Brane script
		 */
		wp_enqueue_script( 'brane', BRANE_TEMPLATE_URI . 'framework/js/brane.js', array('jquery'), false, true );
		
		/**
		 * Theme css
		 */
		wp_enqueue_style( 'brane_theme', BRANE_TEMPLATE_URI . 'css/css/bazar_theme.min.css' );

		/**
		 * Dynamic style
		 */
		wp_add_inline_style( 'brane_theme', brane()->dynamic_style );

	}

	/**
	 * Store enqueued scripts in branejs
	 *
	 * @access public
	 * @return void
	 */
	public function inspect_scripts() {

	    global $wp_scripts, $wp_styles;
	    brane()->branejs[ 'scripts' ] = $wp_scripts;
	    brane()->branejs[ 'styles' ] = $wp_styles;
	}
	
	/**
	 * Enqueue WordPress’s comment-reply.js
	 *
	 * This code checks if the visitor is browsing either a page or a post and adds the JavaScript required for threaded comments if they are
	 *
	 * @access public
	 * @uses is_admin(), is_singular(), comments_open(), get_option(), wp_enqueue_script()
	 * @return void 
	 */
	public function comment_reply_js(){

		if ( !is_admin() && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}

	/**
	 * Remove Query String from Static Resources
	 *
	 * Avoids Browser caching scripts 
	 *
	 * @access public
	 * @uses remove_query_arg()
	 * @return string 
	 */
	public function remove_cssjs_ver( $src ) {

		if( strpos( $src, '?ver=' ) )
		$src = remove_query_arg( 'ver', $src );
		return $src;

	}

	/**
	 * Remove Query String from Static Resources
	 *
	 * Avoids Browser caching scripts 
	 *
	 * @access public
	 * @uses add_filter()
	 * @return void 
	 */
	public function remove_query_string(){

		add_filter( 'style_loader_src', array( $this, 'remove_cssjs_ver' ), 10, 2 );
		add_filter( 'script_loader_src', array( $this, 'remove_cssjs_ver' ), 10, 2 );

	}

	// =====================================================================>
	// 		WIDGETS
	// =====================================================================>
	/**
	 * Register Widget Areas
	 *
	 * This function registers an specific number of widget areas and default sidebar 
	 *
	 * @access public
	 * @uses apply_filters(), register_sidebar()
	 * @return void
	 */
	public function register_widget_areas() {

		$args = array(
			'id' => 'sidebar_',
			'number' => 0, 
			'name' => 'Sidebar ',
			'params' => array()
		);
		
		$args = apply_filters( 'brane_register_widget_areas_args_filter', $args );

		$args[ 'number' ] = intval( $args[ 'number' ] );

		if( !is_array( $args[ 'params' ] ) ){

			$args[ 'params' ] = array(); 

		}
		
		if( empty( $args[ 'params' ] ) ){

			$args[ 'params' ] = array(
				'before_widget' => '<section id="%1$s" class="brane_widget_section widget %2$s">',
				'after_widget'  => '</section>',
	            'before_title'  => '<h3 class="widget-title">',
	            'after_title'   => '</h3>',
			);

		}

		while ( $args[ 'number' ] > 0) {

			$args[ 'number' ]--;

           	$widgets_args = array(
				'name' 			=> $args[ 'name' ] . ( $args[ 'number' ] + 1),
				'id' 			=> $args[ 'id' ] . $args[ 'number' ],
				'before_widget' => $args[ 'params' ]['before_widget'],
				'after_widget' 	=> $args[ 'params' ]['after_widget'],
				'before_title' 	=> $args[ 'params' ]['before_title'],
				'after_title'	=> $args[ 'params' ]['after_title'] 
			);

			$widgets_args = apply_filters( 'register_widget_areas', $widgets_args, $args[ 'id' ] );

			register_sidebar( $widgets_args );
		}

		// Register default sidebar - Necessary
		register_sidebar( array(
			'name'          => esc_html__( 'Default Sidebar', 'brane_lang' ),
			'id'            => 'def_sidebar',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="brane_widget_title widget-title">',
			'after_title'   => '</h3>',
		) );
	}

	// =====================================================================>
	// 		CONFIG
	// =====================================================================>
	/**
	 * Widget Caller
	 *
	 * Calls widgets specified by theme-config
	 *
	 * @access public
	 * @uses get_config(), add_theme_support()
	 * @return void
	 */
	public function theme_support() {

		$theme_supports = brane()->get_config( 'theme_support', array() );
		
		foreach ($theme_supports as $key => $theme_support) {
			
			if( is_array( $theme_support ) ){

				add_theme_support( $theme_support[ 'feature' ], $theme_support[ 'args' ] );

			}else{

				add_theme_support( $theme_support );

			}
		}

		// It is necessary use this two
		/**
         * Add default posts and comments RSS feed links to head
         */
		add_theme_support( 'automatic-feed-links' );

    	/**
    	 * Let WordPress manage the document title
         *
    	 * By adding theme support, we declare that this theme does not use a
    	 * hard-coded <title> tag in the document head, and expect WordPress to
    	 * provide it for us
    	 */
		add_theme_support( 'title-tag' );		

		/**
    	 * Custom Header Support
         *
    	 * This feature enables Custom_Headers support for a theme as of Version 3.4.
    	 */
		add_theme_support( 'custom-header' );

		/**
    	 * Custom Background
         *
    	 * This feature enables Custom_Backgrounds support for a theme as of Version 3.4.
    	 */
		add_theme_support( 'custom-background' );

		/**
		 * Fix option tree change_image_button over call
		 *
		 * change_image_button was called thousands of times
		 */
		global $ot_loader;
		
		remove_filter( 'gettext', array( $ot_loader, 'change_image_button' ) );
	}

	/**
	 * Mobile Landing Page 
	 *
	 * Redirects to Mobile Landing Page
	 *
	 * @access public
	 * @param $original_template
	 * @uses detect_mobile(), is_home(), is_front_page() 
	 * @return string
	 */
	public function mobile_home( $original_template ){ 

		if(  $this->mobile_detector->isMobile() && !$this->mobile_detector->isTablet() && !isset( $_GET["to_mobile"] ) ) {

	        $home_mobile = locate_template( array( 'home-mobile.php'));

	        if ( '' != $home_mobile ) {

	            return $home_mobile ;
	        }
	    }

	    return $original_template;
	}		
	

}