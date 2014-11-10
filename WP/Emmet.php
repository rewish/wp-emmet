<?php
/**
 * WP Emmet
 */
require_once dirname(__FILE__) . '/Emmet/Exception.php';
require_once dirname(__FILE__) . '/Emmet/Lang.php';
require_once dirname(__FILE__) . '/Emmet/Options.php';
require_once dirname(__FILE__) . '/Emmet/FormHelper.php';
require_once dirname(__FILE__) . '/Emmet/Migration.php';
require_once dirname(__FILE__) . '/Emmet/CodeMirror.php';

class WP_Emmet {
	/**
	 * Emmet options instance
	 * @var WP_Emmet_Options
	 */
	public $Options;

	/**
	 * Emmet lang instance
	 * @var WP_Emmet_Lang
	 */
	protected $Lang;

	/**
	 * CodeMirror instance
	 * @var WP_Emmet_CodeMirror
	 */
	public $CodeMirror;

	/**
	 * Get the Asset URL
	 *
	 * @param $name
	 * @return string
	 */
	public static function assetURL($name) {
		return plugin_dir_url(WP_EMMET_FILE) . "assets/$name";
	}

	/**
	 * Get the Asset path
	 *
	 * @param $name
	 * @return string
	 */
	public static function assetPath($name) {
		return plugin_dir_path(WP_EMMET_FILE) . "assets/$name";
	}

	/**
	 * Register style
	 *
	 * @param $domain
	 * @param $src
	 * @param array $options
	 */
	public static function registerStyle($domain, $src, Array $options = array()) {
		$options += array('deps' => array(), 'ver' => false, 'media' => 'all');
		wp_register_style($domain, $src, $options['deps'], $options['ver'], $options['media']);
	}

	/**
	 * Register script
	 *
	 * @param $domain
	 * @param $src
	 * @param array $options
	 */
	public static function registerScript($domain, $src, Array $options = array()) {
		$options += array('deps' => array(), 'ver' => false, 'in_footer' => true);
		wp_register_script($domain, $src, $options['deps'], $options['ver'], $options['in_footer']);
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->Lang = new WP_Emmet_Lang();
		$this->Options = new WP_Emmet_Options();
		$this->CodeMirror = new WP_Emmet_CodeMirror();

		WP_Emmet_Migration::migrate($this);

		add_action('current_screen', array($this, 'registerHooks'));
	}

	/**
	 * Register hooks
	 */
	public function registerHooks() {
		if (!$this->isInScope()) {
			return;
		}

		add_action('admin_print_styles', array($this, 'printStyles'));
		add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
		add_action('admin_print_footer_scripts', array($this, 'applyScripts'), 1);
	}

	/**
	 * Page is in scope?
	 *
	 * @return boolean
	 */
	public function isInScope() {
		$screen = get_current_screen();
		$type = "scope.{$screen->base}";
		$isInScope = true;

		if ($screen->base === 'settings_page_' . WP_EMMET_DOMAIN) {
			return apply_filters('wp_emmet_is_in_scope', $isInScope);
		}

		if (!$this->Options->exists($type)) {
			$type = 'scope.others';
		}

		$isInScope = $this->Options->get($type) === '1';

		return apply_filters('wp_emmet_is_in_scope', $isInScope);
	}

	/**
	 * Print styles
	 */
	public function printStyles() {
		if ($this->isCodeMirrorMode()) {
			$this->CodeMirror->enqueueStyle();
			$this->CodeMirror->enqueueStyle($this->Options->get('codemirror.theme'));
		}
		wp_enqueue_style('wp_emmet', self::assetURL('css/wp_emmet.css'));
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueueScripts() {
		$type = $this->editorType();
		$deps = array('underscore');

		if ($this->isCodeMirrorMode()) {
			$this->CodeMirror->enqueueAllScripts();
			$deps[] = $type;
		}

		wp_enqueue_script('emmet', self::assetURL("js/{$type}/emmet.js"), $deps, false, true);
		wp_enqueue_script('wp_wmmet', self::assetURL("js/wp_emmet.js"), array('emmet','editor'));
	}

	/**
	 * Apply scripts
	 */
	public function applyScripts() {
		$shortcuts = $this->Options->get('shortcuts', true);
		$type = $this->editorType();
		require_once WP_EMMET_VIEW_DIR . DIRECTORY_SEPARATOR . "apply_for_{$type}.php";
	}

	/**
	 * Editor type
	 *
	 * @return string
	 */
	public function editorType() {
		return $this->isCodeMirrorMode() ? 'codemirror' : 'textarea';
	}

	/**
	 * Is CodeMirror mode
	 *
	 * @return bool
	 */
	public function isCodeMirrorMode() {
		return $this->Options->get('use_codemirror') === '1';
	}
}
