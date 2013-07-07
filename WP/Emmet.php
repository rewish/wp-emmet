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
		$this->setupActions();
	}

	/**
	 * Initialize
	 */
	public function init() {
		$this->Lang = new WP_Emmet_Lang();
		$this->Options = new WP_Emmet_Options();
		$this->CodeMirror = new WP_Emmet_CodeMirror();
		$this->migrate();
	}

	/**
	 * Migrate
	 */
	public function migrate() {
		WP_Emmet_Migration::migrate($this);
	}

	/**
	 * Setup actions
	 */
	public function setupActions() {
		add_action('init', array($this, 'init'));
		add_action('admin_print_styles', array($this, 'printStyles'));
		add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
		add_action('admin_print_footer_scripts', array($this, 'applyScripts'), 1);
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
		if ($this->isCodeMirrorMode()) {
			$this->CodeMirror->enqueueAllScripts();
		}
		wp_enqueue_script('wp_wmmet', self::assetURL("js/wp_emmet.js"));
		wp_enqueue_script('emmet', self::assetURL("js/{$type}/emmet.js"), array('underscore'), false, true);
	}

	/**
	 * Apply scripts
	 */
	public function applyScripts() {
		$shortcuts = $this->Options->get('shortcuts');
		$type = $this->editorType();
		require_once WP_EMMET_VIEW_DIR . DIRECTORY_SEPARATOR . "apply_for_{$type}.php";
	}

	/**
	 * Editor type
	 *
	 * @return string
	 */
	protected function editorType() {
		return $this->isCodeMirrorMode() ? 'codemirror' : 'textarea';
	}

	/**
	 * Is CodeMirror mode
	 *
	 * @return bool
	 */
	protected function isCodeMirrorMode() {
		return $this->Options->get('use_codemirror') === '1';
	}
}
