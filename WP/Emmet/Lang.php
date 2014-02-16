<?php
/**
 * WP Emmet Lang
 */
class WP_Emmet_Lang {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('plugins_loaded', array($this, 'load'));
	}

	/**
	 * Loads a MO file into the domain $domain
	 */
	public function load() {
		load_textdomain(WP_EMMET_DOMAIN, $this->getPath());
	}

	/**
	 * Get path to the .mo file
	 *
	 * @return string Path to the .mo file
	 */
	public function getPath() {
		return WP_EMMET_LANG_DIR . DIRECTORY_SEPARATOR . get_locale() . '.mo';
	}
}