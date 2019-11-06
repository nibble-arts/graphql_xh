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

// debug($sections);
// new types\ID();

		// $this->default_types = ["int", "float", "string", "bloolean", "id"];
		// $this->parse_types($schema);

	}


	private function parse_schema($schema) {

echo "<hr>";
debug($schema);
		$ret_array = [];

		$sections = $this->extract_section($schema);

debug($sections);

		// sections found
		if ($sections) {

			foreach ($sections as $part) {
debug($part);

				// $ret_array[] = $this->parse_child($part);
			}
		}

		return $ret_array;
	}


	// parse child section recursively
	private function parse_child($child) {

		if (count($child["child"])) {

			$temp["before"] = $child["before"];
			$temp["child"] = $this->parse_schema($child["child"]);
			$temp["after"] = $child["after"];
		}

		return $temp;
	}


	private function extract_section($string) {

		$ret = [];

		preg_match_all('/\{((?:[^{}]++|(?R))*)}/', $string, $matches, PREG_OFFSET_CAPTURE);

		if (count($matches)) {

			foreach ($matches[1] as $hit) {

				$temp["before"] = substr($string, 0, $hit[0]);
				$temp["child"] = $hit[0];
				$temp["after"] = substr($string, $hit[1] + strlen($hit[0])+1);

				$ret[] = $temp;
			}

			return $ret;
		}

		return false;
	}
}

?>