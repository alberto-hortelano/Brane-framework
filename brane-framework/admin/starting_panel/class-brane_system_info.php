<?php
/**
 * Brane System Info
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
 * Brane System Info
 *
 * The Brane_System_Info generates info reports abour Wordpress, Browser And Server Enviroment
 *
 */
class Brane_System_Info {

	function __construct(){
		add_action( 'wp_ajax_download_system_info', array( __CLASS__, 'download_info' ) );		
	}

	// =====================================================================>
	// 		SYSTEM INFO HELPERS
	// =====================================================================>
	/**
	 * Size to number 
	 *
	 * @since  2.0.0
	 * @access private
	 * @param $size
	 * @return string
	 */
	private static function let_to_num( $size ) {
       
        $let   = substr( $size, - 1 );
        $output = substr( $size, 0, - 1 );

        switch ( strtoupper( $let ) ) {
            case 'P':
                $output *= 1024;
            case 'T':
                $output *= 1024;
            case 'G':
                $output *= 1024;
            case 'M':
                $output *= 1024;
            case 'K':
                $output *= 1024;
        }

        return $output;
    }

    /**
	 * Boolean to String 
	 *
	 * @since  2.0.0
	 * @access public
	 * @param $var
	 * @return string
	 */
    public static function bool_to_string( $var ) {

        if ( $var ) {
            return 'true';
        } 
        
        return 'false';       
    }

    /**
	 * Gets the user ip for system info purposes 
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses apply_filters()
	 * @return string
	 */
    public static function get_the_user_ip() {

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return apply_filters( 'wpb_get_ip', $ip );
	}

	/**
	 * Reserved Ip
	 *
	 * @since  2.0.0
	 * @access public
	 * @return boolean
	 */
	public static function reserved_ip( $ip ){ // From Stackoverflow http://stackoverflow.com/a/14125871

	    $reserved_ips = array( // not an exhaustive list
	    '167772160'  => 184549375,  /*    10.0.0.0 -  10.255.255.255 */
	    '3232235520' => 3232301055, /* 192.168.0.0 - 192.168.255.255 */
	    '2130706432' => 2147483647, /*   127.0.0.0 - 127.255.255.255 */
	    '2851995648' => 2852061183, /* 169.254.0.0 - 169.254.255.255 */
	    '2886729728' => 2887778303, /*  172.16.0.0 -  172.31.255.255 */
	    '3758096384' => 4026531839, /*   224.0.0.0 - 239.255.255.255 */
	    );

	    $ip_long = sprintf('%u', ip2long($ip));

	    foreach ($reserved_ips as $ip_start => $ip_end){

	        if (($ip_long >= $ip_start) && ($ip_long <= $ip_end)){

	            return true;
	        }
	    }

	    return false;
	}

	/**
	 * Check if is LocalHost
	 *
	 * @since  2.0.0
	 * @access public
	 * @return boolean
	 */
    public static function checkLocalHost() {

    	$ip = self::get_the_user_ip();
    	$local_ip = self::reserved_ip($ip);

    	if ( $local_ip || $_SERVER['REMOTE_ADDR'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '::1' ){

    		return true;
    	}

    	return false;
    }

    
	/**
	 * Generate Text file download
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	static function download_info() {
		if ( ! isset( $_POST['brane_sys_info_report_down'] ) || empty( $_POST['brane_sys_info_report_down'] ) ) {
			return;
		}

		header( 'Content-type: text/plain' );

		//Text file name marked with Unix timestamp
		header( 'Content-Disposition: attachment; filename=system_info_' . time() . '.txt' );

		$info_report_down = $_POST['brane_sys_info_report_down'];

		echo esc_attr( $info_report_down );
		
		die();
	}

	public static function convert_html($html) {

		require_once( BRANE_TEMPLATE_DIR . "framework/admin/starting_panel/assets/Html2Text.php");
		require_once( BRANE_TEMPLATE_DIR . "framework/admin/starting_panel/assets/Html2TextException.php");

		self::convert_html_to_text($html);
	}

	public static function convert_html_to_text($html) {

		echo Html2Text\Html2Text::convert($html);
	}

    // =====================================================================>
	// 		MAIN SYSTEM INFO FUNCTION
	// =====================================================================>
	/**
	 * Generates System Info
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses 
	 * @return void
	 */
	public static function systemInfo( $remote_checks = false ) {

        global $wpdb;
        
        $systeminfo = array();
        
        // Get Home / Site Url
        $systeminfo['home_url'] = home_url();
        $systeminfo['site_url'] = site_url();
        
        // Only is a file-write check
        $systeminfo['wp_content_url'] = WP_CONTENT_URL;
        $systeminfo['wp_ver'] = get_bloginfo('version');
        $systeminfo['wp_multisite'] = is_multisite();
        $systeminfo['permalink_structure'] = get_option('permalink_structure') ? get_option('permalink_structure') : 'Default';
        $systeminfo['front_page_display'] = get_option('show_on_front');
        if ($systeminfo['front_page_display'] == 'page') {
            $front_page_id = get_option('page_on_front');
            $blog_page_id = get_option('page_for_posts');
            
            $systeminfo['front_page'] = $front_page_id != 0 ? get_the_title($front_page_id) . ' (#' . $front_page_id . ')' : 'Unset';
            $systeminfo['posts_page'] = $blog_page_id != 0 ? get_the_title($blog_page_id) . ' (#' . $blog_page_id . ')' : 'Unset';
        }
        
        $systeminfo['wp_mem_limit']['raw'] = self::let_to_num(WP_MEMORY_LIMIT);
        $systeminfo['wp_mem_limit']['size'] = size_format($systeminfo['wp_mem_limit']['raw']);
        
        $systeminfo['db_table_prefix'] = 'Length: ' . strlen($wpdb->prefix) . ' - Status: ' . (strlen($wpdb->prefix) > 16 ? 'ERROR: Too long' : 'Acceptable');

        $systeminfo['db_table_prefix'] = '<span class="dashicons-before dashicons-yes">' . sprintf( esc_html__('Length: %s - Status: Acceptable', 'brane_lang'), strlen($wpdb->prefix)) . '</span>';
        if( strlen($wpdb->prefix) > 16 ){
        	$systeminfo['db_table_prefix'] = '<span class="dashicons-before dashicons-dismiss">' . sprintf( esc_html__('Length: %s - Status: ERROR: Too long', 'brane_lang'), strlen($wpdb->prefix)) . '</span>';
        }
        
        $systeminfo['wp_debug'] = 'false';
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $systeminfo['wp_debug'] = 'true';
        }
        
        $systeminfo['wp_lang'] = get_locale();
        
        if (!class_exists('Browser')) {
            
            require_once  BRANE_TEMPLATE_DIR . 'framework/admin/starting_panel/assets/browser.php';
        }
        
        $browser = new Browser();
        
        $systeminfo['browser'] = array(
            'agent' => $browser->getUserAgent() ,
            'browser' => $browser->getBrowser() ,
            'version' => $browser->getVersion() ,
            'platform' => $browser->getPlatform() ,
            
        );
        
        $systeminfo['server_info'] = esc_html($_SERVER['SERVER_SOFTWARE']);
        $systeminfo['localhost'] = self::bool_to_string(self::checkLocalHost());
        $systeminfo['php_ver'] = function_exists('phpversion') ? esc_html(phpversion()) : 'phpversion() function does not exist.';
        $systeminfo['abspath'] = ABSPATH;
        
        if (function_exists('ini_get')) {
            $systeminfo['php_mem_limit'] = size_format(self::let_to_num(ini_get('memory_limit')));
            $systeminfo['php_post_max_size'] = size_format(self::let_to_num(ini_get('post_max_size')));
            $systeminfo['php_time_limit'] = ini_get('max_execution_time');
            $systeminfo['php_max_input_var'] = ini_get('max_input_vars');
            $systeminfo['php_display_errors'] = self::bool_to_string(ini_get('display_errors'));
        }
        
        $systeminfo['suhosin_installed'] = extension_loaded('suhosin');
        $systeminfo['mysql_ver'] = $wpdb->db_version();
        $systeminfo['max_upload_size'] = size_format(wp_max_upload_size());
        
        $systeminfo['def_tz_is_utc'] = 'true';
        if (date_default_timezone_get() !== 'UTC') {
            $systeminfo['def_tz_is_utc'] = 'false';
        }
        
        $systeminfo['fsockopen'] = 'false';
        if (function_exists( 'fsockopen' ) ) {
            $systeminfo['fsockopen'] = 'true';
        }

        $systeminfo['curl'] = 'false';
        if( function_exists( 'curl_init' ) ){
			$systeminfo['curl'] = 'true';
        }
        
        $systeminfo['soap_client'] = 'false';
        if ( class_exists( 'SoapClient' ) ) {
           $systeminfo['soap_client'] = 'true';
        }
              
        $systeminfo['dom_document'] = 'false';
        if ( class_exists( 'DOMDocument' ) ) {
           $systeminfo['dom_document'] = 'true';
        }
        
        $systeminfo['gzip'] = 'false';
        if ( is_callable( 'gzopen' ) ) {
           $systeminfo['gzip'] = 'true';
        }
        
        if ($remote_checks == true) {
                        
            $request['cmd'] = '_notify-validate';

            $params = array(
				'sslverify' => false,
				'timeout'   => 60,
				'body'      => $request,
			);

			$response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );

			if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {

				$systeminfo['wp_remote_post'] = 'true';
                $systeminfo['wp_remote_post_error'] = '';

			} else {

				$systeminfo['wp_remote_post'] = 'false';
                $systeminfo['wp_remote_post_error'] = $response->get_error_message();

			}

        }
        
        $active_plugins = (array)get_option('active_plugins', array());
        
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        
        $systeminfo['plugins'] = array();
        
        foreach ($active_plugins as $plugin) {
            $plugin_data = @get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            $plugin_name = esc_html($plugin_data['Name']);
            
            $systeminfo['plugins'][$plugin_name] = $plugin_data;
        }
        
        $active_theme = wp_get_theme();
        
        $systeminfo['theme']['name'] = $active_theme->Name;
        $systeminfo['theme']['version'] = $active_theme->Version;
        $systeminfo['theme']['author_uri'] = $active_theme->{'Author URI'};
        $systeminfo['theme']['is_child'] = self::bool_to_string(is_child_theme());
        
        if (is_child_theme()) {
            $parent_theme = wp_get_theme($active_theme->Template);
            
            $systeminfo['theme']['parent_name'] = $parent_theme->Name;
            $systeminfo['theme']['parent_version'] = $parent_theme->Version;
            $systeminfo['theme']['parent_author_uri'] = $parent_theme->{'Author URI'};
        }
        
        
        return $systeminfo;
    }

    // =====================================================================>
	// 		SYSTEM INFO REPORT BY MAIL
	// =====================================================================>
    /**
	 * System Info Render Email
	 *
	 * @since  2.0.0
	 * @uses 
	 * @return void
	 */
    static function sysinfo_render_mail() {
		
		include( BRANE_TEMPLATE_DIR . 'framework/admin/starting_panel/sections/views/mail-form.php' );
	}
	
	/**
	 * System Info Render Report
	 *
	 * @since  2.0.0
	 * @uses 
	 * @return void
	 */
    static function sysinfo_render_report() {

    	ob_start();
		include( BRANE_TEMPLATE_DIR . 'framework/admin/starting_panel/sections/views/report.php' );
		return ob_get_clean();
	}

	/**
	 * Sends plain-text email, inserting the System Info
	 *
	 * @since 1.0
	 * @uses
	 * @return string / boolean
	 */
	static function sysinfo_send_email() { 
		
		self::sysinfo_render_mail();

		global $current_user;

		if ( isset( $_POST['brane-sysinfo-mail'] ) &&
			isset( $_POST['brane-sysinfo-subject'] ) &&
			isset( $_POST['brane-sysinfo-message'] ) ) {

			if ( ! empty( $_POST['brane-sysinfo-mail'] ) ) {
				$address = $_POST['brane-sysinfo-mail'];
			} else {
				return self::sysinfo_send_email_response(3);
			}

			if ( ! empty( $_POST['brane-sysinfo-subject'] ) ) {
				$subject = $_POST['brane-sysinfo-subject'];
			} else {
				return self::sysinfo_send_email_response(4);
			}

			if ( ! empty( $_POST['brane-sysinfo-message'] ) ) {
				$message = $_POST['brane-sysinfo-message'];
			} else {
				$message = esc_html__('System Info Message','brane_lang');
			}

			wp_get_current_user();

			$headers = array(
				'From: ' . $current_user->display_name . ' <' . $current_user->user_email . '>',
				'Reply-To: ' . $current_user->user_email,
			);

			// Insert System Info into email
			$message .= "\r\n\r\n---------------\r\n\r\n" . self::display();
			
			$sent = wp_mail( $address, $subject, $message, $headers );

			if ( $sent ) {

				return self::sysinfo_send_email_response(0); // success
			} else {

				return self::sysinfo_send_email_response(1); // error
			}
		}

		return self::sysinfo_send_email_response(2);
	}

	/**
	 * System Info Email Response
	 *
	 * @return void
	 */
	static function sysinfo_send_email_response($email_sent) { 
		
		if ( $email_sent == 0 ) {

			printf( '<div id="brane_sys_rep_mail_message_succes" class="updated"><p>%s</p></div>', esc_html__( 'Report Email Sent Successfully!', 'brane_lang' ) );

		} elseif ( $email_sent == 2 ) {

			// First load - no mail sent

		} else {

			printf( '<div id="brane_sys_rep_mail_message_error" class="error"><p>%s ' . $email_sent . '</p></div>', esc_html__( 'Error Sending Report Email!', 'brane_lang' ) );
		}
		
	}

	/**
	 * Gather data, then generate System Info
	 *
	 * Based on System Info sumbmenu page in Easy Digital Downloads
	 * by Pippin Williamson
	 *
	 * @return void
	 */
	static function display() {

		if (!class_exists('Browser')) {
            
            require_once  BRANE_TEMPLATE_DIR . 'framework/admin/starting_panel/assets/browser.php';
        }

		$browser = new Browser();
		
		$theme_data = wp_get_theme();
		$theme      = $theme_data->Name . ' ' . $theme_data->Version;
		

		// Try to identify the hosting provider
		$host = false;
		if ( defined( 'WPE_APIKEY' ) ) {
			$host = 'WP Engine';
		} elseif ( defined( 'PAGELYBIN' ) ) {
			$host = 'Pagely';
		}

		$request['cmd'] = '_notify-validate';

		$params = array(
			'sslverify' => false,
			'timeout'   => 60,
			'body'      => $request,
		);

		$response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );

		if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {

			$WP_REMOTE_POST = 'wp_remote_post() works' . "\n";
		} else {

			$WP_REMOTE_POST = 'wp_remote_post() does not work' . "\n";
		}

		return self::attach_output( $browser, $theme, $host, $WP_REMOTE_POST );
	}

	/**
	 * Attach Report Output
	 *
	 * @param   string  Browser information
	 * @param   string  Theme Data
	 * @param   string  Theme name
	 * @param   string  Host
	 * @param   string  WP Remote Host
	 */
	static function attach_output( $browser, $theme, $host, $WP_REMOTE_POST ) {

		global $wpdb;
		$report = Brane_System_Info::sysinfo_render_report();   
		ob_start();
		Brane_System_Info::convert_html($report); 
		return ob_get_clean();

	}

}