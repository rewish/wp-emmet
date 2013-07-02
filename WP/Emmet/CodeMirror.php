<?php
class WP_Emmet_CodeMirror {
	/**
	 * Domain
	 * @var string
	 */
	public $domain;

	/**
	 * Themes
	 * @var array
	 */
	public $themes = array();

	/**
	 * Modes
	 * @var array
	 */
	public $modes = array();

	/**
	 * Constructor
	 *
	 * @param string $domain
	 */
	public function __construct($domain = 'codemirror') {
		$this->domain = $domain;
		$this->registerStyle('codemirror');
		$this->registerThemes();
		$this->registerScript('codemirror');
		$this->registerModes();
	}

	/**
	 * Register style
	 *
	 * @param string $fileName
	 * @param string $theme
	 */
	public function registerStyle($fileName, $theme = null) {
		$domain = $theme ? "{$this->domain}-{$theme}" : $this->domain;
		WP_Emmet::registerStyle($domain, $this->styleFileURL($fileName));
	}

	/**
	 * Register script
	 *
	 * @param string $fileName
	 * @param string $mode
	 */
	public function registerScript($fileName, $mode = null) {
		$domain = $mode ? "{$this->domain}-{$mode}" : $this->domain;
		WP_Emmet::registerScript($domain, $this->scriptFileURL($fileName));
	}

	/**
	 * Enqueue style
	 *
	 * @param string $theme
	 */
	public function enqueueStyle($theme = null) {
		wp_enqueue_style($theme ? "{$this->domain}-{$theme}" : $this->domain);
	}

	/**
	 * Enqueue script
	 *
	 * @param string $mode
	 */
	public function enqueueScript($mode = null) {
		wp_enqueue_script($mode ? "{$this->domain}-{$mode}" : $this->domain);
	}

	/**
	 * Enqueue all scripts
	 */
	public function enqueueAllScripts() {
		$this->enqueueScript();
		array_walk($this->modes, array($this, 'enqueueScript'));
	}

	/**
	 * Style file URL
	 *
	 * @param $name
	 * @return string
	 */
	protected function styleFileURL($name) {
		return WP_Emmet::assetURL("css/codemirror/{$name}.css");
	}

	/**
	 * Script file URL
	 *
	 * @param $name
	 * @return string
	 */
	protected function scriptFileURL($name) {
		return WP_Emmet::assetURL("js/codemirror/{$name}.js");
	}

	/**
	 * Register themes
	 */
	protected function registerThemes() {
		$this->themes = array('default' => 'default');
		$themes = glob(WP_Emmet::assetPath('css/codemirror/theme/*.css'));

		foreach ($themes as $theme) {
			$name = basename($theme, '.css');
			$this->registerStyle("theme/$name", $name);
			$this->themes[$name] = $name;
		}
	}

	/**
	 * Register modes
	 */
	protected function registerModes() {
		$modes = glob(WP_Emmet::assetPath('js/codemirror/mode/*.js'));

		foreach ($modes as $mode) {
			$name = basename($mode, '.js');
			$this->registerScript("mode/$name", $name);
			$this->modes[$name] = $name;
		}
	}
}