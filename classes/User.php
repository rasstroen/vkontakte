<?php

class User {

	public $id;
	private $loaded;
	private $registered = false;
	private $data;
	private $changed = array();

	function __construct($id, $data = false) {
		$this->id = max(0, (int) $id);
	}

	function load($data = false) {
		if ($this->is_loaded())
			return $this->data;
		$this->data = Database::sql2row('SELECT * FROM `user` WHERE `id`=' . $this->id);
		if ($this->data)
			$this->registered = true;
		$this->loaded = true;
	}

	function is_registered() {
		return $this->registered;
	}

	public function is_loaded() {
		return $this->loaded;
	}

	function setProperty($field, $value, $save = true) {
		if (!$save)
			$this->data[$field] = $value;
		else
			$this->data[$field] = $this->changed[$field] = $value;
	}

	function getProperty($field, $default = false) {
		$this->load();
		return isset($this->data[$field]) ? $this->data[$field] : false;
	}

	function getClientProfile() {
		$this->load();
		return $this->data;
	}

	function save() {
		if (count($this->changed) && $this->id) {
			$this->changed['lastSave'] = time();
			foreach ($this->changed as $f => $v)
				$sqlparts[] = '`' . $f . '`=\'' . mysql_escape_string($v) . '\'';
			$sqlparts = implode(',', $sqlparts);
			$query = 'INSERT INTO `user` SET `id`=' . $this->id . ',' . $sqlparts . ' ON DUPLICATE KEY UPDATE ' . $sqlparts;
			Database::query($query);
		}
	}

}