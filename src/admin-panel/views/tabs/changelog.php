<div class="sog_panel_tab">
    <header>
        <h3>Change Log</h3>
    </header>

    <div>
		<?php
		global $wp_filesystem;

		if ( ! function_exists( 'request_filesystem_credentials' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( ! WP_Filesystem() ) {
			request_filesystem_credentials( site_url() );
		}

		$file_path = __DIR__ . '/../../../../readme.txt';
		$content = '';

		if ( $wp_filesystem->exists( $file_path ) ) {
			$content = $wp_filesystem->get_contents( $file_path );
			$content = preg_replace( '/.*== Changelog ==/is', '', $content );
			echo nl2br( esc_html( $content ) );
		} else {
			echo 'Failed to retrieve changelog.';
		}
		?>
    </div>
</div>
