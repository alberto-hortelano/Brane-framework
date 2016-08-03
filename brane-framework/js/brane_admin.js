(function($) {
	"use strict";

	// Welcome Screen
	$( document ).ready(function() {

		var target = window.location.hash;

		if( target.length > 3 ){
			tab_target( target.replace('tab','') );
		}

	});	

	function tab_target( target ){

		var $tab = $( target + '_tab' ).parent();

		if( !$tab.hasClass('active') ){

			var $panel = $(target);

			$('.brane-starting-nav-tabs li').removeClass('active');
			$('.brane_tab_panel_body').removeClass('active');

			$tab.addClass('active');
			$panel.addClass('active');

		 	window.location.hash = target + 'tab';			
		}

	}

	$(document).on('click','.brane-starting-nav-tabs li a',function( e ){
		e.preventDefault();
		

		var target = $(this).attr('id').replace("_tab",""); 


		tab_target( '#' + target );		

	});

	// Welcome Screen - SysInfo	
	$(document).on('click','#brane_send_sysrep',function( e ){
		
		if( $('#brane_sys_rep_form').hasClass('active') ) {
			$('#brane_sys_rep_form').removeClass('active');	
		}else {
			$('#brane_sys_rep_form').addClass('active');
			$('#brane_textarea_report').removeClass('active');	
		}
		
	});

	$(document).on('click','#brane_show_sysrep',function( e ){
		
		if( $('#brane_textarea_report').hasClass('active') ) {
			$('#brane_textarea_report').removeClass('active');	
		}else {
			$('#brane_textarea_report').addClass('active');
			$('#brane_sys_rep_form').removeClass('active');	
		}
		
	});

	// Sidebar Generator
	$(document).on('click','.brane_sbp_header',function(){

		var icon = $(this).find('.dashicons');
		// Change Open Icon
		if( icon.hasClass('dashicons-arrow-down-alt2') ) {
			icon.removeClass('dashicons-arrow-down-alt2');
			icon.addClass('dashicons-arrow-up-alt2');
		}else{
			icon.removeClass('dashicons-arrow-up-alt2');			
			icon.addClass('dashicons-arrow-down-alt2');
		}

		// Show Panel
		$('.brane_sbp_content_wrap').slideToggle();
	});

	// Icon Picker 
	$('.format-settings .brane_iconpicker, .menu-item-settings .brane_iconpicker').each(function(){
		if(!$(this).hasClass('active')){
			$(this).addClass('active').fontIconPicker({
			    source: braneIconsArray,
			    emptyIcon: false,
			    hasSearch: true
			});
		}
	});

}( jQuery ));
