<h1 class="sog_title">Sogrid (<?php echo esc_html(SOGRID_VERSION) ?>)</h1>

<div class="sog_panel">

    <?php
        require_once __DIR__ . '/menu.php';

        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
		$tab = sanitize_file_name($tab);

		foreach ($items as $item) {
			if ($item['tab'] === $tab) {
				require_once __DIR__ . '/tabs/'.$tab.'.php';
				break;
			}
		}
    ?>
</div>
