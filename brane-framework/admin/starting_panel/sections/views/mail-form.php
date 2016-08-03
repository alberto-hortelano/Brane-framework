<div id="brane_mail_form_wrapper" class="brane_row">
	<form action="" method="post" enctype="multipart/form-data" id="brane_sys_rep_form">
		<div class="col-6">
			<label for="brane-sysinfo-mail"><?php esc_html_e( 'Email Address to send message', 'brane_lang' ) ?><span>*</span></label>
			<span class="description"><?php esc_html_e( 'Comma-separated for more than one address', 'brane_lang' ) ?>.</span>
			<input type="email" name="brane-sysinfo-mail" id="brane-sysinfo-mail" placeholder="<?php esc_attr_e('Your Mail','brane_lang') ?>" />		
		</div>
		<div class="col-6">
			<label for="brane-sysinfo-subject"><?php esc_html_e( 'Subject', 'brane_lang' ) ?><span>*</span></label>
			<span class="description"><?php esc_html_e( 'Subject or Issue', 'brane_lang' ) ?>.</span>
			<input type="text" name="brane-sysinfo-subject" id="brane-sysinfo-subject" placeholder="<?php esc_attr_e('Subject','brane_lang') ?>" />
		</div>
		<div class="col-12">
			<label for="brane-sysinfo-message"><?php esc_html_e( 'Additional Message', 'brane_lang' ) ?></label>
			<p class="description"><?php esc_html_e( 'Your System Info will be attached automatically to this email form', 'brane_lang' ) ?>.</p>
			<textarea class="ssi-email-textarea" name="brane-sysinfo-message" id="brane-sysinfo-message" placeholder="<?php esc_attr__('System Info Message','brane_lang') ?>"></textarea>
			<?php submit_button( esc_html__( 'Send Email', 'brane_lang' ) , 'brane_button' ) ?>
		</div>
	</form>
</div>