<?php

class Database {

	private static $instance = false;
	private $link;

	public static function get() {
		if (self::$instance === false) {
			self::$instance = new Database();
		}
		return self::$instance;
	}

	function __construct() {
		$link = mysql_connect(Config::need('dbserver'), Config::need('dbuser'), Config::need('dbpass'));
		mysql_select_db(Config::need('dbname'), $link);
		mysql_query('SET NAMES utf8', $link);
		$this->link = $link;
	}

	public static function query($query, $throwError = true) {
		return self::get()->_query($query, $throwError);
	}

	public static function escape($s) {
		return self::get()->_escape($s);
	}

	public static function lastInsertId() {
		return self::get()->_lastInsertId();
	}

	public static function sql2array($query, $indexedFiled = false) {
		return self::get()->_sql2array($query, $indexedFiled);
	}

	public static function sql2row($query, $fetch_type = MYSQL_ASSOC) {
		return self::get()->_sql2row($query, $fetch_type);
	}

	public static function sql2single($query) {
		return self::get()->_sql2single($query);
	}

	public function _query($query, $throwError = true) {
		if (!$r = mysql_query($query, $this->link))
			if ($throwError)
				throw new Exception(mysql_error($this->link));
			else
				return false;
		return $r;
	}

	public function _escape($s) {
		return mysql_real_escape_string($s);
	}

	public function _lastInsertId() {
		return mysql_insert_id($this->link);
	}

	public function _sql2array($query, $indexedFiled = false) {
		$out = array();
		$r = $this->_query($query);
		if (!$indexedFiled) {
			while ($row = mysql_fetch_array($r, MYSQL_ASSOC)) {
				$out[] = $row;
			}
		} else {
			while ($row = mysql_fetch_array($r, MYSQL_ASSOC)) {
				$out[$row[$indexedFiled]] = $row;
			}
		}
		return $out;
	}

	public function _sql2row($query, $fetch_type = MYSQL_ASSOC) {
		return array_shift($this->_sql2array($query));
	}

	public function _sql2single($query) {
		$r = $this->_sql2row($query, MYSQL_NUM);
		if (is_array($r))
			return array_shift($r);
		return false;
	}

}
