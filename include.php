<?php

$root = Config::need('base_path');
$includePathes = array(
    $root,
    $root . 'classes',
    $root . 'actions',
    $root . 'core',
);

set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $includePathes));

function __autoload($className) {
	require_once($className . '.php');
}