<?php
/**
 * Brane_Template_Functions main class Definition
 *
 * @package   Brane_Framework
 * @version   2.0.0
 * @author    Branedesign
 */

/**
 * Avoid direct access
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Brane_Template_Functions {

	public $comments_args;

	public $comments_depth;

	public $std_loop_content = '';

	public function __construct(){

		// Inserts custom css per page
		add_action('wp_head', array( $this, 'insert_custom_css') );		

		// Custom Avatar
		add_action( 'show_user_profile', array( $this, 'custom_avatar_field' ) );
		add_action( 'edit_user_profile', array( $this, 'custom_avatar_field' ) );
		add_action( 'personal_options_update', array( $this, 'save_custom_avatar_field' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_custom_avatar_field' ) );
		add_filter( 'get_avatar', array( $this, 'gravatar_filter'), 10, 5 );

		// Register Widget Areas
		add_filter( 'brane_register_widget_areas_args_filter', array( $this, 'footer_wa_filter') );

		// Search Filter
		add_action('pre_get_posts', array( $this, 'advanced_search_query'), 1000 );
		
		// Custom Excerpt
		add_filter( 'excerpt_more', array( $this, 'custom_excerpt_more' ) );
		
		// =====================================================================>
		// 		Actions & Filters Hooked on Standard Loop
		// =====================================================================>
		add_action( 'brane_before_loop', array( $this, 'content_template_part' ), 10 );
		add_action( 'brane_after_loop', array( $this, 'pagination'), 10 );
		
		// =====================================================================>
		// 		Actions & Filters Hooked on Content-Page
		// =====================================================================>
		add_action( 'brane_after_page_content', array( $this, 'get_comments_template'), 30 );

		// =====================================================================>
		// 		Actions & Filters Hooked on Content-Single
		// =====================================================================>
		add_action( 'brane_after_single_post', array( $this, 'related_posts'), 10);
		add_action( 'brane_after_single_post', array( $this, 'get_comments_template'), 20 );

		// =====================================================================>
		// 		Actions & Filters Related to Comments
		// =====================================================================>
		// Actions After Comments - comments.php
		add_action( 'brane_after_comments', array( $this, 'comments_form' ), 10 );

		// Actions Before Loop Comments
		add_action( 'brane_before_loop_comments', array( $this, 'comments_number' ), 10 );		
		add_action( 'brane_before_loop_comments', array( $this, 'comments_navigation' ), 20 );
		
		// Actions After Loop Comments
		add_action( 'brane_after_loop_comments', array( $this, 'comments_navigation' ), 10 );

		// Actions to add html content to form
		add_action( 'comment_form_before_fields', function(){
		     // Adjust this to your needs:
		     echo '<div class="row brane-labels-wrapper">'; 
		});
		add_action( 'comment_form_after_fields', function(){
		     // Adjust this to your needs:
		     echo '</div>'; 
		});
		
		// Import Files --> Extended by each Theme
		if ( class_exists('PT_One_Click_Demo_Import') ){
			// Demo Files Array
			add_filter( 'pt-ocdi/import_files', array( $this, 'import_files') );
		}
	}

	// =====================================================================>
	// 		Template Functions
	// =====================================================================>
	/**
 	 * Get Global post
	 *
	 * @since  2.0.0
	 * @access public	
	 * @return string
	 */
	public function get_global_post(){

		global $post;

		return $post;
	}

	/**
 	 * Get Global comment
	 *
	 * @since  2.0.0
	 * @access public	
	 * @return string
	 */
	public function get_global_comment(){

		global $comment;

		return $comment;		
	}

	/**
 	 * Get Global user
	 *
	 * @since  2.0.0
	 * @access public	
	 * @return string
	 */
	public function get_global_user(){

		global $user;

		return $user;		
	}

	/**
 	 * Get Global Woocommerce
	 *
	 * @since  2.0.0
	 * @access public	
	 * @return string
	 */
	public function get_global_woocommerce(){

		if( class_exists( 'Woocommerce' ) ){
			
			global $woocommerce;

			return $woocommerce;
		}				
	}

	/**
 	 * Get Global Product
	 *
	 * @since  2.0.0
	 * @access public	
	 * @return string
	 */
	public function get_global_product(){

		if( class_exists( 'Woocommerce' ) ){
			
			global $product;

			return $product;
		}				
	}

	/**
 	 * Get Global Woocommerce Loop
	 *
	 * @since  2.0.0
	 * @access public	
	 * @return string
	 */
	public function get_global_woocommerce_loop(){

		if( class_exists( 'Woocommerce' ) ){
			
			global $woocommerce_loop;

			return $woocommerce_loop;
		}				
	}

	/**
 	 * Get Global Yith
	 *
	 * @since  2.0.0
	 * @access public	
	 * @return string
	 */
	public function get_global_yith_wcwl(){

		if( class_exists( 'YITH_WCWL' ) ){
			
			global $yith_wcwl;

			return $yith_wcwl;
		}				
	}

	/**
 	 * Get Global Wpdb
	 *
	 * @since  2.0.0
	 * @access public	
	 * @return string
	 */
	public function get_global_wpdb(){
			
		global $wpdb;

		return $wpdb;		
	}

	/**
 	 * Insert Custom Css per Page
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_post_meta()
	 * @return string
	 */
	public function insert_custom_css() {

	    if ( is_page() ) {

	        $custom_css = get_post_meta( get_the_ID(), 'brane_meta_page_custom_css', true );

	        if( strlen( $custom_css ) > 5 ) {

	            echo '<style type="text/css">' . $custom_css . '</style>';
	        }
	    }
	} 

	/**
 	 * Builds Page Title and Breadcrumbs
	 *
	 * @since  2.0.0
	 * @access public
	 * @return string
	 */
	public function build_breadcrumb() {

		$current_template = brane()->node->current_template;
		$page_title = brane()->node->title;
		$divider = ' > ';
		$shop_title = $shop_url = $woo = '';

		if( class_exists('Woocommerce') ){
			
			$woo = true;
			$shop_title = get_the_title( wc_get_page_id('shop') );
			$shop_url =  get_permalink( wc_get_page_id('shop') );
		}

		echo '<ol class="breadcrumb">';

		if( !in_array( 'blog', $current_template ) &&  !in_array( 'home', $current_template ) ){
		
			echo '<li><a href="' . esc_url( brane()->node->breadcrumb[ 'home' ]['url'] ) . '">' . esc_html( brane()->node->breadcrumb[ 'home' ]['title'] ) . '</a></li>';
		}

		if( in_array( 'page', $current_template ) ){

			if( isset( brane()->node->breadcrumb[ 'shop' ] ) ){

				echo '<li><a href="'. esc_url( $shop_url ) .'">'. esc_html( $shop_title ) .'</a></li>';
			}

			foreach( brane()->node->breadcrumb[ 'ancestors' ] as $title => $url){
	            
	            echo '<li><a href="'. esc_url( $url ) .'">' . esc_html( $title ) . '</a></li>';	 
	       	}

	    }elseif ( in_array( 'single', $current_template) ) {
	    	
	    	if( in_array( 'single-product', $current_template ) ){

	    		echo '<li><a href="'. esc_url( $shop_url ) .'">'. esc_html( $shop_title ) .'</a></li>';
	    	}
	    	foreach ( brane()->node->breadcrumb[ 'categories' ] as $title => $url ) {
	    		
	    		echo '<li><a href="'. esc_url( $url ) .'">'. esc_html( $title ) .'</a></li>';
	    	}

	    }elseif ( in_array( 'taxonomy', $current_template ) ) {

	    	if( in_array( 'taxonomy-product_cat', $current_template ) || in_array( 'taxonomy-product_tag', $current_template ) ){

	    		echo '<li><a href="'. esc_url( $shop_url ) .'">'. esc_html( $shop_title ) .'</a></li>';
	    	}

	    	foreach ( $current_template as $key => $value) {

	    		$var_breadcrumb = brane()->node->breadcrumb;
	    		
	    		if( is_array( $var_breadcrumb ) && in_array( 'parents', $var_breadcrumb ) ){
		    		
		    		array_pop( brane()->node->breadcrumb[ 'parents' ] );

		    		foreach ( brane()->node->breadcrumb[ 'parents' ] as $title => $url ) {
		    		
		    			echo '<li><a href="'. esc_url( $url ).'">'. esc_html( $title ) .'</a></li>';
		    		}	
	    		}	    			    	

		    }

    		$page_title = single_cat_title( '', '', false);

	    }elseif ( in_array( 'date', $current_template ) ) {

	    	$page_title = brane()->node->breadcrumb[ 'date' ];
	    
	    }elseif ( in_array( 'user', $current_template ) ) {

	    	$page_title = brane()->node->breadcrumb[ 'author' ];

	    }elseif ( in_array( 'search', $current_template ) ) {
	    	
	    	$page_title = brane()->node->breadcrumb[ 'search' ];

	    }elseif ( in_array( 'error-404', $current_template ) ) {

	    	$page_title = brane()->node->breadcrumb[ 'error' ];

	    }elseif ( in_array( 'attachment', $current_template ) ) {

	    	$page_title = brane()->node->breadcrumb[ 'attachment' ];
	    }

		echo '<li class="active">' . esc_html( $page_title );

	    if ( in_array( 'paged', $current_template ) ) {

	    	echo ' / ' . brane()->node->breadcrumb[ 'paged' ];
	    }
	    echo '</li></ol>';
	}

	/**
 	 * Check for content template part on Loop
	 *
	 * Can be extended on class theme_template_functions to add content template parts for custom post types
	 *
	 * @since  2.0.0
	 * @access public
	 * @return string
	 */
	public function content_template_part() {
		
		if( in_array( 'page', brane()->node->current_template ) ){

			$this->std_loop_content = 'page';

		}elseif ( in_array( 'single', brane()->node->current_template ) ) {

			$this->std_loop_content = 'single';

		}				
	}
	
	/**
	 * Footer Widget Areas
	 *
	 * Creates Args for Footer Widget Areas
	 *
	 * @access public
	 * @uses 
	 * @return array
	 */    
    public function footer_wa_filter(){

    	$args = array(
			'id' => 'footerwa_',
			'number' => brane()->get_option('brane_footer_widget_areas'), 
			'name' => 'Footer Widget Area ',
			'params' => array()
		);

		return $args;
    }
    
	/**
	 * Posts Navigation 
	 *
	 * Builds main navigation, for post pages
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_pagenum_link()	 
	 * @param $args
	 * @return void
	 */
	public function pagination( $args = array() ){
		
		$max_num_pages = (isset($args['max_num_pages']))? $args['max_num_pages']: false;
		$range = (isset($args['range']))? $args['range']: 3;
		$paged = (isset($args['paged']))? $args['paged']: false;
		$ajax = (isset($args['ajax']) && $args['ajax'])? ' brane_ajax_pagination': '';		
		
		$showitems = ($range * 2)+1;  // total number of pages including actual to show on pagination

		if( !$paged ){

	        global $paged; // actual page number
	    }

	    if( empty($paged) ){  

	        $paged = 1;
	    }

	    if( !$max_num_pages ){

			global $wp_query;

			$max_num_pages = $wp_query->max_num_pages; 

			if( !is_numeric($max_num_pages) || $max_num_pages < 1 ) $max_num_pages = 1;
		}   

		if( is_numeric( $max_num_pages ) && $max_num_pages > 1){

			echo '<nav class="brane-nav-pagination"> <ul class="pagination">';
			
			if($paged > 2) echo '<li><a href="'. esc_url( get_pagenum_link(1) ).'">&lt;&lt;</a></li>';

			if($paged > 1) echo '<li><a aria-label="Previous" href="'. esc_url( get_pagenum_link($paged - 1) ) .'" data-brane_page="' . esc_attr( ($paged - 1) ) .'"><span aria-hidden="true" class="span-prev">&lt;</span></a></li>';
			
			$pages_before = $range;
			$pages_after = $range;

			if($range >= $paged){

	            $pages_before = $paged - 1;
	        }

			if($range >= $max_num_pages - $paged){ 

	            $pages_after = $max_num_pages - $paged;
	        }

			while ($pages_before > 0) {

				$i = $paged-$pages_before;

				echo '<li><a href="' . esc_url( get_pagenum_link($i) ) . '" data-brane_page="' . esc_attr( $i ).'" >' . esc_attr( $i ) .'</a></li>';

				$pages_before--;
			}

			echo '<li><span>'.$paged.'</span></li>';

			$n = 1;
			while ($n <= $pages_after) {

				$i = $paged+$n;

				echo '<li><a href="' . esc_url( get_pagenum_link($i) ) . '" data-brane_page="' . esc_attr( $i ) .'">' . esc_attr( $i ) .'</a></li>';

				$n++;
			}

			if($max_num_pages - $paged > 0){ 

				echo '<li><a class="previous" aria-label="Previous" href="'. esc_url( get_pagenum_link($paged + 1) ).'" data-brane_page="'. esc_attr( ($paged + 1) ).'"><span aria-hidden="true" class="span-prev">&gt;</span></a></li>';
			}

			if($max_num_pages - $paged > 1) {

				echo '<li><a href="'. esc_url( get_pagenum_link($max_num_pages) ) .'" data-brane_page="'. esc_attr( $max_num_pages ) .'">&gt;&gt;</a></li>';
			}
			echo '</ul></nav>';
		}
	}
	
	/**
 	 * Theme Check Purposes
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses posts_nav_link(), paginate_links(), the_posts_pagination(), the_posts_navigation(), next_posts_link(), previous_posts_link()
	 * @return void 
	 */
	public function wp_tc_functions(){

		posts_nav_link();
		paginate_links();
		the_posts_pagination();
		the_posts_navigation();
		next_posts_link();
		previous_posts_link();
		the_post_thumbnail();
		add_editor_style();

	}

	/**
	 * Related Posts Query
	 *
	 * Creates Related Posts WP_Query Object
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses apply_filters(), wp_get_post_terms(), wp_get_post_tags()
	 * @return WP_Query
	 */
	public function related_posts_query( $args ){ 

		$args = array(
			'postID' => get_the_ID(),
			'number_posts' => '3', 
			'taxonomy' => 'category'
		);

		$args[ 'postID' ] = intval( $args[ 'postID' ] );
		$args[ 'number_posts' ] = intval( $args[ 'number_posts' ] );
		
		$args['post_type'] = get_post_type( $args[ 'postID' ] );

		$tax_query = array(
            'relation' => 'OR',
            array( 
            	'taxonomy'  => $args[ 'taxonomy' ],
            	'field'     => 'id',
            	'terms'     => wp_get_post_terms( $args[ 'postID' ], $args[ 'taxonomy' ], array("fields" => "ids" ) )
            ),
            array( 
            	'taxonomy'  => 'tag',
            	'field'     => 'id',
            	'terms'     => wp_get_post_tags( $args[ 'postID' ] )
            ),            
        );

		$query_args = array(
			'post_type' 			=> $args[ 'post_type' ],
			'ignore_sticky_posts'	=> true,
			'no_found_rows'			=> true,
			'posts_per_page' 		=> $args[ 'number_posts' ],
			'post__not_in'			=> array( $args[ 'postID' ] ),
			'post_status'			=> 'publish',
			'tax_query'             => $tax_query
		);

		// Args for custom Query
		$args = array(
			'custom_loop_query' => new WP_Query( $query_args ),
			'template' => 'content'
		);
		
		return $args;
	}

	/**
	 * Related Posts 
	 *
	 * Adds Filter and gets template part for related posts
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses 
	 * @return WP_Query
	 */
	public function related_posts(){ 

		add_filter( 'brane_related_posts_query_filter', array( $this, 'related_posts_query') );

		brane()->get_template_part( 'related','posts' );

	}
	
	/**
	 * Custom Excerpt 
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_permalink(), get_the_ID()
	 * @return string
	 */
	public function custom_excerpt_more( $more ) {

		if( in_array( 'single-post', brane()->node->current_template() ) ){
			return;
		}
		return '... <a class="brane-read-more" href="'. esc_url( get_permalink( get_the_ID() ) ) . '">' . esc_html__( 'Read More', 'brane_lang') . '</a>';
	}

	// =====================================================================>
	// 		Elements
	// =====================================================================>
	/**
	 * Google Map
	 *
	 * Prints a Google Map from address or shortcode
	 *
	 * @since  1.0.0
	 * @access public
	 * @uses do_shortcode()
	 * @param $args
	 * @return void
	 */
	public function google_map( $args ) {

		if( $args['address'] != ''){ 

			$direction = urlencode( $args[ 'address' ] );

			$src = '//maps.google.com/?q=' . $direction;

			$html = '<div class="brane_map_overlay"></div>

					<iframe src="'. esc_attr( $src ) .'&amp;output=embed"></iframe>';

			return $html;
		}

		return false;
	}

	/**
	 * Pre-loader
	 *
	 * Prints Preloader page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function preloader() {
		
		$spinners_array = include BRANE_TEMPLATE_DIR . 'framework/assets/spinners.php';
		$selected_spinner = brane()->get_option('brane_spinner_type');

		$spinner_class = $spinners_array[ $selected_spinner ]['class'];
		$spinner_html = $spinners_array[ $selected_spinner ]['html'];
		$spinner_style = $spinners_array[ $selected_spinner ]['css'];

		echo ' <div id="brane-spinner-container" class="'. esc_attr( $spinner_class ) .'">
			
				<div class="brane_spinner">';
					
					//echo '<style scope>' . esc_html( $spinner_style ) . '</style>';

					if( $spinner_html != '' ){
						echo wp_kses_post( $spinner_html );
					}
			
			echo '</div>
		</div>';
					
	}

	/**
	 * Pop Up
	 *
	 * Framework for Popups using Magnific Pop up
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses apply_filters()
	 * @return string
	 */
	public function popup() {

		$args = array(
			'content' => '',
			'class' => 'zoom-anim-dialog mfp-hide'
		);

		$args = apply_filters( 'popup_content_filter', $args );

		$html = '<div class="' . esc_attr( $args[ 'class' ] ) . '">' . $args[ 'content' ] . '</div>';

        return $html;
	}
	
	/**
	 * Social Share Links
	 *
	 * Creates social links to share content
	 *
	 * @since  1.0.0
	 * @access public
	 * @uses get_permalink(), get_the_title(), get_the_excerpt(), wp_get_shortlink()
	 * @return string
	 */
	public function brane_social_share_links( $social_links ){
    
	    $the_permalink = get_permalink();
	    $the_title = get_the_title();
	    $the_excerpt = get_the_excerpt();
	    $output = '';

	    global $post;

	    $icons = array(
	        'twitter' => array(
	            'class' => 'fa fa-twitter',
	            'url' => 'http://twitter.com/home/?status=' . esc_html( $the_title ) . '%20'. wp_get_shortlink(),
	        ),
	        'facebook' => array(
	            'class' => 'fa fa-facebook',
	            'url' => 'http://www.facebook.com/sharer.php?u='. esc_url( $the_permalink ) .'&amp;t='. esc_html( $the_title ),
	        ),
	        'reddit' => array(
	            'class' => 'fa fa-reddit-alien',
	            'url' => 'http://www.reddit.com/submit?url='. esc_url( $the_permalink ) .'&amp;title='. esc_html( $the_title )
	        ),
	        'stumbleupon' => array(
	            'class' => 'fa fa-stumbleupon',
	            'url' => 'http://www.stumbleupon.com/submit?url='. esc_url( $the_permalink ) .'&amp;title='. esc_html( $the_title )
	        ),
	        'digg' => array(
	            'class' => 'fa fa-digg',
	            'url' => 'http://digg.com/submit?url='. esc_url( $the_permalink ) .'&amp;title='. esc_html( $the_title )
	        ),
	        'linkedin' => array(
	            'class' => 'fa fa-linkedin',
	            'url' => 'http://www.linkedin.com/shareArticle?mini=true&amp;title='. esc_html( $the_title ).'&amp;url='. esc_url( $the_permalink )
	        ),
	        'delicious' => array(
	            'class' => 'fa fa-delicious',
	            'url' => 'http://del.icio.us/post?url='. esc_url( $the_permalink ) .'&amp;title='. esc_html( $the_title )
	        ),
	        'google-plus' => array(
	            'class' => 'fa fa-google-plus',
	            'url' => 'https://plus.google.com/share?url='. esc_url( $the_permalink ) .'" onclick="javascript:window.open(this.href,
	  \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;'
	        ),
	        'pinterest' => array(
	            'class' => 'fa fa-pinterest-p',
	            'url' => 'http://pinterest.com/pin/create/button/?url='. esc_url( $the_permalink ) .'&media='. wp_get_attachment_url( get_post_thumbnail_id($post->ID) ),
	        ),
	        'tumblr' => array(
	            'class' => 'fa fa-tumblr',
	            'url' => 'http://www.tumblr.com/share/link?url='. esc_url( $the_permalink ) .'&name='. esc_html( $the_title ) .'&description='.$the_excerpt
	        ),
	        'mail' => array(
	            'class' => 'fa fa-envelope-o',
	            'url' => 'mailto:?subject='. esc_html( $the_title ) .'&amp;body='. esc_url( $the_permalink )
	        )
	       
	    ); 

	    $output .= '<ul class="nav">';    
	    	
	    	if( $social_links ){
		        foreach ( $social_links as $key => $value ) {
		            
		            $output .= '<li class="brane_' . esc_attr( $value ) . '">

		            				<a href="' . esc_url( $icons[$value]['url'] ) . '" target="_blank">

		            					<span class="' . esc_attr( $icons[$value]['class'] ) . '"></span>';
		            				
		            $output .= '</a></li>';
		            
		        }
		    }

	    $output .= '</ul>';
	    
	    return $output;
	}

	/**
	 * Social Links
	 *
	 * Creates Html structure for social links
	 *
	 * @access public
	 * @uses get_option(), get_post_meta()
	 * @param $postID
	 * @return void
	 */
	public function social_links( $postID = false ) {

		$social_links = brane()->get_option('brane_social_links');
		
		if( $postID != false ){

       		$social_links = get_post_meta( $postID, 'brane_social_links', true );

       	}

        $output = '';

        $icons = array(
            'facebook' => 'fa fa-facebook',
            'twitter' => 'fa fa-twitter',
            'tumblr' => 'fa fa-tumblr',
            'instagram' => 'fa fa-instagram',
            'pinterest' => 'fa fa-pinterest-p',
            'linkedin' => 'fa fa-linkedin',
            'google-plus' => 'fa fa-google-plus',
            'dribbble' => 'fa fa-dribbble',
            'rss' => 'fa fa-rss'
        ); 
       	
       	if( $social_links != '' ){

	        echo '<div class="brane_social_icons">';

	            echo '<ul>';

	            foreach ( $social_links as $key => $value ) {
	                
	                if( isset( $value[ 'show' ] ) && $value[ 'show' ] == 'on' ) {

	                    $url = $value[ 'href' ];
	                   
	                    $icon = $icons[ $value[ 'title' ] ];                    

	                    echo '<li><a href="'. esc_url( $url ) .'" target="_blank"><i class="' . esc_attr( $icon ) . '"></i></a></li>';
	                }
	            }

	            echo '</ul>';

	        echo '</div>';
	    }
    }  

	// =====================================================================>
	// 		Posts Elements
	// =====================================================================>
	/**
	 * Post Title
	 *
	 * Prints post title
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_permalink(), the_title()
	 * @return void
	 */
	public function post_title() {

		$bef = '<h2 class="brane_post_title"><a href="' . esc_url( get_permalink() ) . '">';
		$af = '</a></h2>';

		if( in_array( 'single-post', brane()->node->current_template() ) ){

			$bef = '<h2 class="brane_post_title">';
			$af = '</h2>';

		}
		
		if( get_the_title() == '' && !is_single() ){

			echo '<h2 class="brane_post_title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html__('View Post', 'brane_lang') . '</a></h2>';

		}else{
			
			the_title( $bef, $af );

		}
	}

	/**
	 * Post Author
	 *
	 * Prints post author
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_avatar(), get_the_author_meta(), the_author_posts_link()
	 * @return void
	 */
	public function post_author() {

		$size = '24';

		$size = apply_filters( 'post_author_avatar_size', $size );

		$size = intval( $size );

		echo '<div class="brane-author">';
			
			echo '<span class="entry-avatar">' . get_avatar( get_the_author_meta('ID'), $size ) . '</span>';

			echo '<span class="entry-author">' . esc_html_e('By ', 'brane_lang') . the_author_posts_link() . '</span>';

		echo '</div>';
	}

	/**
	 * Post Date
	 *
	 * Prints post date
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_the_date(), get_day_link(), get_the_time(), get_post_type()
	 * @return void
	 */
	public function post_date() {

		?>
		<span class="entry-date"> 

			<a href="<?php echo esc_url(get_day_link( get_the_time('Y'), get_the_time('m'), get_the_time('d') )); ?>">
				
				<?php echo get_the_date('j M, Y'); ?> 
			</a>

		</span>		

		<?php 	
	}

	/**
	 * Post Categories
	 *
	 * Prints post categories
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_categories()
	 * @param $postID
	 * @return void / string
	 */
	public function post_categories( $postID = '') {

		$cat_array = brane()->get_categories( $postID );

		foreach ( $cat_array as $name => $url ) {

			echo '<a href="' . esc_url( $url ) . '">' . esc_html( $name ) . '</a>';
		}	
	}

	/**
	 * Post Taxonomies
	 *
	 * Prints custom post categories
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_the_terms(), get_term_link(), is_wp_error(), esc_url()
	 * @return void
	 */
	public function post_taxonomies( $postID, $taxonomy ) {

		$terms = get_the_terms( $postID, $taxonomy );

		foreach ( $terms as $term ) {

		    // The $term is an object, so we don't need to specify the $taxonomy.
		    $term_link = get_term_link( $term );
			   
		    // If there was an error, continue to the next term.
		    if ( is_wp_error( $term_link ) ) {

		        continue;

	    	}

		    // We successfully got a link. Print it out.
		    echo '<a href="' . esc_url( $term_link )  . '">' . esc_html( $term->name ) . '</a>';
		}
			
	}

	/**
	 * Post Tags
	 *
	 * Prints post tags
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_the_tags(), get_tag_link()
	 * @return void
	 */
	public function post_tags(){

		global $post;

		$tags = get_the_tags( $post->ID );

		if( is_array( $tags ) ){

			echo '<div class="brane-post-tags"><span class="fa fa-tag"></span>';

			foreach ( $tags as $tag ) {

				$tag_link = get_tag_link( $tag->term_id );
						
				echo "<a href='" . esc_url( $tag_link ) ."' title='" . esc_attr( $tag->name ) ." Tag' class='tag-" . esc_attr( $tag->slug ) ."'>" . esc_html( $tag->name ) ."</a>";
			}

			echo '</div>';
		}		
	}

	/**
	 * Gets the content
	 *
	 * Gets the Content for single posts and pages and exclude gallery
	 *
	 * @access public
	 * @uses get_the_content(), the_content()
	 * @param $post_format
	 * @return array
	 */
	public function get_the_content( $post_format ){

		if( $post_format == 'gallery' ){

			$post_content = get_the_content();

			$post_content = preg_replace( '/(.?)\[(gallery)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)/s', '$1$6', $post_content ); 

			echo apply_filters( 'the_content' , $post_content );

		}else{

			the_content(); 

		}
	}

	/**
	 * Post Gallery
	 *
	 * Prints post Gallery
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_post_gallery(), wp_get_attachment_image_src(), css_image_url()
	 * @param $postID
	 * @return string
	 */
	public function post_gallery( $postID ) {

		// Gets array of Gallery images
		$images_array = get_post_gallery( $postID, false);

		$html = '';

		if( array_key_exists('ids', $images_array) ){

			// Gets images ids
			$images_ids = explode( ',', $images_array[ 'ids' ] );			

			// Build Gallery Slider
			if( isset( $images_array[ 'src' ] ) && is_array( $images_array[ 'src' ] ) ){

				$html = '<div class="brane-gallery-wrapper">
					<span class="fa fa-angle-left"></span>
					<span class="fa fa-angle-right"></span>
					';

					$html .= '<ul class="brane-post-gallery">';

					$i = 0;
					
					$img_size = 'full';
					
					foreach ( $images_ids as $imgID ) {

						$img_data = wp_get_attachment_image_src( $imgID, $img_size );

						if( $i == 0 ){ $html .= '<li class="active">'; }
						else{ $html .= '<li>'; }

						$html .= '<span class="brane-post-image" style="background-image:' . brane()->css_image_url( $img_data[0] ) . '"></span></li>';
						
						$i++;
					}

					$html .= '</ul>';
					
				$html .= '</div>';
			}

		}

		return $html;
	}

	/**
	 * Post Video
	 *
	 * Prints post Video
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_attached_media(), wp_get_attachment_image_src(), css_image_url()
	 * @param $postID
	 * @return string
	 */
	public function post_video( $postID ) {

		global $post, $wp_embed;
		
		$html = '';

		$matches = array(
			'embed' => false,
			'playlist' => false,
			'audio'	=> false
		);

		preg_match('/\[embed(.*)](.*)\[\/embed]/', $post->post_content, $matches['embed']);

		if( count($matches['embed']) > 0 ){
			
			$html = '<div class="brane-post-video">' . $wp_embed->run_shortcode('[embed]'. $matches['embed'][2] .'[/embed]') . '</div>';			

		}

		return $html;
	}
	
	/**
	 * Post Navigation
	 *
	 * Prints post navigation
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses previous_post_link(), next_post_link()
	 * @return void
	 */
	public function post_navigation(){
		
		$link_previous = '<span>' . esc_html__('Previous', 'brane_lang') . '</span>';
		$link_next = '<span>' . esc_html__('Next', 'brane_lang') . '</span>';

		echo '<div class="brane-post-pagination">
				
				<span class="previous left">';
					previous_post_link( '%link', $link_previous );
				echo '</span>

				<span class="next right">';
					next_post_link( '%link', $link_next );
				echo '</span>

			</div>';
	}
	
	/**
	 * Advanced Search Functionality
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses set()
	 * @return string
	 */	
	public function advanced_search_query( $query ) {
		
		if( $query->is_search()  && isset($_GET['post_type'])) {

			// tag search
			$post_type = $_GET['post_type'];

			if (!is_array($post_type)) {
				$post_type = array($post_type);
			}
			$post_type = array_intersect($post_type,get_post_types());
				
			$query->set('post_type', $post_type);		
		}			

		return $query;
	}

	// =====================================================================>
	// 		Comments.php
	// =====================================================================>
	/**
	 * Comments 
	 *
	 * If comments are open or we have at least one comment, load up the comment template
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses comments_open(), get_comments_number(), comments_template()
	 * @return void
	 */
	public function get_comments_template(){

		if ( comments_open( get_the_ID() ) || get_comments_number( get_the_ID() ) ){			
		    comments_template();
		}	
	}

	/**
	 * Comments Form
	 *
	 * Prints comments form
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses wp_get_current_commenter(), get_option(), allowed_tags(), apply_filters(), comment_form()
	 * @return void
	 */
	public function comments_form(){

		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$required_text = sprintf( ' ' . esc_html__('Required fields are marked %s', 'brane_lang'), '<span class="required">*</span>' );

		$fields	=  array(
			'author' => '<div class="brane-author-label col-md-4"><label for="author">' . esc_html__( 'Name', 'brane_lang' ) . '<span>&#42;</span></label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . esc_attr( $aria_req ) . ' placeholder="' . esc_attr__( 'Name', 'brane_lang') .'" /></div>',

			'email' => '<div class="brane-email-label col-md-4"><label for="email">' . esc_html__( 'Email', 'brane_lang' ) . '<span>&#42;</span></label><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . esc_attr( $aria_req ) . ' placeholder="' . esc_attr__( 'Email', 'brane_lang') .'" /></div>',

			'url' => '<div class="brane-website-label col-md-4"><label for="url">' . esc_html__( 'Website', 'brane_lang' ) . '</label><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" placeholder="' . esc_attr__( 'Website', 'brane_lang') .'" /></div>'

		);

		$title_reply ='<span class="leave-comment">'. esc_html__( 'Leave a Comment', 'brane_lang' ).'</span>';

		$comment_form_args = array(
			'fields' => apply_filters( 'comment_form_default_fields', $fields),	
			'title_reply'       => $title_reply,
			'id_submit'         => 'submit',
  			'class_submit'      => 'submit',
  			'name_submit'       => 'submit',
  			'label_submit'      => esc_html__( 'Comment', 'brane_lang' ),
			'title_reply_to'    => esc_html__( 'Leave a Reply to %s', 'brane_lang' ),
			'cancel_reply_link' => esc_html__( 'Cancel Reply', 'brane_lang' ),
			'comment_notes_after' => '<p class="form-allowed-tags">' 
										. sprintf( esc_html__( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'brane_lang' ), ' <code>' . allowed_tags() . '</code>' ) . '
									</p>',
			'comment_field'	=> '<div class="comment-form-field">
									<label for="comment">' . esc_html__( 'Message', 'brane_lang' ) . '<span>&#42;</span></label>
									<textarea id="comment" class="form-control" name="comment" rows="8" ' . esc_attr( $aria_req ) . ' ></textarea>									
								</div>'

		);
		
		echo '<div class="brane-comments-form col-md-12">';

			comment_form( $comment_form_args ); 

		echo '</div>';		
	}
	
	/**
	 * Comments on List
	 *
	 * Prints comments listed out of a Loop ( for widgets, shortcodes... )
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses apply_filters()
	 * @return void
	 */
	public function comments_on_list( $comments_params = '' ){

		global $comment;

		if( $comments_params == '' ){

			$comments_params = array(
				'number' => 3, 
				'status' => 'approve'
			);

		}

		$comments_params[ 'number' ] = intval( $comments_params[ 'number' ] );
		
		// Gets Lists of Comments
		$comments = get_comments( $comments_params );

		if( ! empty($comments) ) {

			foreach( $comments as $comment ){

				brane()->get_template_part( 'elements/comments', 'onlist' );

			}

		}
	}
	
	/**
	 * Comments Number
	 *
	 * Prints number of comments
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_comments_number(), get_the_title()
	 * @return void
	 */
	public function comments_number(){

		echo '<div class="col-md-12"><h3 class="comments_number">';
			printf( _nx( '1 Comment on ', '%1$s Comments on ', get_comments_number(), 'comments title', 'brane_lang' ), number_format_i18n( get_comments_number() ) );
			echo '<span>' . get_the_title() . '</span>';
		echo '</h3></div>';
	}

	/**
	 * Comments Count
	 *
	 * Prints number of comments
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_comments_number()
	 * @return void
	 */
	public function comments_count(){

		echo '<span class="comments_count">';
		echo get_comments_number();	
		echo '</span>';		
	}

	/**
	 * Comments Navigation
	 *
	 * Prints comments navigation
	 *
	 * @since  2.0.0
	 * @access public
	 * @uses get_comment_pages_count(), get_option(), get_template_part()
	 * @return void
	 */
	public function comments_navigation(){

		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // are there comments to navigate through 
			
			brane()->get_template_part( 'elements/comments', 'navigation' );

		} // check for comment navigation 
	}

	/**
	 * Comments List
	 *
	 * Format the comments.
	 *
	 * @since  2.0.0
	 * @access public
	 * @param  $comment, $args, $depth
	 * @uses Brane()->get_template_part()
	 * @return void
	 */
	public function content_comments( $comment, $args, $depth ){

		$GLOBALS['comment'] = $comment;

		$this->comments_args = $args;

		$this->comments_depth = $depth;

		brane()->get_template_part( 'content', 'comments' );		
	}
	
	/**
	 * Add Custom Avatar Field
	 * @author Bill Erickson
	 * @link http://www.billerickson.net/wordpress-custom-avatar/
	 *
	 * @param object $user
	 */
	public function custom_avatar_field( $user ) { ?>
		
		<h3><?php esc_html_e( 'Custom Avatar', 'brane_lang') ?></h3>
		 
		<table>
		<tr>
		<th><label for="brane_custom_avatar"><?php esc_html_e( 'Custom Avatar URL:', 'brane_lang') ?></label></th>
		<td>
		<input type="text" name="brane_custom_avatar" id="brane_custom_avatar" value="<?php echo esc_attr( get_the_author_meta( 'be_custom_avatar', $user->ID ) ); ?>" /><br />
		<span><?php esc_html_e( "Type in the URL of the image you'd like to use as your avatar. This will override your default Gravatar, or show up if you don't have a Gravatar. Image should be 70x70 pixels", 'brane_lang') ?></span>
		</td>
		</tr>
		</table>
		<?php 
	}
	
	/**
	 * Save Custom Avatar Field
	 * @author Bill Erickson
	 * @link http://www.billerickson.net/wordpress-custom-avatar/
	 *
	 * @param int $user_id
	 */
	public function save_custom_avatar_field( $user_id ) {

		if ( !current_user_can( 'edit_user', $user_id ) ) { 

			return false; 
		}
		
		update_user_meta( $user_id, 'brane_custom_avatar', $_POST['brane_custom_avatar'] );
	}
	
	/**
	 * Use Custom Avatar if Provided
	 * @author Bill Erickson
	 * @link http://www.billerickson.net/wordpress-custom-avatar/
	 *
	 */
	public function gravatar_filter( $avatar, $id_or_email, $size, $default, $alt ) {
		
		// If provided an email and it doesn't exist as WP user, return avatar since there can't be a custom avatar
		$email = is_object( $id_or_email ) ? $id_or_email->comment_author_email : $id_or_email;

		if( is_email( $email ) && ! email_exists( $email ) ){

			return $avatar;
		}
		
		$custom_avatar = get_the_author_meta('brane_custom_avatar');

		if ($custom_avatar) {

			$return = '<img src="' . esc_url( $custom_avatar ) . '" width="'. esc_attr( $size ) .'" height="' . esc_attr( $size ) .'" alt="'. esc_attr( $alt ) .'" />';
		
		}elseif ($avatar) {

			$return = $avatar;

		}else {

			$return = '<img src="' . esc_attr( $default ) .'" width="'. esc_attr( $size ) .'" height="'. esc_attr( $size ) . '" alt="'. esc_attr( $alt ) . '" />';
		}

		return $return;
	}

}


