<?php
/*
Plugin Name: WP Emmet
Plugin URI: https://github.com/rewish/wp-emmet
Description: Emmet (ex-Zen Coding) for WordPress.
Version: 0.3.4
Author: rewish
Author URI: https://github.com/rewish
*/
require_once dirname(__FILE__) . '/WP/Emmet.php';

define('WP_EMMET_DOMAIN', 'wp-emmet');
define('WP_EMMET_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . basename(dirname(__FILE__)));
define('WP_EMMET_FILE', WP_EMMET_DIR . DIRECTORY_SEPARATOR . basename(__FILE__));
define('WP_EMMET_LANG_DIR', WP_EMMET_DIR . DIRECTORY_SEPARATOR . 'langs');
define('WP_EMMET_VIEW_DIR', WP_EMMET_DIR . DIRECTORY_SEPARATOR . 'views');

$wp_emmet = new WP_Emmet();
