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
			
			<h3 class="title"><?php _e('360i Velocity Post Bookmarklet') ?></h3>
			<p><?php _e('360i Post is a bookmarklet: a little app that runs in your browser and lets you grab bits of the web.');?></p>
			<p><?php _e('Use the "360i Post" button to clip text, images and videos from any article in 360i Velocity. Then edit and add more straight from the popup window before you save or publish it in a post on your site.'); ?></p>
			<p><?php _e('You MUST HIGHLIGHT what you want to Post before invoking the bookmarklet. Drag-and-drop the following link to your bookmarks bar or right click it and add it to your favorites for a posting shortcut.') ?></p>
				<p class="pressthis"><a onclick="return false;" oncontextmenu="if(window.navigator.userAgent.indexOf('WebKit')!=-1||window.navigator.userAgent.indexOf('MSIE')!=-1){jQuery('.pressthis-code').show().find('textarea').focus().select();return false;}" href="<?php echo htmlspecialchars( get_shortcut_link() ); ?>"><span><?php _e('360i Post') ?></span></a></p>
			<div class="pressthis-code" style="display:none;">
				<p class="description"><?php _e('If your bookmarks toolbar is hidden: copy the code below, open your Bookmarks manager, create new bookmark, type Press This into the name field and paste the code into the URL field.') ?></p>
				<p><textarea rows="5" cols="120" readonly="readonly"><?php echo htmlspecialchars( get_shortcut_link() ); ?></textarea></p>
			</div>
			<div class="m360ivelocity">
			<div class="icon32" id="360i-logo"><br></div>
				<H3 class="title"><?php _e('Video Tutorial on Using Bookmarklet') ?></h3>
				<iframe width="560" height="315" src="//www.youtube.com/embed/V6Yq8FddOUE" frameborder="0" allowfullscreen></iframe>
			</div>
			<?php
		}
		?>
		
	</div>
</div>
