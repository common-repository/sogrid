<?php
    if( isset( $_POST['sogrid_settings_nonce'], $_POST['sogrid_settings_excerpt_max'] ) ){
		if (!wp_verify_nonce($_POST['sogrid_settings_nonce'], 'sogrid_settings_nonce')) {
			wp_die('Security check');
		}

		$allowedSettings = [
			'sogrid_settings_excerpt_max' => true
		];

        foreach ( $_POST as $key => $value ){
			if (!isset($allowedSettings[$key])) {
				continue;
			}

            update_option($key, $value);
        }
    }
?>

<div class="sog_panel_tab">

    <header>
        <h3>Settings</h3>
    </header>

    <div>
        <form method="POST">

            <input type="hidden" name="sogrid_settings_nonce" value="<?php echo esc_attr(wp_create_nonce('sogrid_settings_nonce')) ?>" >

            <table class="form-table" role="presentation">

                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="sogrid_settings_excerpt_max"><?php esc_html_e('Excerpt Max Words', 'sogrid') ?></label>
                        </th>
                        <td>
                            <input name="sogrid_settings_excerpt_max" type="number" id="sogrid_settings_excerpt_max" value="<?php echo (int)get_option('sogrid_settings_excerpt_max', 15 ) ?>" class="regular-text" required>
                            <!--<p class="description" id="tagline-description"></p></td>-->
                        </td>
                    </tr>
                </tbody>

            </table>

            <button type="submit" class="button button-primary"><?php esc_html_e('Save', 'sogrid'); ?></button>
        </form>
    </div>

</div>
