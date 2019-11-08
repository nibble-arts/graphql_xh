<?php

namespace data;


class Field {

	private $type;
	private $field;
	private $string;
	private $params;
	private $children;

	public function __construct($data) {

		// $this->type = $type;
		$this->field = $data["field"];
		$this->string = $data["string"];
		$this->params = $data["params"];

		$this->children = [];

	}


	// add child or array of children
	public function add_children($children) {

		if (is_array($children)) {
			$this->children = array_merge($this->children, $children);
		}

		else {
			$this->children[] = $children;
		}
	}
}

?>