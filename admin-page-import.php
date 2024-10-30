<?php
function fbg_import_page() {
    echo '<div class="wrap">';
    echo '  <h1>' . __('Import Facebook Group Posts', 'fbg') . '</h1>';

	if(
		isset($_POST['fbg_nonce']) &&
		wp_verify_nonce($_POST['fbg_nonce'], 'fbg_pool_nonce') &&
		isset($_POST['fbg_pool_btn']) &&
		current_user_can('edit_others_pages')
	) {
		$post_type = ( get_option('fbg_post_type') != '') ? get_option('fbg_post_type') : 'post';
		foreach ($_POST['fbg_pool'] as $item) {

			$the_post = wp_insert_post(array(
				'post_content' => sanitize_textarea_field($item['content']),
				'post_title' => substr(sanitize_textarea_field($item['content']), 0, 32) . ' ...',
				'post_type' => $post_type
			));
			add_post_meta($the_post, 'fbg_email', sanitize_email($item['email']), true);
		}

		echo '<div id="fbg-message" class="notice notice-success is-dismissible">';
		echo '  <p>'. __('Your new post(s) was created!.', 'fbg') .'</p>';
		echo '</div>';

	}


	if(
        isset($_POST['fbg_nonce']) &&
        wp_verify_nonce($_POST['fbg_nonce'], 'fbg_pull_posts') &&
        isset($_POST['fb-pull-posts']) &&
        $_POST['fb-pull-posts'] == 1 &&
        current_user_can('edit_others_pages')
    ) {
		$fb = new \Facebook\Facebook([
			'app_id' => get_option('fbg_app_id'),
			'app_secret' => get_option('fbg_app_secret'),
			'default_graph_version' => 'v3.0',
		]);

		try {
			$response = $fb->get(
				'/'.get_option('fbg_group').'/feed',
				get_option('fbg_access_token')
			);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		    if( $e->getCode() == 190 ) {
			    echo '<div id="fbg-message" class="notice notice-warning is-dismissible">';
			    echo '  <p>'. __('You need to refresh your Access Token via the settings page.', 'fbg') .'</p>';
			    echo '</div>';
            }
		    else
    			echo __('The action returned with an error: ', 'fbg') . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		$response = (array) $response;
		$fb_posts = array();
		if( !empty($response) ) {
			$i = 0;
			foreach ($response as $data) {
				$i++; if($i == 4)
					$fb_posts = $data['data'];
			}
		}

        echo '<h2>' . __('Your Facebook Group Posts', 'fbg') . '</h2>';

		if(!empty($fb_posts)) : $i = 0;?>
			<table id="fbg-posts-table" class="wp-list-table widefat fixed striped">
				<thead>
				<tr>
					<td width="25"> </td>
					<th><?php _e('Post Content', 'fbg');?></th>
					<th><?php _e('Post Email');?></th>
					<th><?php _e('Publish Date', 'fbg');?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($fb_posts as $fb_post) :
					$i++;
					if( isset($fb_post['message']) ) {
						preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $fb_post['message'], $matches);
						?>
						<tr>
							<td>
								<input type="checkbox" name="fb-post-<?php echo $i;?>" value="1">
								<div>
									<input type="hidden" name="fbg_pool[<?php echo $i;?>][email]" value="<?php if(isset($matches[0][0])) echo $matches[0][0];?>">
									<input type="hidden" name="fbg_pool[<?php echo $i;?>][content]" value="<?php echo $fb_post['message'];?>">
								</div>
							</td>
							<td><?php echo wp_trim_words($fb_post['message'], 32, '...');?></td>
							<td><?php if(isset($matches[0][0])) echo $matches[0][0];?></td>
							<td><?php
                                $date = explode('T', $fb_post['updated_time']);
                                $time = explode(':', $date[1]);
                                echo $date[0] . ' / ' . $time[0] . ':' . $time[1];?></td>
						</tr>
						<?php
					}
				endforeach; ?>
				</tbody>
			</table>
		<?php endif;?>
		<form id="fbg-pool" method="post" action="" style="margin-top: 15px;">
			<?php wp_nonce_field( 'fbg_pool_nonce', 'fbg_nonce' ); ?>
			<input class="button button-primary" type="submit" name="fbg_pool_btn" value="<?php _e('Create Posts', 'fbg');?>">
		</form>
		</div>
		<?php
	}
	else {
	    echo '<div>';
	    echo '  <p>' . __('To import posts from your group simply press the next button:', 'fbg') . '</p>';
	    echo '  <form method="post" action="">';
		wp_nonce_field( 'fbg_pull_posts', 'fbg_nonce' );
	    echo '      <input type="hidden" name="fb-pull-posts" value="1">';
	    echo '      <input class="button button-primary" type="submit" value="' . __('Pull Posts', 'fbg') . '">';
	    echo '  </form>';
		echo '</div>';
    }

}