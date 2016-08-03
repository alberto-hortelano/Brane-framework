<?php
/**
 * System Info template
 *
 * @package   Brane_Framework
 * @version   2.0.0
 * @author    Branedesign
 */

// Gets the System Report
$report = Brane_System_Info::sysinfo_render_report();                   
?>

<div id="sys_info" class="brane_tab_panel_body brane_row">

	<div class="brane_tab_header">
		<h1 class="brane_tab_title"><?php esc_html_e( 'System Info', 'brane_lang' ) ?></h1>
		<div class="brane_tab_description">
			<?php esc_html_e('Here you can find detailed info about WordPress & Server Environment','brane_lang'); ?>			
		</div>	
        <div id="brane_system_report">	
        	<form action="<?php echo esc_url( self_admin_url( 'admin-ajax.php' ) ); ?>" method="post" enctype="multipart/form-data" >
                <a href="javascript:void(0)" id="brane_show_sysrep" class="brane_button"><?php esc_html_e( 'Show as Plain Text', 'brane_lang' ) ?></a> 
                <input type="submit" id="brane_down_sysrep" class="brane_button" value="<?php esc_attr_e( 'Download as Text File', 'brane_lang' ) ?>" /> 
                <input type="hidden" name="action" value="download_system_info" />  
                <a href="javascript:void(0)" id="brane_send_sysrep" class="brane_button"><?php esc_html_e( 'Send by Mail', 'brane_lang' ) ?></a>
                <textarea readonly="readonly" onclick="this.focus();this.select()" id="brane_textarea_report" name="brane_sys_info_report_down" title="<?php esc_attr_e( 'Click and press Ctrl + C (PC) or Cmd + C (Mac) to Copy System Report.', 'brane_lang' ); ?>">
                   <?php Brane_System_Info::convert_html($report);?>
                </textarea>                               
            </form>            
            <?php Brane_System_Info::sysinfo_send_email(); ?>
        </div>		
    </div><!-- brane_tab_header -->

    <div id="brane_sys_info_report" name="brane_sys_info_report_down">       
        <?php echo wp_kses_post( $report ); ?>
    </div><!-- brane_sys_info_report -->
    
</div>