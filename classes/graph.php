<?php

namespace data;

class Graph {

	private $types;

	public function __construct($schema) {

		$this->parse_types($schema);

debug($this->types);
	}


	private function parse_types($types) {

		foreach ($types as $name => $type) {
// debug($type);
			$this->types[$name] = new Type($name, $this->parse_type($type));
		}
	}


	private function parse_type($type) {

		foreach ($type as $name => $ref) {

// debug($name);
		}

		return $type;
	}

}


/*			$type_class = 'type_' . $name;

			if (class_exists($type_class)) {

				new $type_class();
			}

			else {
				die ("class ".$type_class." does not exist");
			}
*/