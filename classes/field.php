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
	public function find($string) {

		$trunc = $this->get_trunc($string);

		foreach ($this->values as $idx => $value) {

			$start = strpos($value, $string);

			// hit
			if ($start !== false) {

				$end = $start + strlen($string) - 1;

echo "<hr>";
debug($string ." in ".$value);
debug($trunc);
debug("start: ".$start." end: ".$end.", string_len: ". strlen($string).", val_length: ".strlen($value));

debug(($trunc[0] || (!$trunc[0] && $start == 0)) || (($trunc[1] || (!$trunc[1] && $end == strlen($value)))));

				if (($trunc[0] || (!$trunc[0] && $start == 0)) || (($trunc[1] || (!$trunc[1] && $end == (strlen($value) - 1))))) {
					debug("found ".$string." in ".$idx);
				}
			}


		}
		// debug(array_search($string, $this->values));


		// debug($this->values);
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

		preg_match('/([*]?)([^*])([*]?)/', $string, $matches);

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