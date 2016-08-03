<?php
/**
 * Theme Starting Panel
 * 
 * @package Brane_Framework
 * @since 2.0.0
 */

/**
 * Avoid direct access
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Theme Starting Panel
 *
 * The Brane_Starting_Panel class manages Theme Starting Panel
 *
 */
class Brane_Theme_Panel {

	function __construct(){
		
		// THEME WELCOME SCREEN
		/* activation redirect */
		add_action( 'load-themes.php', array( $this, 'theme_activation_admin_redirect' ) );

		/* create dashbord page */
		add_action( 'admin_menu', array( $this, 'starting_register_menu' ) );
		
		// IMPORT DEMO CONTENT
		if ( class_exists('PT_One_Click_Demo_Import') ){
			
			// Demo Import Starting Screen
			add_filter( 'pt-ocdi/plugin_intro_text', array( $this, 'importer_starting_panel') );		
		
		}

	}

	// =====================================================================>
	// 		THEME Starting Panel - Panel Structure Functions 
	// =====================================================================>
	/**
	 * Adds a notice upon theme successful activation.
	 *
	 * @access public
	 * @uses is_admin(), $_GET(), add_action() 
	 * @return void
	 */
	public function theme_activation_admin_redirect() {

		global $pagenow;

		if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
			wp_redirect( admin_url( 'themes.php?page=brane_starting' ) );
      		exit;
		}
	}	

   	/**
	 * Creates the Dashboard Page
	 *
	 * @access public
	 * @uses add_theme_page(), welcome_screen() 
	 * @return void
	 * @see  add_theme_page()
	 */
	public function starting_register_menu() {

		$theme_page_text = '<span class="brane_starting_menu dashicons-before dashicons-star-filled">' . esc_html__( 'About '. wp_get_theme(), 'brane_lang') . '</span>';

		add_theme_page( 'About ' . wp_get_theme(), $theme_page_text, 'activate_plugins', 'brane_starting', array( $this, 'welcome_screen' ) );
	}

	/**
	 * Welcome screen content
	 *
	 * @access public
	 * @uses welcome_getting_started(), register_support(), changelog(), sys_info()
	 * @return void
	 */
	public function welcome_screen() {

		require_once( ABSPATH . 'wp-load.php' );
		require_once( ABSPATH . 'wp-admin/admin.php' );
		require_once( ABSPATH . 'wp-admin/admin-header.php' );

		$theme_name = wp_get_theme();
		$theme_name_version = wp_get_theme()->get('Version');

		$urls = array(
			'envato' 		=> 'http://themeforest.net/user/branedesign/follow',
			'twitter'		=> 'https://twitter.com/BraneDesign',
			'facebook'		=> 'https://www.facebook.com/BraneDesign-758900647482712/',
			'doc'			=> 'http://branedesign.com/wordpressthemes/documentation/bazardoc/',
			'child_theme'	=> 'http://www.wpbeginner.com/beginners-guide/wordpress-child-theme-pros-cons/',
			'wpml'			=> 'https://wpml.org/documentation/getting-started-guide/'
		);

		?>
		<div class="brane-tabs-navigation">
			<header>
				<span class="brane_tab_title"><?php echo esc_html( $theme_name ) ?>
				<?php if( !empty( $theme_name_version ) ){ ?> 
					<span id="brane-theme-version">
						<?php echo esc_html( $theme_name_version ); ?> 
					</span>
				<?php } ?>
				</span>
				<div class="brane_follow">
					<a href="<?php esc_url( $urls['envato'] ) ?>" class="brane_social_link"><img src="<?php echo get_template_directory_uri() . '/framework/admin/img/envato.png' ?>" alt="BraneDesign Envato"></a>
					<a href="<?php esc_url( $urls['twitter'] ) ?>" rel="nofollow"  class="brane_social_link"><img src="<?php echo get_template_directory_uri() . '/framework/admin/img/twitter.png' ?>" alt="BraneDesign Twitter"></a>
					<a href="<?php esc_url( $urls['facebook'] ) ?>" rel="nofollow" class="brane_social_link"><img src="<?php echo get_template_directory_uri() . '/framework/admin/img/facebook.png' ?>" alt="BraneDesign Facebook"></a>
				</div>				
			</header>
			<ul class="brane-starting-nav-tabs" role="tablist">
				<li role="presentation" class="active">
					<a href="javascript:void(0)" id="brane_getting_started_tab"><?php esc_html_e( 'Getting started','brane_lang'); ?></a>
				</li>							
				<li role="presentation">
					<a href="javascript:void(0)" id="register_product_tab"><?php esc_html_e( 'Support & F.A.Q.','brane_lang'); ?></a>
				</li>
				<li role="presentation">
					<a href="javascript:void(0)" id="brane_changelog_tab"><?php esc_html_e( 'Changelog','brane_lang'); ?></a>
				</li>
				<li role="presentation">
					<a href="javascript:void(0)" id="sys_info_tab"><?php esc_html_e( 'System Info','brane_lang'); ?></a>
				</li>
			</ul>
		</div>
		<div class="brane-tab-content brane_row">

			<?php
			// Gets Sections Templates
			$this->welcome_getting_started(); 
			$this->register_support(); 
			$this->changelog(); 
			$this->sys_info(); 
			?>

		</div>
		<?php
	}

	/**
	 * Getting started
	 *
	 * @access public
	 * @uses get_template_directory()
	 * @return void
	 */
	public function welcome_getting_started() {
		require_once( get_template_directory() . '/framework/admin/starting_panel/sections/getting-started.php' );
	}

	/**
	 * Changelog
	 *
	 * @access public
	 * @uses get_template_directory()
	 * @return void
	 */
	public function changelog() {
		require_once( get_template_directory() . '/framework/admin/starting_panel/sections/changelog.php' );
	}

	/**
	 * Register & Support
	 *
	 * @access public
	 * @uses get_template_directory()
	 * @return void
	 */
	public function register_support() {
		require_once( get_template_directory() . '/framework/admin/starting_panel/sections/register.php' );
	}

	/**
	 * System Info
	 *
	 * @access public
	 * @uses get_template_directory()
	 * @return void
	 */
	public function sys_info() {
		require_once( get_template_directory() . '/framework/admin/starting_panel/sections/sys_info.php' );
	}

	/**
	 * Is Plugin Installed
	 *
	 * @access public
	 * @uses get_option()
	 * @param $plug_path
	 * @return boolean
	 */
	public static function if_plugin_installed( $plugin_folder = '', $plug_slug = '' ) {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$installed_plugins = get_plugins( $plugin_folder );
		
		if( $plug_slug != '' && !empty( $installed_plugins[ $plug_slug ] ) ){
			
			return true;
		}

		return false;

	}
	
	// =====================================================================>
	// 		THEME IMPORT DEMO CONTENT
	// =====================================================================>
	/**
	 * Importer Starting Screen
	 *
	 * @access 	public
	 * @param $screen_content
	 * @return  string 
	 */
	public function importer_starting_panel( $screen_content ) {

	    $screen_content .= '<div class="ocdi__intro-text">' . esc_html__( 'Select a Demo and start installing content!', 'brane_lang') . '</div>';

	    return $screen_content;
	}
	

}