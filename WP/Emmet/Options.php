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

			'override_shortcuts' => '',

			'shortcuts' => array(
				'Expand Abbreviation'      => 'Cmd-E',
				'Match Pair Outward'       => 'Cmd-D',
				'Match Pair Inward'        => 'Shift-Cmd-D',
				'Matching Pair'            => 'Cmd-T',
				'Wrap with Abbreviation'   => 'Shift-Cmd-A',
				'Next Edit Point'          => 'Ctrl-Alt-Right',
				'Prev Edit Point'          => 'Ctrl-Alt-Left',
				'Select Line'              => 'Cmd-L',
				'Merge Lines'              => 'Cmd-Shift-M',
				'Toggle Comment'           => 'Cmd-/',
				'Split/Join Tag'           => 'Cmd-J',
				'Remove Tag'               => 'Cmd-K',
				'Evaluate Math Expression' => 'Shift-Cmd-Y',

				'Increment number by 1'   => 'Ctrl-Up',
				'Decrement number by 1'   => 'Ctrl-Down',
				'Increment number by 0.1' => 'Alt-Up',
				'Decrement number by 0.1' => 'Alt-Down',
				'Increment number by 10'  => 'Ctrl-Alt-Up',
				'Decrement number by 10'  => 'Ctrl-Alt-Down',

				'Select Next Item'     => 'Shift-Cmd-.',
				'Select Previous Item' => 'Shift-Cmd-,',
				'Reflect CSS Value'    => 'Cmd-B'
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