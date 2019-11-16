<?php

namespace data;

class Type {
	
	private $name;
	private $fields;
	
	private $types;

	// create type from ini file
	public function __construct ($type) {

		$this->name($type["name"]);

		$this->add_fields($type["children"][0]);
	}


	// add array of fields
	// input graphql array
	public function add_fields($fields) {
		
		foreach ($fields as $field) {

			$field = new Field($field);

			$this->fields[$field->name()] = $field;
		}
	}


	// get field by name
	public function get_field($name) {

		if ($this->has_field($name)) {
			return $this->fields[$name];
		}

		return false;
	}


	// check if field exists
	public function has_field($name) {

		return isset($this->fields[$name]);
	}


	// set/get type name
	public function name($name = false) {

		if (!$name) {
			return $this->name;
		}

		else {
			$this->name = $name;
		}
	}
}