<?php
/**
 * WP Emmet
 */
require_once dirname(__FILE__) . '/Emmet/Exception.php';
require_once dirname(__FILE__) . '/Emmet/Lang.php';
require_once dirname(__FILE__) . '/Emmet/Options.php';

class WP_Emmet {
	/**
	 * Emmet options instance
	 * @var WP_Emmet_Options
	 */
	protected $Options;

	/**
	 * Emmet lang instance
	 * @var WP_Emmet_Lang
	 */
	protected $Lang;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->Lang = new WP_Emmet_Lang();
		$this->Options = new WP_Emmet_Options();

		$this->setupActions();
	}

	/**
	 * Setup actions
	 */
	public function setupActions() {
		add_action('admin_print_footer_scripts', array($this, 'loadEmmet'));
	}

	/**
	 * Load the Emmet
	 */
	public function loadEmmet() {
		$shortcuts = $this->Options->get('shortcuts');
		require_once WP_EMMET_VIEW_DIR . DIRECTORY_SEPARATOR . 'load_emmet.php';
	}

	/**
	 * Is load underscore
	 *
	 * @return boolean
	 */
	public function isLoadUnderscore() {
		global $editing;
		return !$editing || 3.5 > floatval(get_bloginfo('version'));
	}

	/**
	 * Get JavaScript URL
	 *
	 * @param string $name File name
	 * @return string
	 */
	public function getJavaScriptURL($name) {
		return plugin_dir_url(WP_EMMET_FILE) . 'js/' . $name;
	}

	/**
	 * Get option
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getOption($key) {
		return $this->Options->get($key);
	}
}