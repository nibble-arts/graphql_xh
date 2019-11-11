<?php

namespace data;

class Type {
	
	private $name;
	private $fields;
	
	private $types;

	// create type from ini file
	public function __construct ($name) {
		$this->name = $name;
	}


	public function add_field($field) {

		$this->fields[$field->name()] = $field;
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