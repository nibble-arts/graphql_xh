<?php

namespace data;

class Type {
	
	private $uuid;
	private $name;
	private $values;
	private $links;
	

	// create type from ini file
	public function __construct ($name, $structure, $uuid = false) {

		$this->name = $name;

		// set/create type id
		if ($uuid === false) {
			$this->uuid = UUID::create();
		}

		else {
			$this->uuid = $uuid;
		}

		// parse structure
		foreach ($structure as $name => $value) {

			// if (!isset($value["type"])) {
			// 	return false;
			// }

debug($value);

			

			switch($value["type"]) {

				// add link to type
				case "link":

					// target is mandatory
					if (!isset($value["target"])) {
						return false;
					}

					// add new link class
					$this->links[$name] = new Link($name, $value["target"]);
					break;

				// add simple value
				default:

					// add new value class
					$new_val = new Value($name);

					// set type
					$new_val->type($value["type"]);

					// set unique
					if (isset($value["unique"])) {
						$new_val->unique($value["unique"]);
					}

					// set occations
					if (isset($value["occ"])) {
						$new_val->occ($value["occ"]);
					}

					$this->values[$name] = $new_val;
					break;

			}
		}
	}


	// get/set uuis
	public function uuid($uuid = false) {

		if ($uuid !== false) {
			$this->uuid = $uuid;
		}

		else {
			return $this->uuid;
		}
	}


	// get/set name
	public function name($name = false) {

		if ($name !== false) {
			$this->name = $name;
		}

		else {
			return $this->name;
		}
	}


	// add values
	public function add($values) {

		foreach($values as $key => $value) {

			switch ($this->get_type($key)) {

				// key is value
				case "value":
					$this->add_value($key, $value);
					break;

				// key is link
				case "link":
					$this->add_link($key, $value);
					break;
			}

			// $this->add_value($key, $value);
		}
	}


	// add value
	public function add_value($name, $value) {

		if ($this->value_exists($name)) {
			$this->values[$name]->add($value);
		}
	}


	// add value
	public function add_link($name, $value) {

		if ($this->link_exists($name)) {

			$target = $this->get_link($name)->target();
			$this->links[$name]->add($target, $value);
		}
	}


	// get type as array
	public function values() {

		$ret = [];

		foreach ($this->values as $key => $value) {
			$ret[$key] = $value->get();
		}

		return $ret;
	}


	// get type as array
	public function links() {

		$ret = [];

		if ($this->links) {
			
			foreach ($this->links as $key => $value) {
				$ret[$key] = $value->get();
			}
		}

		return $ret;
	}


	// get value class
	public function get_value($name) {

		if ($this->value_exists($name)) {
			return $this->values[$name];
		}
	}


	// get value class
	public function get_link($name) {

		if ($this->link_exists($name)) {
			return $this->links[$name];
		}
	}


	// find value
	public function find($name, $value) {

	}


	// get type: value or link
	public function get_type($name) {

		if ($this->value_exists($name)) {
			return "value";
		}

		if ($this->link_exists($name)) {
			return "link";
		}

		return false;
	}


	// value exists
	public function value_exists($name) {
		return (isset($this->values[$name]));
	}


	// link exists
	public function link_exists($name) {
		return (isset($this->links[$name]));
	}

}