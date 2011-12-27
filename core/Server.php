<?php

class Server {

	private $result = array();
	public $request;

	function __construct() {
		$this->request = Request::getInstance();
	}

	public function process() {
		$action = $this->request->getAction();
		switch ($action) {
			case 'Init':
				$action .= "Action";
				$module = new $action($action);
				$module->process();
				$this->setResult($module->getResult());
				break;
		}
	}

	private function setResult($data, $field = 'data') {
		$this->result[$field] = $data;
	}

	public function getResult() {
		return $this->result;
	}

}