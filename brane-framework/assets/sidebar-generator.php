<?php
/*
Plugin Name: Sidebar Generator
Plugin URI: http://www.getson.info
Description: This plugin generates as many sidebars as you need. Then allows you to place them on any page you wish. Version 1.1 now supports themes with multiple sidebars. 
Version: 1.1.0
Author: Kyle Getson
Author URI: http://www.kylegetson.com
Copyright (C) 2009 Kyle Robert Getson
*/

/*
Copyright (C) 2009 Kyle Robert Getson, kylegetson.com and getson.info

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class sidebar_generator {
	
	function sidebar_generator(){
		add_action('init',array('sidebar_generator','init'));
		//add_action('admin_menu',array('sidebar_generator','admin_menu'));
		add_action('wp_ajax_add_sidebar', array('sidebar_generator','add_sidebar') );
		add_action('wp_ajax_remove_sidebar', array('sidebar_generator','remove_sidebar') );
			
		//edit posts/pages
		add_action('edit_form_advanced', array('sidebar_generator', 'edit_form'));
		add_action('edit_page_form', array('sidebar_generator', 'edit_form'));
		
		//save posts/pages
		add_action('edit_post', array('sidebar_generator', 'save_form'));
		add_action('publish_post', array('sidebar_generator', 'save_form'));
		add_action('save_post', array('sidebar_generator', 'save_form'));
		add_action('edit_page_form', array('sidebar_generator', 'save_form'));

	}
	
	static function init(){
		//go through each sidebar and register it
		$sb_generator = new sidebar_generator();
	    $sidebars = $sb_generator->get_sidebars();
	    
	    $widget_title_size = 'h3';

	    if(is_array($sidebars)){
			foreach($sidebars as $sidebar){
				$sidebar_class = sidebar_generator::name_to_class($sidebar);
				register_sidebar(array(
					'name'=>$sidebar,
					'id'=>$sidebar,
					'description'   => '',
			    	'before_widget' => '<section id="%1$s" class="brane_widget_section widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<' . tag_escape( $widget_title_size ) .' class="brane_widget_title widget-title">',
					'after_title'   => '</'. tag_escape( $widget_title_size) .'>',
		    	));
			}
		}
	}
	
	static function add_sidebar(){
		$sidebars = sidebar_generator::get_sidebars();
		$name = str_replace(array("\n","\r","\t"),'',$_POST['sidebar_name']);
		$id = sidebar_generator::name_to_class($name);
		if(isset($sidebars[$id])){
			die("alert('Sidebar already exists, please use a different name.')");
		}
		
		$sidebars[$id] = $name;
		sidebar_generator::update_sidebars($sidebars);
		
	}
	
	static function remove_sidebar(){
		$sidebars = sidebar_generator::get_sidebars();
		$name = str_replace(array("\n","\r","\t"),'',$_POST['sidebar_name']);
		$id = sidebar_generator::name_to_class($name);
		if(!isset($sidebars[$id])){
			echo "Sidebar ".$id." does not exist.";
		}
		$row_number = $_POST['row_number'];
		unset($sidebars[$id]);
		sidebar_generator::update_sidebars($sidebars);
		echo "Sidebar ".$id." removed.";
	}
	
	static function admin_page(){
		?>
		<script>
		var brane_templateDir = "<?php echo site_url() ?>";
		</script>
		<div class="wrap settings-wrap" id="brane_sidebars_page">
			<h2><?php esc_html_e('Add Custom Sidebars','brane_lang') ?></h2>
			<br />
			<table class="widefat page" id="sbg_table" >
				<tr>
					<th><?php esc_html_e('Name','brane_lang') ?></th>
					<th><?php esc_html_e('CSS Class','brane_lang') ?></th>
					<th><?php esc_html_e('Remove','brane_lang') ?></th>
				</tr>
				<?php
				$sidebars = sidebar_generator::get_sidebars();
				if(is_array($sidebars) && !empty($sidebars)){
					$cnt=0;
					foreach($sidebars as $sidebar){
						$alt = ( $cnt%2 == 0 ? 'alternate' : '' );
				?>
				<tr class="<?php echo esc_html( $alt ) ?>">
					<td class="brane_sidebar_name"><a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php echo esc_html( $sidebar ); ?></a></td>
					<td><?php echo sidebar_generator::name_to_class($sidebar);?></td>
					<td><a href="javascript:void(0);" title="Remove this sidebar" class="brane_remove"><?php esc_html_e('Remove Sidebar','brane_lang') ?></a></td>
				</tr>
				<?php
						$cnt++;
					}
				}
				?>
			</table>
			<br /><br />
			<div class="add_sidebar">
				<a class="button-primary" href="javascript:void(0);" title="Add a sidebar">
					<i class="dashicons dashicons-plus-alt"></i>
					<?php esc_html_e('Add Sidebar','brane_lang') ?>
				</a>
			</div>
		</div>
		<?php
	}
	
	/**
	 * for saving the pages/post
	*/
	static function save_form($post_id){
		$is_saving = array();
		if(array_key_exists('sbg_edit',$_POST))$is_saving = $_POST['sbg_edit'];
		if(!empty($is_saving)){
			delete_post_meta($post_id, 'sbg_selected_sidebar');
			delete_post_meta($post_id, 'sbg_selected_sidebar_replacement');
			add_post_meta($post_id, 'sbg_selected_sidebar', $_POST['sidebar_generator']);
			add_post_meta($post_id, 'sbg_selected_sidebar_replacement', $_POST['sidebar_generator_replacement']);
		}		
	}
	
	static function edit_form(){
	    global $post;
	    $post_id = $post;
	    if (is_object($post_id)) {
	    	$post_id = $post_id->ID;
	    }
	    $selected_sidebar = get_post_meta($post_id, 'sbg_selected_sidebar', true);
	    if(!is_array($selected_sidebar)){
	    	$tmp = $selected_sidebar; 
	    	$selected_sidebar = array(); 
	    	$selected_sidebar[0] = $tmp;
	    }
	    $selected_sidebar_replacement = get_post_meta($post_id, 'sbg_selected_sidebar_replacement', true);
		if(!is_array($selected_sidebar_replacement)){
	    	$tmp = $selected_sidebar_replacement; 
	    	$selected_sidebar_replacement = array(); 
	    	$selected_sidebar_replacement[0] = $tmp;
	    }		
	}
	
	/**
	 * called by the action get_sidebar. this is what places this into the theme
	 */
	static function get_sidebar($name="0"){
		if(!is_singular()){
			if($name != "0"){
				dynamic_sidebar($name);
			}else{
				dynamic_sidebar();
			}
			return;//dont do anything
		}
		global $wp_query;
		$post = $wp_query->get_queried_object();
		$selected_sidebar = get_post_meta($post->ID, 'sbg_selected_sidebar', true);
		$selected_sidebar_replacement = get_post_meta($post->ID, 'sbg_selected_sidebar_replacement', true);
		$did_sidebar = false;
		//this page uses a generated sidebar
		if($selected_sidebar != '' && $selected_sidebar != "0"){
			echo "\n\n<!-- begin generated sidebar -->\n";
			if(is_array($selected_sidebar) && !empty($selected_sidebar)){
				for($i=0;$i<sizeof($selected_sidebar);$i++){					
					
					if($name == "0" && $selected_sidebar[$i] == "0" &&  $selected_sidebar_replacement[$i] == "0"){
						dynamic_sidebar();//default behavior
						$did_sidebar = true;
						break;
					}elseif($name == "0" && $selected_sidebar[$i] == "0"){
						dynamic_sidebar($selected_sidebar_replacement[$i]);//default behavior
						$did_sidebar = true;
						break;
					}elseif($selected_sidebar[$i] == $name){
						$did_sidebar = true;
						dynamic_sidebar($selected_sidebar_replacement[$i]);//default behavior
						break;
					}
				}
			}
			if($did_sidebar == true){
				echo "\n<!-- end generated sidebar -->\n\n";
				return;
			}
			//go through without finding any replacements, lets just send them what they asked for
			if($name != "0"){
				dynamic_sidebar($name);
			}else{
				dynamic_sidebar();
			}
			echo "\n<!-- end generated sidebar -->\n\n";
			return;			
		}else{
			if($name != "0"){
				dynamic_sidebar($name);
			}else{
				dynamic_sidebar();
			}
		}
	}
	
	/**
	 * replaces array of sidebar names
	*/
	static function update_sidebars($sidebar_array){
		$sidebars = update_option('sbg_sidebars',$sidebar_array);
	}	
	
	/**
	 * gets the generated sidebars
	*/
	static function get_sidebars(){
		$sidebars = get_option('sbg_sidebars');
		return $sidebars;
	}
	static function name_to_class($name){
		$class = str_replace(array(' ',',','.','"',"'",'/',"\\",'+','=',')','(','*','&','^','%','$','#','@','!','~','`','<','>','?','[',']','{','}','|',':',),'',$name);
		return $class;
	}
	
}
$sbg = new sidebar_generator;

function generated_dynamic_sidebar($name='0'){
	sidebar_generator::get_sidebar($name);	
	return true;
}
?>