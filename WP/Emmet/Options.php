<?php
/**
 * WP Emmet Options
 */
class WP_Emmet_Options {
	/**
	 * Name of option
	 * @var string
	 */
	protected $name;

	/**
	 * Options
	 * @var array
	 */
	protected $options;

	/**
	 * Constructor
	 *
	 * @param string $name Optional, Name of option.
	 */
	public function __construct($name = WP_EMMET_DOMAIN) {
		$this->name = $name;
		$this->options = $this->load();
		$this->addAdminMenu();
	}

	/**
	 * Load options
	 */
	public function load() {
		return array_merge(array(
			'editor' => array(
				'profile' => 'html',
				'theme' => 'default',

				'indentWithTabs' => '1',
				'indentUnit' => 2,
				'tabSize' => 4,
				'smartIndent' => '1',

				'lineWrapping' => '1',
				'lineNumbers' => '1'
			),

			'expand_with_tab' => '1',
			'override_shortcuts' => '',

			'shortcuts' => array(
				'Expand Abbreviation'      => 'Meta+E',
				'Match Pair Outward'       => 'Meta+D',
				'Match Pair Inward'        => 'Shift+Meta+D',
				'Wrap with Abbreviation'   => 'Shift+Meta+A',
				'Next Edit Point'          => 'Ctrl+Alt+Right',
				'Prev Edit Point'          => 'Ctrl+Alt+Left',
				'Select Line'              => 'Meta+L',
				'Merge Lines'              => 'Meta+Shift+M',
				'Toggle Comment'           => 'Meta+/',
				'Split/Join Tag'           => 'Meta+J',
				'Remove Tag'               => 'Meta+K',
				'Evaluate Math Expression' => 'Shift+Meta+Y',

				'Increment number by 1'   => 'Ctrl+Up',
				'Decrement number by 1'   => 'Ctrl+Down',
				'Increment number by 0.1' => 'Alt+Up',
				'Decrement number by 0.1' => 'Alt+Down',
				'Increment number by 10'  => 'Ctrl+Alt+Up',
				'Decrement number by 10'  => 'Ctrl+Alt+Down',

				'Select Next Item'     => 'Meta+.',
				'Select Previous Item' => 'Meta+,',
				'Reflect CSS Value'    => 'Meta+Shift+B'
			)
		), get_option($this->name, array()));
	}

	/**
	 * Set option
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key, $value) {
		$k = explode('.', $key);
		$o =& $this->options;

		switch (count($k)) {
			case 1: $o[$k[0]] = $value; break;
			case 2: $o[$k[0]][$k[1]] = $value; break;
		}

		update_option($this->name, $this->options);
	}

	/**
	 * Get option
	 *
	 * @param string $key
	 */
	public function get($key = null) {
		$options = $this->normalizedOptions();

		if (empty($key)) {
			return $options;
		}

		$keys = explode('.', $key);
		$option = $options;

		do {
			$key = array_shift($keys);
			if (!isset($option[$key])) {
				$option = null;
				break;
			}
			$option = $option[$key];
		} while (!empty($keys));

		return $option;
	}

	/**
	 * Option to JSON
	 *
	 * @param string $key
	 */
	public function toJSON($key = null) {
		return json_encode($this->get($key));
	}

	/**
	 * Add action
	 */
	public function addAdminMenu() {
		add_action('admin_menu', array($this, 'addOptionsPage'));
	}

	/**
	 * Add options page
	 */
	public function addOptionsPage() {
		add_options_page('Emmet', 'Emmet', 'manage_options', $this->name, array($this, 'pageOptions'));
	}

	/**
	 * Page options
	 */
	public function pageOptions() {
		global $wp_emmet;
		$domain = WP_EMMET_DOMAIN;
		$form = new WP_Emmet_FormHelper($this->name, $this->options);
		$themes = $wp_emmet->CodeMirror->themes;
		require_once WP_EMMET_VIEW_DIR . DIRECTORY_SEPARATOR . 'options.php';
	}

	/**
	 * Normalized options
	 *
	 * @return array
	 */
	public function normalizedOptions() {
		$options = $this->options;

		// Boolean
		foreach (array('indentWithTabs', 'smartIndent', 'lineWrapping' , 'lineNumbers') as $key) {
			$options['editor'][$key] = $options['editor'][$key] === '1';
		}

		// Integer
		foreach (array('indentUnit', 'tabSize') as $key) {
			$options['editor'][$key] = (int)$options['editor'][$key];
		}

		if ($options['editor']['indentWithTabs']) {
			$options['editor']['indentUnit'] = $options['editor']['tabSize'];
		}

		return $options;
	}
}