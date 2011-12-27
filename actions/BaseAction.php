<?php

class BaseAction {

	private $data = array();

	function __construct($moduleName) {
		$this->data['action'] = $moduleName;
		$this->data['time'] = time();
		$this->data['result'] = array();
	}

	public function setResponseField($field, $value) {
		$this->data['result'][$field] = $value;
	}

	public function process() {
		
	}

	public function getResult() {
		return $this->data;
	}

}