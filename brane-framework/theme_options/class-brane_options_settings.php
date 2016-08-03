<?php
/**
 * Brane_Options_Settings class definition
 *
 * Creates Menu options panel and Loads and updates theme options array
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

class Brane_Options_Settings {

	/**
 	 * Theme Options admin menu slug
	 */
	public $ot_theme_options = 'brane-theme-options';
	
	public $options_id = 'brane_ot_options_956231';

	/**
 	 * Option Tree configuration
	 *
	 * Ask OT to behave as part of the theme
	 * Force redirect to set options on theme's first load
	 * Allow TinyMCE on metaboxes ( bug: metabox drag breaks wysiwyg )
	 *
	 * @access public
	 * @return void
	 */
	public function __construct( $args = array() ){
		
		if( isset( $args[ 'ot_theme_options' ] ) ) {
			$this->ot_theme_options = $args[ 'ot_theme_options' ];
		}

		if( isset( $args[ 'options_id' ] ) ) {
			$this->options_id = $args[ 'options_id' ];
		}

		add_filter( 'ot_options_id', array( $this, 'get_options_id' ) );

		add_filter( 'ot_show_options_ui', '__return_false' );
		
		add_filter( 'ot_theme_options_parent_slug', array( $this, 'return_empty' ) );

		add_filter( 'ot_register_pages_array', array( $this, 'ot_register_pages_array' ) );

		//add_filter( 'ot_show_pages', '__return_false' );

		add_filter( 'ot_override_forced_textarea_simple', '__return_true' );

		add_filter( 'ot_theme_options_menu_slug', array( $this, 'get_ot_theme_options' ) );

		//add_action( 'admin_menu', array( $this, 'options_subpages' ), 11 );

		/**
		 * Upload Theme Options to database.
		 */
		add_action( 'admin_init', array( $this, 'theme_load' ) ); 

		/**
		 * Adds Metaboxes
		 */
		add_action( 'admin_init', array( $this, 'metaboxes' ) );

		/* Page & Menu Title Filters */
		add_filter( 'ot_theme_options_page_title', array( $this,'page_title_ot') );
		add_filter( 'ot_theme_options_menu_title', array( $this,'page_title_ot') );

		/* Options menu Icon Filter */
		add_filter( 'ot_theme_options_icon_url', array( $this,'menu_icon_ot') );

		/* Custom Option types */
		add_filter( 'ot_option_types_array', 'add_custom_option_types' );
		
	}

	/**
 	 * Return empty
	 *
	 * @access public
	 * @return ''
	 */
	public function return_empty(){
		return '';
	}

	/**
	 * Get Theme Options
	 * 
	 * @access public
	 * @return string
	 */
	public function get_ot_theme_options() {

		return $this->ot_theme_options;
	}

	/**
	 * Options id
	 *
	 * @access public
	 * @return string
	 */
	public function get_options_id() {

		return $this->options_id;
	}

	/**
	 * Creates Theme Options sub menu pages
	 * 
	 * Hooked to admin_menu
	 */
	/*public function options_subpages(){
		
		add_theme_page(
		    esc_html__( 'Create Sidebars', 'brane_lang' ),           // Page title
		    esc_html__( 'Create Sidebars', 'brane_lang' ),           // Menu title
		    'edit_theme_options',                   					// Capability
		    'brane_custom_sidebars',                            		// Menu slug
		    array('sidebar_generator','admin_page') 					// Callback
		);
	}*/

	/**
	 * Theme Options Page & Menu Title
	 * 
	 * @access public
	 * @return string
	 */
	public function page_title_ot(){

		return sprintf( esc_html__( '%s Options', 'brane_lang' ), wp_get_theme() );
	}

	/**
	 * Theme Options Menu Icon
	 * 
	 * @access public
	 * @return string
	 */
	public function menu_icon_ot(){

		return 'dashicons-admin-generic';
	}

	/**
	 * Creates Theme Options backup page
	 * 
	 * Hooked to ot_register_pages_array
	 * 
	 * @return $ot_register_pages_array
	 */
	public function ot_register_pages_array($ot_register_pages_array) {
		
		// Remove Option tree documentation pages
		foreach ($ot_register_pages_array as $key => $value) {

			$value_id = '';

			if(is_array($value) && isset($value['id'])){

				$value_id = $value['id'];

			}
			
			if($value_id == 'ot'){

				unset($ot_register_pages_array[$key]);

			}elseif ($value_id == 'settings') {

				unset($ot_register_pages_array[$key]);

				//$ot_register_pages_array[] = array();
				// Adding Backup submenu page
				$ot_register_pages_array[] = array(
					'id'              => 'settings',
					'parent_slug'     => $this->ot_theme_options,
					'page_title'      => esc_html__( 'Backup', 'brane_lang' ),
					'menu_title'      => esc_html__( 'Backup', 'brane_lang' ),
					'capability'      => 'edit_theme_options',
					'menu_slug'       => 'brane-settings',
					'icon_url'        => null,
					'position'        => null,
					'updated_message' => sprintf( esc_html__( '%s Options Updated', 'brane_lang' ), wp_get_theme()),
					'reset_message'   => sprintf( esc_html__( '%s Options Reset', 'brane_lang' ), wp_get_theme()),
					'button_text'     => esc_html__( 'Save Settings', 'brane_lang' ),
					'show_buttons'    => false,
					'sections'        => array(
						array(
							'id'          => 'import',
							'title'       => esc_html__( 'Import', 'brane_lang' )
						),
						array(
							'id'          => 'export',
							'title'       => esc_html__( 'Export', 'brane_lang' )
						),
						array(
							'id'          => 'layouts',
							'title'       => esc_html__( 'Layouts', 'brane_lang' )
						)
					),
					'settings'        => array(
						array(
							'id'          => 'import_data_text',
							'label'       => sprintf( esc_html__( '%s Options', 'brane_lang' ), wp_get_theme()),
							'type'        => 'import-data',
							'section'     => 'import'
						),
						array(
							'id'          => 'import_layouts_text',
							'label'       => esc_html__( 'Layouts', 'brane_lang' ),
							'type'        => 'import-layouts',
							'section'     => 'import'
						),
						array(
							'id'          => 'export_data_text',
							'label'       => sprintf( esc_html__( '%s Options', 'brane_lang' ), wp_get_theme()),
							'type'        => 'export-data',
							'section'     => 'export'
						),
						array(
							'id'          => 'export_layout_text',
							'label'       => esc_html__( 'Layouts', 'brane_lang' ),
							'type'        => 'export-layouts',
							'section'     => 'export'
						),
						array(
							'id'          => 'modify_layouts_text',
							'label'       => esc_html__( 'Layout Management', 'brane_lang' ),
							'type'        => 'modify-layouts',
							'section'     => 'layouts'
						)
					)
				);
			}
		}
		return $ot_register_pages_array;
	}
	
	/**
 	 * Loads files options to data base
	 *
	 * @access public
	 * @return void
	 */
	public function theme_load(){

		/* OptionTree is not loaded yet, or this is not an admin request */
		if ( ! function_exists( 'ot_settings_id' ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		
		/**
		 * Custom settings array that will eventually be 
		 * passed to the OptionTree Settings API Class.
		 */
		$custom_settings = array( 		
			/*'contextual_help' => array( 
		      'content'       => array( 
		        array(
		          'id'        => 'option_types_help',
		          'title'     => esc_html__( 'Option Types3', 'brane_lang' ),
		          'content'   => '<p>' . esc_html__( 'Help content goes here!', 'brane_lang' ) . '</p>'
		        ),
		        array(
		          'id'        => 'option_types_help1',
		          'title'     => esc_html__( 'Option Types3', 'brane_lang' ),
		          'content'   => '<p>' . esc_html__( 'Help content goes here!', 'brane_lang' ) . '</p>'
		        ),
		        array(
		          'id'        => 'option_types_help2',
		          'title'     => esc_html__( 'Option Types3', 'brane_lang' ),
		          'content'   => '<p>' . esc_html__( 'Help content goes here!', 'brane_lang' ) . '</p>'
		        ),
		        array(
		          'id'        => 'option_types_help3',
		          'title'     => esc_html__( 'Option Types3', 'brane_lang' ),
		          'content'   => '<p>' . esc_html__( 'Help content goes here!', 'brane_lang' ) . '</p>'
		        )
		      ),
		      'sidebar'       => '<p>' . esc_html__( 'Sidebar content goes here!', 'brane_lang' ) . '</p>'
		    ),	*/
		    'sections'        => array(),
		    'settings'        => array()
		);

  		include_once BRANE_TEMPLATE_DIR . 'theme_customs/options_files/sections.php';

  		$options_files = brane()->get_config( 'options_files', array() );

  		foreach ( $options_files as $file ) {
  			include_once BRANE_TEMPLATE_DIR . 'theme_customs/options_files/' . $file . '.php';
  		}

		update_option( ot_settings_id(), $custom_settings ); 

		/* Lets OptionTree know the UI Builder is being overridden */
		global $ot_has_custom_theme_options;
		$ot_has_custom_theme_options = true;
	}

	/**
 	 * Loads files metaboxes to data base and register metaboxes
	 *
	 * @access public
	 * @return void
	 */
	public function metaboxes() {

  		$metaboxes_files = brane()->get_config( 'metaboxes_files', array() );

  		$files = $metaboxes_files;

		if( class_exists( 'Brane_Post_Type' ) ){

  			$custom_posts_metaboxes_files = brane()->get_config( 'custom_posts_metaboxes_files', array() );

			$files = array_merge( $metaboxes_files, $custom_posts_metaboxes_files );
		
		}

		if( class_exists('OT_Loader') ){

			foreach ( $files as $file ) {

				ot_register_meta_box( require_once BRANE_TEMPLATE_DIR . 'theme_customs/options_metaboxes/' . $file . '.php' );

			}
		}

	}

	/**
	 * Filter to add custom option types.
	 *
	 * @param     array     An array of option types.
	 * @return    array
	 */
	public function add_custom_option_types( $types ) {

		$types['preload'] = esc_html__('Preload', 'brane_lang');

		return $types;
	}

}

/** 
 * Heading custom option type
 *
 * @param $args
 * @return void
 **/
function ot_type_heading( $args = array() ) {

	return '';
  
}

/** 
 * Preload custom option type
 *
 * @param $args
 * @return void
 **/
function ot_type_preload( $args = array() ) {

	/* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="format-setting type-radio-image ' . ( esc_html( $has_desc ) ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo esc_html( $has_desc ) ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="format-setting-inner">';
      
        /* build radio */
        foreach ( (array) $field_choices as $key => $choice ) {
        	
          echo '<div class="option-tree-ui-radio-images brane-preload-option">';
            echo '<p style="display:none"><input type="radio" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '"' . checked( $field_value, $choice['value'], false ) . ' class="option-tree-ui-radio option-tree-ui-images" /><label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">' . esc_attr( $choice['label'] ) . '</label></p>';
           
            echo '<div class="brane_ot_preload_preview option-tree-ui-radio-image ' . esc_attr( $field_class ) . ( $field_value == $choice['value'] ? ' option-tree-ui-radio-image-selected' : '' ) . ' brane_spinner_'. esc_attr( $key + 1 ) .'" id="preview-' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">
					
						<div class="brane_spinner">';
						
						$preload_attributes = include BRANE_TEMPLATE_DIR . 'framework/assets/spinners.php';

						if( is_array($preload_attributes) ){
							foreach ( $preload_attributes as $type => $atributes) {
								
								if( $choice['value'] == $type ){
									echo '<style scoped>' . $atributes['css'] . '</style>';
									
									echo wp_kses_post( $atributes['html'] );
								}
							}
						}

					echo '</div>

				</div>';
            echo '</div>';

        }
      
      echo '</div>';
    
    echo '</div>';		    
  
}

/** 
 * Border custom option position types
 *
 * @param $args
 * @return void
 **/
function ot_recognized_border_position_types( $field_id = '' ) {

    return apply_filters( 'ot_recognized_border_position_types', array(
      'none' => 'None',
      'all' => 'All',
      'top'  => 'Top',
      'bottom' => 'Bottom',
      'left' => 'Left',
      'right' => 'Right',
    ), $field_id );

}

/** 
 * Border custom option type
 *
 * @param $args
 * @return void
 **/
function ot_type_custom_border( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="format-setting type-border ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

      /* description */
      echo esc_html( $has_desc ) ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      /* format setting inner wrapper */
      echo '<div class="format-setting-inner">';

        /* allow fields to be filtered */
        $ot_recognized_border_fields = apply_filters( 'ot_recognized_border_fields', array(
          'position',
          'width',
          'unit',
          'style',
          'color'
        ), $field_id );

        /* build border width */
        if ( in_array( 'position', $ot_recognized_border_fields ) ) {

			echo '<div class="ot-option-group ot-option-group--one-fourth">';
          
				echo '<select name="' . esc_attr( $field_name ) . '[position]" id="' . esc_attr( $field_id ) . '-position" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

				  echo '<option value="">' . esc_html__( 'position', 'brane_lang' ) . '</option>';

				  foreach ( ot_recognized_border_position_types( $field_id ) as $position ) {
				    echo '<option value="' . esc_attr( $position ) . '"' . ( isset( $field_value['position'] ) ? selected( $field_value['position'], $position, false ) : '' ) . '>' . esc_attr( $position ) . '</option>';
				  }

				echo '</select>';

			echo '</div>';

        }

        /* build border width */
        if ( in_array( 'width', $ot_recognized_border_fields ) ) {

          $width = isset( $field_value['width'] ) ? esc_attr( $field_value['width'] ) : '';

          echo '<div class="ot-option-group ot-option-group--one-sixth"><input type="text" name="' . esc_attr( $field_name ) . '[width]" id="' . esc_attr( $field_id ) . '-width" value="' . esc_attr( $width ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'width', 'brane_lang' ) . '" /></div>';

        }

        /* build unit dropdown */
        if ( in_array( 'unit', $ot_recognized_border_fields ) ) {
          
          echo '<div class="ot-option-group ot-option-group--one-fourth">';
          
            echo '<select name="' . esc_attr( $field_name ) . '[unit]" id="' . esc_attr( $field_id ) . '-unit" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
    
              echo '<option value="">' . esc_html__( 'unit', 'brane_lang' ) . '</option>';
    
              foreach ( ot_recognized_border_unit_types( $field_id ) as $unit ) {
                echo '<option value="' . esc_attr( $unit ) . '"' . ( isset( $field_value['unit'] ) ? selected( $field_value['unit'], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
              }
    
            echo '</select>';
          
          echo '</div>';
  
        }
        
        /* build style dropdown */
        if ( in_array( 'style', $ot_recognized_border_fields ) ) {
          
          echo '<div class="ot-option-group ot-option-group--one-fourth">';
          
            echo '<select name="' . esc_attr( $field_name ) . '[style]" id="' . esc_attr( $field_id ) . '-style" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
    
              echo '<option value="">' . esc_html__( 'style', 'brane_lang' ) . '</option>';
    
              foreach ( ot_recognized_border_style_types( $field_id ) as $key => $style ) {
                echo '<option value="' . esc_attr( $key ) . '"' . ( isset( $field_value['style'] ) ? selected( $field_value['style'], $key, false ) : '' ) . '>' . esc_attr( $style ) . '</option>';
              }
    
            echo '</select>';
          
          echo '</div>';
  
        }
        
        /* build color */
        if ( in_array( 'color', $ot_recognized_border_fields ) ) {
          
          echo '<div class="option-tree-ui-colorpicker-input-wrap">';
            
            /* colorpicker JS */      
            echo '<script>jQuery(document).ready(function($) { OT_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker"); });</script>';
            
            /* set color */
            $color = isset( $field_value['color'] ) ? esc_attr( $field_value['color'] ) : '';
            
            /* input */
            echo '<input type="text" name="' . esc_attr( $field_name ) . '[color]" id="' . $field_id . '-picker" value="' . $color . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" />';
          
          echo '</div>';
        
        }
      
      echo '</div>';

    echo '</div>';

}