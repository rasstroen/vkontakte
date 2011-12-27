<?php

class Config {

	private static $config = array(
	    'base_path' => '/home/richest.hardtechno.ru/', // в какой директории на сервере лежит index.php
	    // MySQL
	    'dbuser' => 'root',
	    'dbpass' => '2912',
	    'dbhost' => 'localhost',
	    'dbname' => 'richest',
	);

// получем переменную из конфига
	public static function need($var_name, $default = false) {
		if (isset(self::$config[$var_name])) {
			return self::$config[$var_name];
		}
		return $default;
	}

	public static function init($local_config = false) {
		if ($local_config) {
			foreach ($local_config as $name => $value) {
				self::$config[$name] = $value;
			}
		}
	}

}