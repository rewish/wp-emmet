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
		add_action('admin_enqueue_scripts', array($this, 'enqueueEmmet'));
		add_action('admin_print_footer_scripts', array($this, 'applyEmmet'));
	}

	/**
	 * Enqueue the Emmet
	 */
	public function enqueueEmmet() {
		wp_enqueue_script(WP_EMMET_DOMAIN, $this->getEmmetURL(), array('underscore'), false, true);
	}

	/**
	 * Apply the Emmet
	 */
	public function applyEmmet() {
		$shortcuts = $this->Options->get('shortcuts');
		require_once WP_EMMET_VIEW_DIR . DIRECTORY_SEPARATOR . 'apply_emmet.php';
	}

	/**
	 * Get the Emmet URL
	 *
	 * @return string
	 */
	public function getEmmetURL() {
		return plugin_dir_url(WP_EMMET_FILE) . 'js/emmet.js';
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
