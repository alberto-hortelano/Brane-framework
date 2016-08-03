<?php
/**
 * Creates HTML markup for Sidebar Generator on Widgets Page
 */
?>

<script>
var brane_templateDir = "<?php echo esc_url( site_url() ) ?>";
</script>

<div class="brane_row" id="brane_custom_sidebars_panel">
	<div class="brane_sbp_header col-12">
		<h3><?php printf( esc_html__( '%s Custom Sidebars', 'brane_lang' ), wp_get_theme() )?></h3>
		<a href="javascript:void(0)" class="brane_open_panel"><i class="dashicons dashicons-arrow-down-alt2"></i></a>
	</div>	
	<div class="brane_sbp_content_wrap col-12">

		<div class="brane_sbp_content brane_row">
			
			<?php
			$sidebars = sidebar_generator::get_sidebars(); 

			if(is_array($sidebars) && !empty($sidebars)){

				foreach($sidebars as $sidebar){
				?>
				
				<div class="brane_single_sidebar col-6">
					<div class="brane_single_sidebar_content">
						<span class="brane_sidebar_name"><?php echo esc_html( $sidebar ); ?></span>
						<span class="brane_sidebar_trash"><a href="javascript:void(0);" title="Remove this sidebar" class="brane_remove"><i class="dashicons dashicons-trash"></i></a></span>
					</div>
				</div>

				<?php } 
			} ?>			
		</div>

		<div class="brane_sbp_footer brane_row">
			<div class="brane_spb_f_content">
				<form class="brane_sbp_form">
					<input type="text" class="sidebar_name" name="sidebar_name">
					<button type="button" class="brane_sbp_btn"><?php esc_html_e('Add Sidebar','brane_lang') ?></button>
					<button type="button" class="brane_sbp_remove_btn"><?php esc_html_e('Remove Selected Sidebars','brane_lang') ?></button>
				</form>
			</div>
		</div>
	</div>
</div>