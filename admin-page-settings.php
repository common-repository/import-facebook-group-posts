<?php
function fbg_settings_page() {
	?>
	<div class="wrap">
	<h1><?php _e('Settings', 'fbg');?></h1>

	<form method="post" action="options.php">
		<?php settings_fields( 'fbg' ); ?>
		<?php do_settings_sections( 'fbg' ); ?>

		<table class="form-table">
			<tr valign="top">
				<th scope="row">App ID</th>
				<td><input type="text" name="fbg_app_id" value="<?php echo esc_attr( get_option('fbg_app_id') ); ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">App Secret</th>
				<td><input type="text" name="fbg_app_secret" value="<?php echo esc_attr( get_option('fbg_app_secret') ); ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">Access Token</th>
				<td>
					<input type="text" name="fbg_access_token" value="<?php echo esc_attr( get_option('fbg_access_token') ); ?>" />
					<p><?php _e('Get your Access Token', 'fbg');?> <a href="https://developers.facebook.com/tools/explorer/" target="_blank"><?php _e('here', 'fbg');?></a>.</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Facebook Group ID</th>
				<td><input type="text" name="fbg_group" value="<?php echo esc_attr( get_option('fbg_group') ); ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">Post Type Key (default is: post)</th>
				<td><input type="text" name="fbg_post_type" value="<?php echo esc_attr( get_option('fbg_post_type') ); ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">Post Status</th>
				<td>
					<select name="fbg_post_status">
						<option value="draft" <?php selected('draft', get_option('fbg_post_status'));?>>Draft (default)</option>
						<option value="pending" <?php selected('pending', get_option('fbg_post_status'));?>>Pending</option>
						<option value="publish" <?php selected('publish', get_option('fbg_post_status'));?>>Publish</option>
					</select>
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>

	</form>
	</div>
	<?php

}