<?php
/**
 * Register Product template
 *
 * @package   Brane_Framework
 * @version   2.0.0
 * @author    Branedesign
 */

$theme_name = wp_get_theme();
$theme_name_version = wp_get_theme()->get('Version');
?>

<div id="register_product" class="brane_tab_panel_body brane_row">
	<div class="brane_tab_header">
		<h1 class="brane_tab_title"><?php printf( esc_html__( '%1$s Support & F.A.Q.', 'brane_lang' ), $theme_name ); ?></h1>	
		<div class="brane_tab_description">
			<?php //esc_html_e('In order to access to Support and submit tickets, you need to Login on BraneDesign Support Site.','brane_lang'); 
			/*<ol>
			<li><a href="<?php esc_url( "http://envato.branedesign.com/support/" ) ?>" target="_blank"><strong><?php esc_html_e('Visit support page','brane_lang') ?></strong></a><?php esc_html_e(' and click Login (You must be logged into your envato account before login on our support page)','brane_lang') ?>
			</li>
			<li><?php esc_html_e('Authorize "BraneDesign Login" to connect with your account.','brane_lang') ?></li>
			<li><?php esc_html_e('You are ready to Open Tickets, visit Forum, check F.A.Q. and more!','brane_lang') ?></li>
		</ol>*/ ?>
		</div>	
	</div>
	<div class="brane_section_content col-12">
		<h3><?php esc_html_e('Support','brane_lang') ?></h3>
		<p><?php esc_html_e('For any question you can contact us through our Themeforest Panel or sending a message to: support@branedesign.com','brane_lang') ?> </p>
		<div class="brane_separator brane_row"></div>
		<div class="brane_common_register_questions">
			<h3><?php esc_html_e('F.A.Q.','brane_lang') ?></h3>
			<ul>
				<li>
					<span class="brane_question"><?php esc_html_e('How to update the theme manually?','brane_lang') ?></span>
					<div class="brane_response">
						<?php esc_html_e('You can update Bazar Theme in two different ways.
							Via WordPress:
							Go to Dashboard > Appearance > Themes. 
							Deactivate BAZAR Theme (activate any other theme). Delete BAZAR Theme. 
							Click Add New button, then click Upload Theme button. 
							Select new bazar.zip file. 
							Activate BAZAR Theme again.
							Via FTP: 
							Open you FTP client and go to wp-content > themes folder. 
							Upload unziped new BAZAR Theme folder and choose “replace”.','brane_lang') ?>
					</div>
				</li>				
				<li>
					<span class="brane_question"><?php esc_html_e('Menu Items Limit: My Menu Item has disappeared!','brane_lang') ?></span>
					<div class="brane_response">
						<?php esc_html_e('Sometimes you can experience an unusual problem, a limit to the number of items you can add to a menu.
						This is not a Theme issue. This limit is due to server configuration and can be solved increasing the php max_input_vars setting in php.ini. max_input_vars = 3000;  Alternatively, you can try placing this in your .htaccess. This won’t work on some servers, so your mileage may vary.  php_value max_input_vars 3000','brane_lang') ?>
					</div>
				</li>
				<li>
					<span class="brane_question"><?php esc_html_e('Why can not activate a menu?','brane_lang') ?></span>
					<div class="brane_response">
						<?php esc_html_e('Please, view "Menu Items Limit: My Menu Item has disappeared!"','brane_lang') ?>
					</div>
				</li>
				<li>
					<span class="brane_question"><?php esc_html_e('How to Increase the Maximum File Upload Size in WordPress','brane_lang') ?></span>
					<div class="brane_response">
						<?php esc_html_e('Depending on the web hosting company you choose and the package you select, each of you will see maximum file upload limit on your Media Uploader page in WordPress. Visit "System Info" tab and learn how to solve this issues.','brane_lang') ?>
					</div>
				</li>
				<li>
					<span class="brane_question"><?php esc_html_e("Demo Content Fails: Seems Like An Error Has Occurred",'brane_lang') ?></span>
					<div class="brane_response">
						<?php esc_html_e('If you attempted to import the sample demo data, but it never completes the installation process or it fails with errors, there could be several reasons. Please read the following information.','brane_lang') ?></br>
						<strong><?php esc_html_e('Likely Causes For Demo Import Failing','brane_lang') ?></strong>
						<ul>
							<li><?php esc_html_e('Your PHP memory, file upload size, and/or execution limits are set too low. Visit "System Info" tab and learn how to solve this issues.','brane_lang') ?></li>
							<li><?php esc_html_e('Your web host uses process watching software that prevents bulk processing on their web servers.','brane_lang') ?></li>
							<li><?php esc_html_e('You have “wp_debug = true”, please change that to “wp_debug = false” for the import in your WP config file','brane_lang') ?></li>
						</ul>
					</div>
				</li>
				<li>
					<span class="brane_question"><?php esc_html_e("Why my site doesn't look exactly like the demo?",'brane_lang') ?></span>
					<div class="brane_response">
						<?php esc_html_e('There are some things you need to adjust once you have imported the Demo content. For example, you will need to set up the static front page or make your own newsletter form.','brane_lang') ?>
					</div>
				</li>
			</ul>
		</div>
	</div>	
</div>