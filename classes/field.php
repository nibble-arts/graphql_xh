<?php

namespace data;


class Field {

	private $type;
	private $name;
	private $value;
	private $params;

	public function __construct($data) {

		// $this->type = $type;
		$this->name = $data["name"];
		$this->value = $data["value"];
		$this->params = $data["params"];

	}


	public function name($name = false) {

		if (!$name) {
			return $this->name;
		}

		else {
			$this->name = $name;
		}
	}
}

?>