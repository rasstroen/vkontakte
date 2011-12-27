<?php

class Request {

	private $post;
	private static $instance = false;

	function __construct() {
		foreach ($_POST as $f => $v) {
			$this->_set($f, $v);
		}
		foreach ($_GET as $f => $v) {
			$this->_set($f, $v);
		}
	}

	public static function getInstance() {
		if (self::$instance === false) {
			self::$instance = new Request();
		}
		return self::$instance;
	}

	public static function set($field, $value) {
		return self::getInstance()->_set($field, $value);
	}

	function _set($f, $v) {
		$this->post[$f] = $v;
	}

	public static function get($field, $default = false) {
		return self::getInstance()->_get($field, $default);
	}

	function _get($field, $default = false) {
		if (isset($this->post[$field]))
			return $this->post[$field];
		return $default;
	}

	public static function getAction() {
		return self::getInstance()->_getAction();
	}

	function _getAction() {
		return $this->_get('action');
	}

}