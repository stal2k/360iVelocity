<div class="wrap">
	<div class="m360ivelocity">
		<div class="icon32" id="360i-logo"><br></div>
		<h2><?php _e( 'Settings', M360IVELOCITY_DOMAIN ); ?></h2>
		<?php
		if (!current_user_can('administrator')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		} else {
			settings_errors();
			?>
			<form id="m360ivelocity_plugin_settings" action="options.php" method="post">
				<?php settings_fields('m360ivelocity_settings'); ?>
				<?php do_settings_sections('m360ivelocity-plugin-settings-section'); ?>
				<?php submit_button(); ?>
			</form>
		<?php
		}
		?>
	</div>
</div>
