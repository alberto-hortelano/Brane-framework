<?php
/**
 * System Report template
 *
 * @package   Brane_Framework
 * @version   2.0.0
 * @author    Branedesign
 */

$systeminfo = Brane_System_Info::systemInfo( true );

?>

    <!-- Begins WordPress Environment Table -->
    <table id="brane_sys_wp_table" class="sys_table">
    	<!-- Table Head -->
        <thead>
	        <tr>    
	            <th colspan="3" data-export-label="WordPress Environment">
	                <?php esc_html_e( 'WordPress Environment', 'brane_lang' ); ?>
	            </th>
	        </tr>
        </thead>
        <!-- Table Body -->
        <tbody>
	        <tr>           
	            <td data-export-label="Home URL">
	            	<span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info dashicons dashicons-info"></a>
		                <span>
		                    <?php esc_html_e( 'The URL of your site\'s homepage.', 'brane_lang' ); ?>
		                </span>
	            	</span>
	                <?php esc_html_e( 'Home URL', 'brane_lang' ); ?>:
	            </td>
	            <td><?php echo esc_url($systeminfo['home_url']); ?></td>
	        </tr>
	        <tr>
	            <td data-export-label="Site URL">
		        	<span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php esc_html_e( 'The root URL of your site.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'Site URL', 'brane_lang' ); ?>:
	            </td>
	            
	            <td>
	                <?php echo esc_url($systeminfo['site_url']); ?>
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="WP Content URL">
		        	<span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The location of Wordpress\'s content URL.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'WP Content URL', 'brane_lang' ); ?>:
	            </td>	           
	            <td>
	                <?php echo '<code>' . esc_url($systeminfo['wp_content_url']) . '</code> '; ?>
	            </td>
	        </tr>        
	        <tr>	        	
	            <td data-export-label="WP Version">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The version of WordPress installed on your site.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'WP Version', 'brane_lang' ); ?>:
	            </td>	            
	            <td>
	                <?php bloginfo( 'version' ); ?>
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="WP Multisite">
		        	<span class="brane_info">
		               <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'WP Multisite', 'brane_lang' ); ?>:
	            </td>	            
	            <td><?php if ( $systeminfo['wp_multisite'] == true ) {
	                    echo '<span>' . esc_html__( 'Yes', 'brane_lang') .'</span>';
	                } else {
	                    echo '<span>' . esc_html__( 'No', 'brane_lang') .'</span>';
	                } ?>
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="Permalink Structure">
		        	<span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The current permalink structure as defined in Wordpress Settings->Permalinks.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'Permalink Structure', 'brane_lang' ); ?>:
	            </td>	            
	            <td>
	                <?php echo esc_html($systeminfo['permalink_structure']); ?>
	            </td>
	        </tr>
	        <?php $sof = $systeminfo['front_page_display']; ?>
	        <tr>
	            <td data-export-label="Front Page Display">
		        	<span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The current Reading mode of Wordpress.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'Front Page Display', 'brane_lang' ); ?>:
	            </td>	           
	            <td><?php echo esc_html($sof); ?></td>
	        </tr>

        	<?php
        if ( $sof == 'page' ) {
			?>
            <tr>
                <td data-export-label="Front Page">
                    <span class="brane_info">
                        <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
                       <span>
                            <?php echo esc_attr__( 'The currently selected page which acts as the site\'s Front Page.', 'brane_lang' ); ?>
                        </span>
                    </span>
                    <?php esc_html_e( 'Front Page', 'brane_lang' ); ?>:
                </td>
                <td>
                    <?php echo esc_html($systeminfo['front_page']); ?>
                </td>
            </tr>
            <tr>
                <td data-export-label="Posts Page">
                    <span class="brane_info">
                        <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
                       <span>
                            <?php echo esc_attr__( 'The currently selected page in where blog posts are displayed.', 'brane_lang' ); ?>
                        </span>
                    </span>
                    <?php esc_html_e( 'Posts Page', 'brane_lang' ); ?>:
                </td>
                <td>
                    <?php echo esc_html($systeminfo['posts_page']); ?>
                </td>
            </tr>
		<?php
        }
		?>
	        <tr>
	            <td data-export-label="WP Memory Limit">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'WP Memory Limit', 'brane_lang' ); ?>:
	            </td>	            
	            <td>
				<?php
	                    $memory = $systeminfo['wp_mem_limit']['raw'];

	                    if ( $memory < 67108864 ) {	                       
	                        echo '<span class="dashicons-before dashicons-dismiss">' . esc_html( $systeminfo['wp_mem_limit']['size'] ) .
	                        	esc_html__( '- We recommend setting memory to at least 64MB. See:', 'brane_lang') .
	                        	'<a href="' . esc_url('http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP') . '" target="_blank">' . esc_html__('Increasing memory allocated to PHP','brane_lang') . '</a>
	                        	</span>';
	                    } else {
	                        echo '<span class="dashicons-before dashicons-yes">' . esc_html($systeminfo['wp_mem_limit']['size']) . '</span>';
	                    }
				?>
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="Database Table Prefix">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The prefix structure of the current Wordpress database.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'Database Table Prefix', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php echo wp_kses_post( $systeminfo['db_table_prefix'] ); ?>
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="WP Debug Mode">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'WP Debug Mode', 'brane_lang' ); ?>:
	            </td>	            
	            <td>
	                <?php if ( $systeminfo['wp_debug'] === 'true' ) {
	                    echo '<span>' . esc_html__( 'Yes', 'brane_lang') .'</span>';
	                } else {
	                    echo '<span>' . esc_html__( 'No', 'brane_lang') .'</span>';
	                } ?>
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="Language">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The current language used by WordPress. Default = English', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'Language', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php echo esc_html($systeminfo['wp_lang']); ?>
	            </td>
	        </tr>
        </tbody>
    </table>
    <!-- Ends WordPress Environment Table -->
    <!-- Begins Theme Table -->
    <table id="brane_sys_theme_table" class="sys_table">
        <thead>
        <tr>
            <th colspan="3" data-export-label="Theme">
            	<?php esc_html_e( 'Theme', 'brane_lang' ); ?>
            </th>
        </tr>
        </thead>
        <tbody>
	        <tr>
	            <td data-export-label="Name">
					<span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The name of the current active theme.', 'brane_lang' ); ?>
		                </span>
		            </span>
	            	<?php esc_html_e( 'Name', 'brane_lang' ); ?>:
	            </td>           
	            <td><?php echo esc_html($systeminfo['theme']['name']); ?></td>
	        </tr>
	        <tr>
	            <td data-export-label="Version">
					<span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The installed version of the current active theme.', 'brane_lang' ); ?>
		                </span>
		            </span>
	            	<?php esc_html_e( 'Version', 'brane_lang' ); ?>:
	            </td>            
	            <td>
					<?php
		                echo esc_html( $systeminfo['theme']['version'] );
		                if ( ! empty( $theme_version_data['version'] ) && version_compare( $theme_version_data['version'], $active_theme->Version, '!=' ) ) {
		                    echo ' &ndash; <strong style="color:red;">' . esc_html($theme_version_data['version']) . ' ' . esc_html__( 'is available', 'brane_lang' ) . '</strong>';
		                }
					?>
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="Author URL">
	            	<span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		                <span>
		                    <?php echo esc_attr__( 'The theme developers URL.', 'brane_lang' ); ?>
		                </span>
		            </span>
	            	<?php esc_html_e( 'Author URL', 'brane_lang' ); ?>:
	            </td>            
	            <td><?php echo esc_url($systeminfo['theme']['author_uri']); ?></td>
	        </tr>
	        <tr>
	            <td data-export-label="Child Theme">
					<span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		                <span>
		                    <?php echo esc_attr__( 'Displays whether or not the current theme is a child theme.', 'brane_lang' ); ?>
		                </span>
		            </span>
	            	<?php esc_html_e( 'Child Theme', 'brane_lang' ); ?>:
	            </td>            
	            <td>
					<?php
	                echo is_child_theme() ? '<span>' . esc_html__( 'Yes', 'brane_lang') .'</span>' : '<span>' . esc_html__( 'No', 'brane_lang') .' </span>' .
	                sprintf( '<a href="%s" target="_blank">%s</a>', 'http://codex.wordpress.org/Child_Themes',	esc_html__( 'How to create a child theme', 'brane_lang' ) );
	                ?>

	            </td>
	        </tr>
			<?php
            if ( is_child_theme() ) {
			?>
            <tr>
                <td data-export-label="Parent Theme Name">
	                <span class="brane_info">
	                    <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
	                   <span>
	                        <?php echo esc_attr__( 'The name of the parent theme.', 'brane_lang' ); ?>
	                    </span>
	                </span>
                	<?php esc_html_e( 'Parent Theme Name', 'brane_lang' ); ?>:
                </td>
                <td><?php echo esc_html($systeminfo['theme']['parent_name']); ?></td>
            </tr>
            <tr>
                <td data-export-label="Parent Theme Version">
	                <span class="brane_info">
	                    <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
	                   <span>
	                        <?php echo esc_attr__( 'The installed version of the parent theme.', 'brane_lang' ); ?>
	                    </span>
	                </span>
                    <?php esc_html_e( 'Parent Theme Version', 'brane_lang' ); ?>:
                </td>
                <td><?php echo esc_html($systeminfo['theme']['parent_version']); ?></td>
            </tr>
            <tr>
                <td data-export-label="Parent Theme Author URL">
	                <span class="brane_info">
	                    <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
	                   <span>
	                        <?php echo esc_attr__( 'The parent theme developers URL.', 'brane_lang' ); ?>
	                    </span>
	                </span>
                    <?php esc_html_e( 'Parent Theme Author URL', 'brane_lang' ); ?>:
                </td>
                <td><?php echo esc_url($systeminfo['theme']['parent_author_uri']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <!-- Ends Theme Table -->
    <!-- Begins Browser Table -->
    <table id="brane_sys_browser_table" class="sys_table">
        <thead>
	        <tr>
	            <th colspan="3" data-export-label="Browser">
	                <?php esc_html_e( 'Browser', 'brane_lang' ); ?>
	            </th>
	        </tr>
        </thead>
        <tbody>
	        <tr>
	            <td data-export-label="Browser Info">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		                <span>
		                    <?php echo esc_attr__( 'Information about web browser current in use.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'Browser', 'brane_lang' ); ?>:
	            </td>
	            <td>
	            	<?php echo esc_html($systeminfo['browser']['browser']); ?>
	            </td>	            
	        </tr>
	        <tr>
	        	<td data-export-label="Browser Agent">
	                <?php esc_html_e( 'User Agent String', 'brane_lang' ); ?>:
	            </td>	        	
	        	<td>
	        		<?php echo esc_html($systeminfo['browser']['agent']); ?>
	        	</td>
	        </tr>
	        <tr>
	        	<td data-export-label="Browser Version">
	                <?php esc_html_e( 'Version', 'brane_lang' ); ?>:
	            </td>	        	
	        	<td>
	        		<?php echo esc_html($systeminfo['browser']['version']); ?>
	        	</td>
	        </tr>
	        <tr>
	        	<td data-export-label="Platform">
	                <?php esc_html_e( 'Platform', 'brane_lang' ); ?>:
	            </td>	        	
	        	<td>
	        		<?php echo esc_html($systeminfo['browser']['platform']); ?>
	        	</td>
	        </tr>
        </tbody>
    </table>
	<!-- Ends Browser Table -->
    <!-- Begins Server Table -->
    <table id="brane_sys_server_table" class="sys_table">
        <thead>
	        <tr>
	            <th colspan="3" data-export-label="Server Environment">
	                <?php esc_html_e( 'Server Environment', 'brane_lang' ); ?>
	            </th>
	        </tr>
        </thead>
        <tbody>
	        <tr>
	            <td data-export-label="Server Info">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'Information about the web server that is currently hosting your site.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'Server Info', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php echo esc_html($systeminfo['server_info']); ?>
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="Localhost Environment">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'Is the server running in a localhost environment.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'Localhost Environment', 'brane_lang' ); ?>:
	            </td>
	            <td>
				<?php
	                if ( $systeminfo['localhost'] === 'true' ) {
	                    echo '<span>' . esc_html__( 'Yes', 'brane_lang') .'</span>';
	                } else {
	                    echo '<span>' . esc_html__( 'No', 'brane_lang') .'</span>';
	                }
				?>            
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="PHP Version">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The version of PHP installed on your hosting server.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'PHP Version', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php echo esc_html($systeminfo['php_ver']); ?>
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="ABSPATH">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The ABSPATH variable on the server.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'ABSPATH', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php echo '<code>' . esc_html($systeminfo['abspath']) . '</code>'; ?>
	            </td>
	        </tr>
	        
	        <?php if ( function_exists( 'ini_get' ) ) { ?>
	            <tr>
	                <td data-export-label="PHP Memory Limit">
		                <span class="brane_info">
		                    <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		                   <span>
		                        <?php echo esc_attr__( 'The largest filesize that can be contained in one post.', 'brane_lang' ); ?>
		                    </span>
		                </span>
	                	<?php esc_html_e( 'PHP Memory Limit', 'brane_lang' ); ?>:
	                </td>
	                
	                <td><?php echo esc_html($systeminfo['php_mem_limit']); ?></td>
	            </tr>
	            <tr>
	                <td data-export-label="PHP Post Max Size">
		                <span class="brane_info">
		                    <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		                   <span>
		                        <?php echo esc_attr__( 'The largest filesize that can be contained in one post.', 'brane_lang' ); ?>
		                    </span>
		                </span>
	                	<?php esc_html_e( 'PHP Post Max Size', 'brane_lang' ); ?>:
	                </td>
	                <td>
					<?php
	                    $post_max_size = $systeminfo['php_post_max_size'];

	                    if ( $post_max_size < 32 ) {
	                         echo '<span class="dashicons-before dashicons-dismiss">' . esc_html( $systeminfo['php_post_max_size'] ) . 
	                          		esc_html__( ' - Reccomended value is at least 32. See: ', 'brane_lang') .
	                          		'<a href="' . esc_url( 'https://www.a2hosting.com/kb/developer-corner/php/using-php-directives-in-custom-htaccess-files/setting-the-php-maximum-upload-file-size-in-an-htaccess-file') .'" target="_blank">' . esc_html__('Please, increase it.','brane_lang') . '</a>' .
	                          		'</span>';
	                    } else {
	                        echo '<span class="dashicons-before dashicons-yes">' . esc_html( $systeminfo['php_post_max_size'] ) . '</span>';
	                    }
					?>
	                </td>
	            </tr>
	            <tr>
	                <td data-export-label="PHP Time Limit">
		                <span class="brane_info">
		                    <?php echo '<a href="#" class="brane-tooltip dashicons dashicons-info"></a>'; ?>
		                   <span>
		                        <?php echo esc_attr__( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)', 'brane_lang' ); ?>
		                    </span>
		                </span>
	                	<?php esc_html_e( 'PHP Time Limit', 'brane_lang' ); ?>:
	                </td>
	                <td>
					<?php
	                    $php_time_limit = $systeminfo['php_time_limit'];

	                    if ( $php_time_limit < 120 ) {	                       
	                        echo '<span class="dashicons-before dashicons-dismiss">' . esc_html( $php_time_limit ) .
	                        	esc_html__( ' - Reccomended value is at least 120. See:', 'brane_lang') .
	                        	'<a href="' . esc_url('http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded') . '" target="_blank">' . esc_html__('Please, increase it','brane_lang') . '</a>
	                        	</span>';
	                    } else {
	                        echo '<span class="dashicons-before dashicons-yes">' . esc_html( $php_time_limit ) . '</span>';
	                    }
					?>
	                </td>
	            </tr>
	            <tr>
	                <td data-export-label="PHP Max Input Vars">
		                <span class="brane_info">
		                    <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		                   <span>
		                        <?php echo esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'brane_lang' ); ?>
		                    </span>
		                </span>
	                	<?php esc_html_e( 'PHP Max Input Vars', 'brane_lang' ); ?>:
	                </td>
	                <td>
					<?php
	                    $php_max_input_var = $systeminfo['php_max_input_var'];

	                    if ( $php_max_input_var < 3000 ) {	                       
	                        echo '<span class="dashicons-before dashicons-dismiss">' . esc_html( $php_max_input_var ) .
	                        	esc_html__( ' - Reccomended value is at least 3000. See:', 'brane_lang') .
	                        	'<a href="' . esc_url('http://docs.woothemes.com/document/problems-with-large-amounts-of-data-not-saving-variations-rates-etc/#section-2">') . '" target="_blank">' . esc_html__('Please, increase it','brane_lang') . '</a>
	                        	</span>';
	                    } else {
	                        echo '<span class="dashicons-before dashicons-yes">' . esc_html( $php_max_input_var ) . '</span>';
	                    }
					?>
	                </td>
	            </tr>
	            <tr>
	                <td data-export-label="PHP Display Errors">
		                <span class="brane_info">
		                    <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		                   <span>
		                        <?php echo esc_attr__( 'Determines if PHP will display errors within the browser.', 'brane_lang' ); ?>
		                    </span>
		                </span>
	                	<?php esc_html_e( 'PHP Display Errors', 'brane_lang' ); ?>:
	                </td>
	                <td><?php
	                    if ( $systeminfo['php_display_errors'] === 'true' ) {
	                        echo '<span>' . esc_html__( 'Yes', 'brane_lang') .'</span>';
		                } else {
		                    echo '<span>' . esc_html__( 'No', 'brane_lang') .'</span>';
		                }
	                    ?>
	                </td>
	            </tr>
	        <?php } ?>
	        <tr>
	            <td data-export-label="SUHOSIN Installed">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'Suhosin is an advanced protection system for PHP installations. It was designed to protect your servers on the one hand against a number of well known problems in PHP applications and on the other hand against potential unknown vulnerabilities within these applications or the PHP core itself.  If enabled on your server, Suhosin may need to be configured to increase its data submission limits.', 'brane_lang' ); ?>
		                </span>
		            </span>
	            	<?php esc_html_e( 'SUHOSIN Installed', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php if ( $systeminfo['suhosin_installed'] == true ) {
	                   echo '<span>' . esc_html__( 'Yes', 'brane_lang') .'</span>';
	                } else {
	                    echo '<span>' . esc_html__( 'No', 'brane_lang') .'</span>';
	                }?>
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="MySQL Version">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               <span>
		                    <?php echo esc_attr__( 'The version of MySQL installed on your hosting server.', 'brane_lang' ); ?>
		                </span>
		            </span>
	            	<?php esc_html_e( 'MySQL Version', 'brane_lang' ); ?>:
	            </td>
	            <td><?php echo esc_html($systeminfo['mysql_ver']); ?></td>
	        </tr>
	        <tr>
	            <td data-export-label="Max Upload Size">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               	<span>
		                    <?php esc_html_e( 'The largest filesize that can be uploaded to your WordPress installation.', 'brane_lang' ); ?>
		                </span>
		            </span>
	            	<?php esc_html_e( 'Max Upload Size', 'brane_lang' ); ?>:
	            </td>
	            <td>
				<?php
                    $max_upload_size = $systeminfo['max_upload_size'];

                    if ( $max_upload_size < 32 ) {	                       
                        echo '<span class="dashicons-before dashicons-dismiss">' . esc_html( $max_upload_size ) .
                        	esc_html__( ' - Reccomended value is at least 32. See:', 'brane_lang') .
                        	'<a href="' . esc_url('https://www.a2hosting.com/kb/developer-corner/php/using-php-directives-in-custom-htaccess-files/setting-the-php-maximum-upload-file-size-in-an-htaccess-file">' ) . '" target="_blank">' . esc_html__('Please, increase it','brane_lang') . '</a>
                        	</span>';
                    } else {
                        echo '<span class="dashicons-before dashicons-yes">' . esc_html( $max_upload_size ) . '</span>';
                    }
				?>
	            </td>
	        </tr>
	        <tr>
	            <td data-export-label="Default Timezone is UTC">
		            <span class="brane_info">
		                <a href="#" class="brane-tooltip dashicons dashicons-info"></a>
		               	<span>
		                    <?php esc_html_e( 'The default timezone for your server.', 'brane_lang' ); ?>
		                </span>
		            </span>
	                <?php esc_html_e( 'Default Timezone is UTC', 'brane_lang' ); ?>:
	            </td>
	            <td>
				<?php
	                if ( $systeminfo['def_tz_is_utc'] === 'false' ) {
	                    echo '<span class="dashicons-before dashicons-dismiss">False</span><span class="status-state status-false"></span> ' . sprintf( esc_html__( 'Default timezone is %s - it should be UTC', 'brane_lang' ), esc_html(date_default_timezone_get()) );
	                } else {
	                    echo '<span class="dashicons-before dashicons-yes">' . esc_html__( 'Yes', 'brane_lang') .'</span>';
	                }                 
				?>
	            </td>
	        </tr>
	        <tr>            
	        <?php  //fsockopen/cURL ?>			
	        <tr>
	            <td data-export-label="FSOCKOPEN">
	                <span class="brane_info">
	                	<a href="#" class="brane-tooltip dashicons dashicons-info"></a>
	           			<span>
	                    <?php esc_html_e( 'Used when communicating with remote services with PHP.', 'brane_lang' )  ?>
	                	</span>
	                </span>
	                <?php esc_html_e( 'FSOCKOPEN', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php 
	                if ( $systeminfo['fsockopen'] === 'true' ) {
	                    echo '<span class="dashicons-before dashicons-yes">'. esc_html__( 'Yes', 'brane_lang') .'</span>';
	                } else {
	                    echo '<span class="dashicons-before dashicons-dismiss">'. esc_html__( 'Your server does not support fsockopen.', 'brane_lang') .'</span>';
	                } ?>
	            </td>
	        </tr>

			<?php //cURL ?>
			<tr>
	            <td data-export-label="cURL">
	                <span class="brane_info">
	                	<a href="#" class="brane-tooltip dashicons dashicons-info"></a>
	           			<span>
	                    <?php esc_html_e( 'Used when communicating with remote services with PHP.', 'brane_lang' )  ?>
	                	</span>
	                </span>
	                <?php esc_html_e( 'cURL', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php 
	                if ( $systeminfo['curl'] === 'true' ) {
	                    echo '<span class="dashicons-before dashicons-yes">'. esc_html__( 'Yes', 'brane_lang') .'</span>';
	                } else {
	                    echo '<span class="dashicons-before dashicons-dismiss">'. esc_html__( 'Your server does not have cURL enabled - cURL is used to communicate with other servers. Please contact your hosting provider.', 'brane_lang') .'</span>';
	                } ?>
	            </td>
	        </tr>

			<?php //SOAP ?>
			<tr>
	            <td data-export-label="SoapClient">
	                <span class="brane_info">
	                	<a href="#" class="brane-tooltip dashicons dashicons-info"></a>
	           			<span>
	                    <?php esc_html_e( 'Some webservices like shipping use SOAP to get information from remote servers, for example, live shipping quotes from FedEx require SOAP to be installed.', 'brane_lang' )  ?>
	                	</span>
	                </span>
	                <?php esc_html_e( 'SoapClient', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php 
	                if ( $systeminfo['soap_client'] === 'true' ) {
	                    echo '<span class="dashicons-before dashicons-yes">'. esc_html__( 'Your server has the SOAP Client enabled', 'brane_lang') .'</span>';
	                } else {
	                    echo '<span class="dashicons-before dashicons-dismiss">'. sprintf( esc_html__( 'Your server does not have the <a href="%s">SOAP Client</a> class enabled - some gateway plugins which use SOAP may not work as expected.', 'brane_lang' ), 'http://php.net/manual/en/class.soapclient.php' ) . '</span>';
	                } ?>
	            </td>
	        </tr>

	        <?php //DOMDocument ?>
			<tr>
	            <td data-export-label="DOMDocument">
	                <span class="brane_info">
	                	<a href="#" class="brane-tooltip dashicons dashicons-info"></a>
	           			<span>
	                    <?php esc_html_e( 'Some webservices like shipping use SOAP to get information from remote servers, for example, live shipping quotes from FedEx require SOAP to be installed.', 'brane_lang' )  ?>
	                	</span>
	                </span>
	                <?php esc_html_e( 'DOMDocument', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php 
	                if ( $systeminfo['dom_document'] === 'true' ) {
	                    echo '<span class="dashicons-before dashicons-yes">'. esc_html__( 'Yes', 'brane_lang') .'</span>';
	                } else {
	                    echo '<span class="dashicons-before dashicons-dismiss">'. sprintf( esc_html__( 'Your server does not have the <a href="%s">DOMDocument</a> class enabled - HTML/Multipart emails, and also some extensions, will not work without DOMDocument.', 'brane_lang' ), 'http://php.net/manual/en/class.domdocument.php' ) . '</span>';
	                } ?>
	            </td>
	        </tr>

	        <?php //GZIP ?>
			<tr>
	            <td data-export-label="GZIP">
	                <span class="brane_info">
	                	<a href="#" class="brane-tooltip dashicons dashicons-info"></a>
	           			<span>
	                    <?php esc_html_e( 'GZip (gzopen) is used to open the GEOIP database from MaxMind.', 'brane_lang' )  ?>
	                	</span>
	                </span>
	                <?php esc_html_e( 'GZIP', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php 
	                if ( $systeminfo['gzip'] === 'true' ) {
	                    echo '<span class="dashicons-before dashicons-yes">'. esc_html__( 'Yes', 'brane_lang') .'</span>';
	                } else {
	                    echo '<span class="dashicons-before dashicons-dismiss">'. sprintf( esc_html__( 'Your server does not support the <a href="%s">gzopen</a> function - this is required to use the GeoIP database from MaxMind. The API fallback will be used instead for geolocation.', 'brane_lang' ), 'http://php.net/manual/en/zlib.installation.php' ) . '</span>';
	                } ?>
	            </td>
	        </tr>

	        <?php //WP Remote Post Check ?>
			<tr>
	            <td data-export-label="WP Remote Post Check">
	                <span class="brane_info">
	                	<a href="#" class="brane-tooltip dashicons dashicons-info"></a>
	           			<span>
	                    <?php esc_html_e( 'Used to send data to remote servers.', 'brane_lang' )  ?>
	                	</span>
	                </span>
	                <?php esc_html_e( 'WP Remote Post', 'brane_lang' ); ?>:
	            </td>
	            <td>
	                <?php 
	                if ( $systeminfo['wp_remote_post'] === 'true' ) {
	                    echo '<span class="dashicons-before dashicons-yes">'. esc_html__( 'Yes', 'brane_lang') .'</span>';
	                } else {
	                    echo '<span class="dashicons-before dashicons-dismiss">'. esc_html__( 'wp_remote_post() failed. Many advanced features may not function. Contact your hosting provider.', 'brane_lang' ) . '</span>';
	                } ?>
	            </td>
	        </tr>		
        </tbody>
    </table>
    <!-- Ends Server Table -->
    <!-- Begins Plugins Table -->   
    <table id="brane_sys_plugins_table" class="sys_table">
        <thead>
	        <tr>
	            <th colspan="3" data-export-label="Active Plugins (<?php echo esc_html(count( (array) get_option( 'active_plugins' ) ) ); ?>)">
	                <?php esc_html_e( 'Active Plugins', 'brane_lang' ); ?> (<?php echo esc_html(count( (array) get_option( 'active_plugins' ) ) ); ?>)
	            </th>
	        </tr>
        </thead>
        <tbody>
        <?php
            foreach ( $systeminfo['plugins'] as $name => $plugin_data ) {
                $version_string = '';
                $network_string = '';

                if ( ! empty( $plugin_data['Name'] ) ) {
                    // link the plugin name to the plugin url if available
                    $plugin_name = esc_html( $plugin_data['Name'] );

                    if ( ! empty( $plugin_data['PluginURI'] ) ) {
                        $plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_attr__( 'Visit plugin homepage', 'brane_lang' ) . '">' . esc_html($plugin_name) . '</a>';
                    }
					?>
                    <tr>
                        <td><?php echo wp_kses_post( $plugin_name ) ?></td>
                        <td class="brane_info">&nbsp;</td>
                        <td>
                            <?php echo sprintf( _x( 'by %s', 'by author', 'brane_lang' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?>
                        </td>
                    </tr>
				<?php
                }
            }
        ?>
        </tbody>
    </table>		
