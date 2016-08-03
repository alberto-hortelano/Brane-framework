<?php
/**
 * Brane_Node main class Definition
 *
 * The Brane_Node. 
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

class Brane_Node {

	public $title = false;

	public $woo = false;

	public $title_shop = false;

	public $breadcrumb = array();

	public $current_template = array();

	public $header = ''; 

	public $content = array();

	public $footer;

	function __construct( $args = array() ) {

		add_action( 'wp', array( $this, 'current_template' ) );

		add_action( 'brane_header_content', array($this, 'header') );

		add_action( 'brane_before_content', array( $this, 'beforeContent') );

		add_action( 'brane_after_content', array( $this, 'afterContent'), 20 );

	}

	/**
 	 * Builds Header
	 *
	 * @access public
	 * @return void
	 */
	public function header() { 
        
        // Extended on Theme Node
	}
	
	/**
 	 * Builds Before Content
	 *
	 * @access public
	 * @return void
	 */
	public function beforeContent() {
		
		echo '<div id="main-content" class="clearfix">';
	}

	/**
 	 * Builds After Content
	 *
	 * @access public
	 * @return void
	 */
	public function afterContent() {
		
		echo '</div>';
	}

	/**
 	 * Builds Footer
	 *
	 * @access public
	 * @return void
	 */
	public function footer() {
		
		// Extended on Theme Node	
	}

	/**
 	 * Get current template, store Page Title & Breadcrumbs
	 *
	 * @access public
	 * @uses get_queried_object(), get_queried_object_id(), is_front_page(), home_url(), is_home(), is_page(), the_title(), is_single(), is_archive(), is_post_type_archive(), get_query_var(), is_tax(), is_category(), is_tag(), get_term_by(), get_term_link(), single_tag_title(), is_author(), sanitize_html_class(), get_the_author_meta(), is_date(), is_year(), the_time(), is_month(), is_day(), is_time(), is_search(), get_search_query(), is_404(), is_attachment() 
	 * @return string | array
	 */
	public function current_template(){
				
		global $post;
		
		$object = get_queried_object();
		$object_id = get_queried_object_id();
		$title = ''; 

		if( class_exists('Woocommerce') ){ 
			
			$this->woo = true;
			$this->title_shop = get_the_title( wc_get_page_id( 'shop' ) );
		}

		// Front page of the site.
		if ( is_front_page() ) {

			$this->current_template[] = 'home';

			$this->title = the_title( '', '', false );

		}else{

			$this->breadcrumb[ 'home' ] = array(
				'title' => esc_html__( 'Home', 'brane_lang' ),
				'url' => home_url()
			);

		}

		// Blog page.
		if ( is_home() ) {

			$this->current_template[] = 'blog';

			$this->title = esc_html__( 'Blog' , 'brane_lang' );

		} elseif ( is_page() ) {// Singular views.

			$this->current_template[] = 'page';
			$this->current_template[] = "page-{$object->post_type}";
			$this->current_template[] = "page-{$object->post_type}-{$object_id}";

			$this->breadcrumb[ 'ancestors' ] = brane()->ancestor();
			$this->title = the_title( '', '', false );

			$this->current_template[] = get_page_template_slug();

			if( $this->woo && ( is_cart() || is_account_page() || is_checkout() ) ){
				
				$this->breadcrumb[ 'shop' ] = array(
					get_the_title( wc_get_page_id('shop') ) => get_permalink( wc_get_page_id('shop') )
				);
			}

		} elseif ( is_single() ) {// Singular views.

			$this->current_template[] = 'single';
			$this->current_template[] = "single-{$object->post_type}";
			$this->current_template[] = "single-{$object->post_type}-{$object_id}";

			$this->breadcrumb[ 'categories' ] = brane()->get_categories();
			$this->title = the_title( '', '', false );
			$this->breadcrumb[] = $this->title;

		}
		
		// Search results.
		elseif ( is_search() ) {

			$this->current_template[] = 'search';
			$this->breadcrumb['search'] = sprintf( esc_html__( ' Search results: %s', 'brane_lang'), get_search_query() );

			$allsearch = new WP_Query( "s=" . get_search_query() . "&showposts=-1" ); $count = $allsearch->post_count; wp_reset_postdata(); 

			$this->title = sprintf( esc_html__( '%d Results for: %s', 'brane_lang' ), $count, '<span>' . get_search_query() . '</span>' );
		}

		elseif ( is_archive() ) {// Archive views.

			$this->current_template[] = 'archive';

			// Post type archives.
			if ( is_post_type_archive() ) {

				$post_type = get_query_var( 'post_type' );

				if ( is_array( $post_type ) ){
					reset( $post_type );
				}

				if( !is_array( $post_type )){
					$this->current_template[] = "archive-{$post_type}";
				}

			}

			if( in_array( 'archive-product', $this->current_template) ){

				$this->title = get_the_title( wc_get_page_id( 'shop' ) );

			}

			// Taxonomy archives.
			if ( is_tax() || is_category() || is_tag() ) {
				
				$this->current_template[] = 'taxonomy';
				
				$this->current_template[] = "taxonomy-{$object->taxonomy}";

				$slug = 'post_format' == $object->taxonomy ? str_replace( 'post-format-', '', $object->slug ) : $object->slug;

				$this->current_template[] = "taxonomy-{$object->taxonomy}-" . sanitize_html_class( $slug, $object->term_id );

				if( is_category() ){

					$this->current_template[] = 'taxonomy-category';

					$this->breadcrumb[ 'parents' ] = brane()->get_category_parents();
					$this->title = sprintf( esc_html__( 'Category Archives: %s', 'brane_lang' ), single_cat_title( '', false ) );

				}elseif ( is_tax() ) {
					
					$this->current_template[] = 'taxonomy-term';

					$id = get_queried_object()->term_id;
					$this->breadcrumb[ 'parents' ] = brane()->get_category_parents( $id );
					//$this->title = sprintf( esc_html__( 'Taxonomy Archives: %s', 'brane_lang' ), single_term_title( '', false ) );
					$this->title = single_term_title( '', false );

					if( in_array( 'taxonomy-product_tag', $this->current_template ) ) {

						$this->title = sprintf( esc_html__( 'Products Tagged: %s', 'brane_lang' ), single_tag_title( '', false ) );
					}
					

				}elseif ( is_tag() ) {
					
					$this->current_template[] = 'taxonomy-tag';

					$this->breadcrumb[] = array(
						single_tag_title( '', false ) => ''
					);

					$this->title = sprintf( esc_html__( 'Archives Tagged: %s', 'brane_lang' ), single_tag_title( '', false ) );
				}
				
			}

			// User/author archives.
			if ( is_author() ) {

				$user_id = get_query_var( 'author' );
				$this->current_template[] = 'user';
				$this->current_template[] = 'user-' . sanitize_html_class( get_the_author_meta( 'display_name', $user_id ), $user_id );

				$this->breadcrumb[ 'author' ] = get_the_author_meta( 'display_name', $user_id );

				$this->title = sprintf( esc_html__( 'Author: %s', 'brane_lang' ), get_the_author_meta( 'display_name', $user_id ) );
			}

			// Date archives.
			if ( is_date() ) {

				$this->current_template[] = 'date';

				if ( is_year() ){

					$this->current_template[] = 'year';
					$this->breadcrumb[ 'date' ] = get_the_time('Y');

					$this->title = sprintf( esc_html__( 'Yearly Archives: %s', 'brane_lang' ), get_the_time( 'Y' ) );

				}elseif ( is_month() ){

					$this->current_template[] = 'month';
					$this->breadcrumb[ 'date' ] = get_the_time('F, Y');

					$this->title = sprintf( esc_html__( 'Monthly Archives: %s', 'brane_lang' ), get_the_time( 'F, Y' ) );

				}elseif ( get_query_var( 'w' ) ){

					$this->current_template[] = 'week';
					$this->breadcrumb[ 'date' ] = get_the_time('W');

					$this->title = sprintf( esc_html__( 'Weekly Archives: %s', 'brane_lang' ), get_the_time( 'W' ) );

				}elseif ( is_day() ){

					$this->current_template[] = 'day';
					$this->breadcrumb[ 'date' ] = get_the_time('F jS, Y');

					$this->title = sprintf( esc_html__( 'Daily Archives: %s', 'brane_lang' ), get_the_time( 'F j, Y' ) );
				}
			}

			// Time archives.
			if ( is_time() ) {
				$this->current_template[] = 'time';

				if ( get_query_var( 'hour' ) )
					$this->current_template[] = 'hour';

				if ( get_query_var( 'minute' ) )
					$this->current_template[] = 'minute';
			}
		}

		// Error 404 pages.
		elseif ( is_404() ) {

			$this->current_template[] = 'error-404';
			$this->breadcrumb[ 'error' ] = esc_html__('404 - Not Found', 'brane_lang');

			$this->title = esc_html__( 'Error', 'brane_lang' );
		}

		// Attachment.
		elseif ( is_attachment() ) {

			$this->current_template[] = 'attachment';

			$this->breadcrumb[ 'attachment' ] = the_title();

			$this->title = the_title();
		}

		// Paged
		if ( get_query_var('paged') ) {

			$this->current_template[] = 'paged';

			$this->breadcrumb[ 'paged' ] = _x('Page', 'breadcrumbs', 'brane_lang') . ' ' . get_query_var('paged');

        }

		return array_map( 'esc_attr', apply_filters( 'hybrid_context', array_unique( $this->current_template ) ) );
	}

}

