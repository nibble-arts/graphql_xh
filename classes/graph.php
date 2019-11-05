<?php

namespace data;

class Graph {

	private $types;
	private $default_types;

	public function __construct($path) {

		if (file_exists($path)) {
			$schema = file_get_contents($path);
		}


		$sections = $this->parse_schema($schema);

debug($sections);
// new types\ID();

		// $this->default_types = ["int", "float", "string", "bloolean", "id"];
		// $this->parse_types($schema);

	}


	private function parse_schema($schema, $ret_array = false) {

echo "<hr>";
// debug($schema);
		$sections = $this->extract_section($schema);

		if ($sections) {

			foreach ($sections as $part) {

debug($part);
debug($schema);
debug("pos: ".strpos($schema, $part));
debug("length: ".strlen($part));

// debug("recursion");
// // debug($part);
// 				$ret_array = [$this->parse_schema($part)];
// // debug($ret);
			}

// debug("return");
// debug($part);
// debug($ret_array);

		}
// 		else {
// 			$ret_array = [$schema];
// 		}


		return $ret_array;
	}



	private function extract_section($string) {

		preg_match_all('/\{((?:[^{}]++|(?R))*)}/', $string, $matches );

		if (count($matches)) {

			return $matches[1];
		}

		return false;
	}
}

?>