<?php
/**
 * Getting started template
 *
 * @package   Brane_Framework
 * @version   2.0.0
 * @author    Branedesign
 */

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

<div id="brane_getting_started" class="brane_tab_panel_body active brane_row">
	<div class="brane_tab_header">
		<h1 class="brane_tab_title"><?php printf( esc_html__( 'Welcome to %1$s!', 'brane_lang' ), $theme_name ); ?>
		
		</h1>
		<h2 class="brane_welcome_subtitle"><?php esc_html_e('A BraneDesign Theme','brane_lang') ?></h2>		
		<div class="brane_tab_description">
			<?php esc_html_e('We would like to thank you for purchasing our theme!','brane_lang'); ?>
			<?php esc_html_e('This panel is a small theme guide. Here you can find links to necessary actions to start customizing your site and theme documentation. It has a usefull tab with info about WordPress & Server Environment. Also, "Support" tab shows you how to acces to our customers support system and F.A.Q. On Changelog tab you can check the changes we will make on theme updates. At the bottom of this page you can find a selection of recommended plugins to install demo content and improve your site.','brane_lang'); ?>
		</div>
	</div>
	<div class="brane_section_content col-12">
		<h2 class="brane_section_title"><?php esc_html_e('Getting Started','brane_lang') ?></h2>
		<h3 class="brane_section_subtitle"><?php esc_html_e('Begin to create a unique site!','brane_lang') ?></h3>
		<div class="brane_row">
			<div class="col-6">
				<h4><?php esc_html_e( 'Installing Required Plugins', 'brane_lang' ) ?></h4>
				<p class="brane-section-desc"><?php esc_html_e( 'Before starting customizing theme and creating your awesome site, we need to install the required plugins.', 'brane_lang') ?></p>
				<p><a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'themes.php?page=tgmpa-install-plugins&plugin_status=install' ) ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Install Plugins', 'brane_lang' ) ?></a></p>

			</div>
			<div class="col-6">
				<h4><?php esc_html_e( 'Customizing Theme', 'brane_lang' ) ?></h4>
				<p class="brane-section-desc"><?php esc_html_e( "Once you have installed and activated required plugin 'Option Tree', we can starting to customize the Theme.", 'brane_lang') ?></p>
				<p>
				<?php if ( class_exists('OT_Loader') ) {
					?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'admin.php?page=brane-theme-options' ) ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( "Go to Theme Options", 'brane_lang') ?></a><?php
				}else{
					?> <a href="javascript:void(0)" class="brane_button disabled"><?php esc_html_e( "Go to Theme Options", 'brane_lang') ?></a><?php
				} ?>
				</p>
			</div>
		</div>
	</div>
	<div class="brane_section_content col-12">
		<h2 class="brane_section_title"><?php esc_html_e('Theme Full Documentation','brane_lang') ?><span class="dashicons dashicons-editor-help"></span></h2>
		<h3 class="brane_section_subtitle"><?php esc_html_e('Check the online documentation and detailed information on how to use this theme.','brane_lang') ?></h3>
		<p><a href="<?php echo esc_url( $urls['doc'] ) ?>" class="brane_button" target="_blank"><?php esc_html_e( "Go to Documentation", 'brane_lang') ?></a></p>
	</div>
	<div class="brane_section_content col-12">
		<h2 class="brane_section_title"><?php esc_html_e('Install Demo Content','brane_lang') ?><span class="dashicons dashicons-warning"></span></h2>
		<span class="brane_section_subtitle"><?php esc_html_e('Please, in order to install Demo Content you need to install first "One Click Demo Import" Plugin.','brane_lang') ?></span>
		<p>
			<?php if( is_plugin_active('one-click-demo-import/one-click-demo-import.php')  ){
				
				?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'themes.php?page=pt-one-click-demo-import' ) ) ) ?>" class="brane_button" target="_blank"><?php esc_html_e( "Go to Install Demo Content", 'brane_lang') ?></a> <?php

			}else if( is_plugin_inactive('one-click-demo-import/one-click-demo-import.php') &&  Brane_Theme_Panel::if_plugin_installed( '', 'one-click-demo-import/one-click-demo-import.php') == true ){

				?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'plugins.php' ) ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Activate One Click Demo Import', 'brane_lang' ); ?></a> <?php

			}else{
				?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=one-click-demo-import' ), 'install-plugin_one-click-demo-import' ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Install One Click Demo Import', 'brane_lang' ); ?></a> <?php
			} ?>				
			
			<a href="<?php echo esc_url( 'http://branedesign.com/wordpressthemes/documentation/bazardoc/#demo_content' ) ?>" class="brane_button" target="_blank"><?php esc_html_e( "More Info about Demo Content", 'brane_lang') ?></a>
		</p>
	</div>
	<div class="brane_section_content col-12">
		<h2 class="brane_section_title"><?php esc_html_e("How To's",'brane_lang') ?></h2>
		<div class="brane_row">
			<div class="col-6">
				<h4><?php esc_html_e( 'Child Theme', 'brane_lang' ) ?></h4>
				<p class="brane-section-desc"><?php esc_html_e( 'Our Themes include a Child Theme. You can find it in package downloaded from Themeforest. Istall it as a normal theme. A child theme is a WordPress theme that inherits its functionality from another WordPress theme, the parent theme. Child themes are often used when you want to customize or tweak an existing WordPress theme without losing the ability to upgrade that theme.', 'brane_lang') ?></p>
				<p><a href="<?php echo esc_url( 'http://branedesign.com/wordpressthemes/documentation/bazardoc/#child_theme' ) ?>" class="brane_button"  target="_blank"><?php esc_html_e( "View More", 'brane_lang') ?></a></p>

			</div>
			<div class="col-6">
				<h4><?php esc_html_e( 'Creating Sidebars', 'brane_lang' ) ?></h4>
				<p class="brane-section-desc"><?php esc_html_e( 'You can add as many Sidebars for your widgets as you need, is very easy with the Sidebars Generator. To add a new Sidebar click Add Sidebar. Type a name for your new sidebar and click Accept. To remove a Sidebar, press remove beside the sidebar you want to delete.', 'brane_lang') ?></p>
				<p><a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'widgets.php' ) ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( "Go to Sidebars Generator", 'brane_lang') ?></a>
				</p>
			</div>
		</div>
		<div class="brane_row">			
			<div class="col-12">
				<h4><?php esc_html_e( 'How to Internationalize Your Website', 'brane_lang' ) ?></h4>
				<p><?php esc_html_e( "This Theme is Translation Ready and compatible with WPML plugin. Visit plugin page to learn how to make your site Multilingual.", 'brane_lang') ?></p>
				<p><a href="<?php echo esc_url( $urls['wpml'] ) ?>" class="brane_button" target="_blank"><?php esc_html_e( "Go to WPML Site", 'brane_lang') ?></a>
				</p>
			</div>
		</div>
	</div>
	<div class="brane_section_content col-12">
		<h2 class="brane_section_title"><?php esc_html_e("Recommended Plugins",'brane_lang') ?></h2>
		<div class="brane_row">
			<div class="col-6">
				<h4><?php esc_html_e( 'One Click Demo Import', 'brane_lang' ) ?></h4>
				<p class="brane-section-desc"><?php esc_html_e( 'This Plugin is necessary to Import Theme demo content. Please, install One Click Demo Import Plugin.', 'brane_lang') ?></p>
				<?php if( is_plugin_active('one-click-demo-import/one-click-demo-import.php')  ){
					
					?> <a href="javascript:void(0)" class="brane_button disabled"><?php esc_html_e( "Allready Activated", 'brane_lang') ?></a> <?php

				}else if( is_plugin_inactive('one-click-demo-import/one-click-demo-import.php') &&  Brane_Theme_Panel::if_plugin_installed( '', 'one-click-demo-import/one-click-demo-import.php') == true ){

					?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'plugins.php' ) ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Activate One Click Demo Import', 'brane_lang' ); ?></a> <?php

				}else{
					?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=one-click-demo-import' ), 'install-plugin_one-click-demo-import' ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Install One Click Demo Import', 'brane_lang' ); ?></a> <?php
				} ?>				
			</div>
			<div class="col-6">
				<h4><?php esc_html_e( 'W3 Total Cache', 'brane_lang' ) ?></h4>
				<p class="brane-section-desc"><?php esc_html_e( 'The highest rated and most complete WordPress performance plugin. Dramatically improve the speed and user experience of your site. Add browser, page, object and database caching as well as minify and content delivery network (CDN) to WordPress', 'brane_lang') ?></p>
				<?php if( is_plugin_active('w3-total-cache/w3-total-cache.php')  ){
					
					?> <a href="javascript:void(0)" class="brane_button disabled"><?php esc_html_e( "Allready Activated", 'brane_lang') ?></a> <?php

				}else if( is_plugin_inactive('w3-total-cache/w3-total-cache.php') &&  Brane_Theme_Panel::if_plugin_installed( '', 'w3-total-cache/w3-total-cache.php') == true ){

					?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'plugins.php' ) ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Activate W3 Total Cache', 'brane_lang' ); ?></a> <?php

				}else{
					?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=w3-total-cache' ), 'install-plugin_w3-total-cache' ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Install W3 Total Cache', 'brane_lang' ); ?></a> <?php
				} ?>				
			</div>
			<div class="col-6">
				<h4><?php esc_html_e( 'Yoast SEO', 'brane_lang' ) ?></h4>
				<p class="brane-section-desc"><?php esc_html_e( 'Improve your WordPress SEO: Write better content and have a fully optimized WordPress site using Yoast SEO plugin.', 'brane_lang') ?></p>
				<?php if( is_plugin_active('wordpress-seo/wp-seo.php')  ){
					
					?> <a href="javascript:void(0)" class="brane_button disabled"><?php esc_html_e( "Allready Activated", 'brane_lang') ?></a> <?php

				}else if( is_plugin_inactive('wordpress-seo/wp-seo.php') &&  Brane_Theme_Panel::if_plugin_installed( '', 'wordpress-seo/wp-seo.php') == true ){

					?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'plugins.php' ) ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Activate Yoast SEO', 'brane_lang' ); ?></a> <?php

				}else{
					?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=wordpress-seo' ), 'install-plugin_wordpress-seo' ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Install Yoast SEO', 'brane_lang' ); ?></a> <?php
				} ?>				
			</div>
			<div class="col-6">
				<h4><?php esc_html_e( 'AddToAny Share Buttons', 'brane_lang' ) ?></h4>
				<p class="brane-section-desc"><?php esc_html_e( "Share buttons for your pages including AddToAny's universal sharing button, Facebook, Twitter, Google+, Pinterest, WhatsApp and many more", 'brane_lang') ?></p>
				<?php if( is_plugin_active('add-to-any/add-to-any.php')  ){
					
					?> <a href="javascript:void(0)" class="brane_button disabled"><?php esc_html_e( "Allready Activated", 'brane_lang') ?></a> <?php

				}else if( is_plugin_inactive('add-to-any/add-to-any.php') &&  Brane_Theme_Panel::if_plugin_installed( '', 'add-to-any/add-to-any.php') == true ){

					?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'plugins.php' ) ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Activate AddToAny Share Buttons', 'brane_lang' ); ?></a> <?php

				}else{
					?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=add-to-any' ), 'install-plugin_add-to-any' ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Install AddToAny Share Buttons', 'brane_lang' ); ?></a> <?php
				} ?>				
			</div>
			<div class="col-6">
				<h4><?php esc_html_e( 'Mailchimp for Wordpress', 'brane_lang' ) ?></h4>
				<p class="brane-section-desc"><?php esc_html_e( 'Plugin to connect your site with your Mailchimp account and create forms to get subscribers for your newsletters.', 'brane_lang') ?></p>
				<?php if( is_plugin_active('mailchimp-for-wp/mailchimp-for-wp.php')  ){
					
					?> <a href="javascript:void(0)" class="brane_button disabled"><?php esc_html_e( "Allready Activated", 'brane_lang') ?></a> <?php

				}else if( is_plugin_inactive('mailchimp-for-wp/mailchimp-for-wp.php') &&  Brane_Theme_Panel::if_plugin_installed( '', 'mailchimp-for-wp/mailchimp-for-wp.php') == true ){

					?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'plugins.php' ) ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Activate Mailchimp for Wordpress', 'brane_lang' ); ?></a> <?php

				}else{
					?> <a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=mailchimp-for-wp' ), 'install-plugin_mailchimp-for-wp' ) ); ?>" class="brane_button" target="_blank"><?php esc_html_e( 'Install Mailchimp for Wordpress', 'brane_lang' ); ?></a> <?php
				} ?>				
			</div>
			<?php 
			/*<div class="col-6">
				<h4><?php esc_html_e( 'Creating Sidebars', 'brane_lang' ) ?></h4>
				<p><?php esc_html_e( 'You can add as many Sidebars for your widgets as you need, is very easy with the Sidebars Generator. To add a new Sidebar click Add Sidebar. Type a name for your new sidebar and click Accept. To remove a Sidebar, press remove beside the sidebar you want to delete. To add widgets on a Sidebar go to Appearance > Widgets, drag and drop desired widget to the sidebar.', 'brane_lang') ?></p>
				<p><a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'themes.php?page=brane_custom_sidebars' ) ) ); ?>" class="brane_button"><?php esc_html_e( "Go to Sidebars Generator", 'brane_lang') ?></a>
				</p>
			</div> */?>
		</div>
	</div>
</div>