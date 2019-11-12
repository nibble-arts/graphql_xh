<?php

namespace data;


class Field {

	private $type;
	private $name;
	private $values;
	private $params;
	private $func;


	// construct field object
	public function __construct($data) {

		$this->values = [];
		// $this->type = $type;
		$this->type = $data["type"];

		$this->func = $this->parse_type($this->type);

		$this->name = $data["name"];
		$this->params = $data["params"];

	}


	// set/get name of field
	public function name($name = false) {

		if (!$name) {
			return $this->name;
		}

		else {
			$this->name = $name;
		}
	}


	// query field
	// if case == true > find case sensitive
	public function find($value, $case = false) {

		$trunc = $this->get_trunc($value);

		// reset found results
		$result = [];

		foreach ($this->values as $idx => $val) {

			// search case sensitive
			if ($case !== false) {
				$pos = strpos($val, $value);
			}

			// search case insensitive
			else {
				$pos = stripos($val, $value);
			}


			// contains value
			if ($pos !== false) {

				$end = $pos + strlen($value);

				// check with truncation
				if ((($pos == 0 && !$trunc[0]) || // not left truncated and at start
					($pos >= 0 && $trunc[0])) && // left truncated
					(($end == strlen($val) && !$trunc[1]) || // not right truncated and at end
					($end <= strlen($val) && $trunc[1]))) { // right truncated
				
					// add result
					$result[$idx] = $val;
				}
			}

			// value is only wildcard > get all
			elseif ($value == "*") {
				$result[$idx] = $val;
			}
		}

		return $result;
	}


	// add value or array of values if array
	public function add($string) {

		if (!is_array($string)) {
			$string = [$string];
		}

		// if array of only one element > add value
		if ($this->func["array"] || ((count($this->values) + count($string)) <= 1)) {
			$this->values = array_merge($this->values, $string);
		}

		// throw warning
		else {
			Message::warning("multiple values in single value field '" . $this->name() . "'");
		}
	}


	// remove value by id
	public function remove($id) {

		if (isset($this->values[$id])) {
			unset($this->values[$id]);

			$this->values = array_values($this->values);
		}
	}


	// parse type definition
	// extract type name
	// return array of functions
	private function parse_type(&$type) {

		$array = false;
		$type_nn = false;
		$array_nn = false;

		preg_match('/([\[]?)([^]!]+)([!]?)([\]]?)([!]?)/', trim($type), $matches);

		if (count($matches[1])) {

			$type = $matches[2];

			if ($matches[1] && $matches[4]) {
				$array = true;
			}


			// type not null
			if ($matches[3]) {
				$type_nn = true;
			}

			// array not null
			if ($matches[5] && $array) {
				$array_nn = true;
			}
		}

		return [
			"array" => $array,
			"type_nn" => $type_nn,
			"array_nn" => $array_nn
		];
	}


	// get truncation
	private function get_trunc(&$string) {

		$left = false;
		$right = false;

		preg_match('/([*]?)([^*]+)([*]?)/', $string, $matches);

		if (count($matches) > 3) {

			$string = $matches[2];

			if ($matches[1]) {
				$left = true;
			}

			if ($matches[3]) {
				$right = true;
			}

		}

		return [$left, $right];
	}
}

?>