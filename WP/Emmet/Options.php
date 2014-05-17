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
	 * Normalized options
	 * @var array
	 */
	protected $normalizedOptions;

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
			'use_codemirror' => '0',

			'profile' => 'html',

			'textarea' => array(
				'variables' => array(
					'indentation' => "\t"
				),

				'options' => array(
					'syntax' => 'html',
					'use_tab' => '1',
					'pretty_break' => '1'
				),
			),

			'codemirror' => array(
				'theme' => 'default',

				'indentWithTabs' => '1',
				'indentUnit' => '2',
				'tabSize' => '4',
				'smartIndent' => '1',

				'lineWrapping' => '',
				'lineNumbers' => '1'
			),

			'codemirror_style' => "font-family: Ricty, \"VL Gothic\", monospace, sans-serif;\nfont-size: 16px;\nline-height: 1.3;\nletter-spacing: 1px;",

			'scope' => array(
				'post' => '1',
				'theme-editor' => '1',
				'plugin-editor' => '1',
				'others' => '1'
			),

			'override_shortcuts' => '',
			'shortcuts' => array(
				'Expand Abbreviation'      => 'Meta+E',
				'Match Pair Outward'       => 'Meta+D',
				'Match Pair Inward'        => 'Shift+Meta+D',
				'Matching Pair'            => 'Meta+T',
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

				'Select Next Item'     => 'Shift+Meta+.',
				'Select Previous Item' => 'Shift+Meta+,',
				'Reflect CSS Value'    => 'Meta+B'
			)
		), get_option($this->name, array()));
	}

	/**
	 * Save options
	 *
	 * @param array $options
	 */
	public function save(Array $options) {
		$this->options = $options;
		update_option($this->name, $options);
	}

	/**
	 * Set option
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param boolean $andSave
	 */
	public function set($key, $value, $andSave = true) {
		$k = explode('.', $key);
		$o = $this->options;

		switch (count($k)) {
			case 1: $o[$k[0]] = $value; break;
			case 2: $o[$k[0]][$k[1]] = $value; break;
		}

		$andSave && $this->save($o);
	}

	/**
	 * Get option
	 *
	 * @param string $key
	 * @param boolean $normalize
	 * @return mixed
	 */
	public function get($key = null, $normalize = false) {
		$options = $normalize ? $this->normalizedOptions() : $this->options;

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
	 * Key exists
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function exists($key) {
		$keys = explode('.', $key);
		$options = $this->options;

		while (count($keys) > 0) {
			$key = array_shift($keys);

			if (!array_key_exists($key, $options)) {
				return false;
			}

			$options = $options[$key];
		}

		return true;
	}

	/**
	 * Option to JSON
	 *
	 * @param string $key
	 * @return string
	 */
	public function toJSON($key = null) {
		return json_encode($this->get($key, true));
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
		if ($this->normalizedOptions) {
			return $this->normalizedOptions;
		}

		if ($this->options['use_codemirror']) {
			$this->normalizedOptions = $this->normalizedOptionsForCodeMirror();
		} else {
			$this->normalizedOptions = $this->normalizedOptionsForTextarea();
		}

		return $this->normalizedOptions;
	}

	/**
	 * Normalized options for Textarea
	 *
	 * @return array
	 */
	protected function normalizedOptionsForTextarea() {
		$options = $this->options;

		// Boolean
		foreach (array('use_tab' , 'pretty_break') as $key) {
			$options['textarea']['options'][$key] = $options['textarea']['options'][$key] === '1';
		}

		unset($options['codemirror']);

		return $options;
	}

	/**
	 * Normalized options for CodeMirror
	 *
	 * @return array
	 */
	protected function normalizedOptionsForCodeMirror() {
		$options = $this->options;

		// Boolean
		foreach (array('indentWithTabs', 'smartIndent', 'lineWrapping' , 'lineNumbers') as $key) {
			$options['codemirror'][$key] = $options['codemirror'][$key] === '1';
		}

		// Integer
		foreach (array('indentUnit', 'tabSize') as $key) {
			$options['codemirror'][$key] = (int)$options['codemirror'][$key];
		}

		// Indent
		if ($options['codemirror']['indentWithTabs']) {
			$options['codemirror']['indentUnit'] = $options['codemirror']['tabSize'];
		}

		// Shortcuts
		foreach ($options['shortcuts'] as $type => $shortcutKey) {
			$options['shortcuts'][$type] = str_replace(
				array('+', 'Meta', 'Cmd-Shift', 'Alt-Cmd', 'Alt-Shift'),
				array('-', 'Cmd', 'Shift-Cmd', 'Cmd-Alt', 'Shift-Alt'),
				$shortcutKey
			);
		}

		unset($options['textarea']);

		return $options;
	}
}