<?php

namespace data;

class Graph {

	private $types;
	private $default_types;

	public function __construct($schema) {

		$this->default_types = ["string", "int", "float", "bool", "date"];
		$this->parse_types($schema);

	}


	private function parse_types($types) {

		foreach ($types as $name => $type) {
			$this->types[$name] = new Type($name, $this->parse_type($type));
		}
	}


	private function parse_type($type) {

		$ret = [
			"values" => [],
			"types" => []
		];

		foreach ($type as $name => $type) {

			// check for default types
			if (in_array(strtolower($type), $this->default_types)) {
				$ret["values"][$name] = $type;
			}

			else {
				$ret["types"][$name] = $type;
			}

		}

		return $ret;
	}

}

?>