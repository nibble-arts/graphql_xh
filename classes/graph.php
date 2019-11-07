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

// new types\ID();

	}


	// ************************************************
	// parse schema
	private function parse_schema ($schema) {

		$sections = $this->parse_sections($schema);

debug($sections);






	}


	// ************************************************
	// parse schema recursively
	private function parse_sections($schema) {

		$ret_array = [];
		$sections = $this->extract_sections($schema);

		// sections found
		if ($sections) {

			foreach ($sections as $idx => $part) {

				switch (array_keys($part)[0]) {

					case "content":
						$lines = $this->split_lines($part["content"]);

						// get last content entry
						// $last = array_pop($lines);

						if (count($lines)) {
							$ret_array[] = ["content" => $lines];

						}
						break;

					case "child":
					// debug("last field: ".$last["field"]);
						$ret_array[] = ["child" => $this->parse_sections($part["child"])];

						$last = "";
						break;
				}
			}
		}

		// return content
		else {
			return $schema;
		}

		return $ret_array;
	}


	// ************************************************
	// find { } sections and return array of content and sections
	private function extract_sections($string) {

		$ret = [];

		preg_match_all('/\{((?:[^{}]++|(?R))*)}/', $string, $matches, PREG_OFFSET_CAPTURE);

		if (count($matches)) {

			$cursor = 0;

			foreach ($matches[1] as $hit) {

				if (($hit[1] - $cursor) > 0) {
					$ret[] = ["content" => substr($string, $cursor, ($hit[1] - $cursor - 1))];
				}

				$ret[] = ["child" => $hit[0]];

				$cursor = $hit[1] + strlen($hit[0]) + 1;
			}

			// add rest
			if ($cursor < (strlen($string))) {
				$ret[] = ["content" => substr($string, $cursor)];
			}

			return $ret;
		}

		return false;
	}


	// ************************************************
	// split string to trimmed lines
	private function split_lines($string) {

		$ret = [];
		$lines = explode("\n", $string);

		foreach ($lines as $line) {

			$line = trim($line);

			if ($line != "") {

// debug( $this->parse_line($line));

				$ret[] = $this->parse_line($line);
			}
		}

		return $ret;
	}


	// ************************************************
	// parse line
	private function parse_line($line) {

		$param = $this->get_parameters($line);
		[$type, $string] = $this->get_type_string($line);

		return [
			"field" => $line,
			"type" => $type,
			"string" => $string,
			"params" => $param
		];
	}


	// ************************************************
	// get parameters
	private function get_parameters(&$string) {

		preg_match('/\(([^{}]+)\)/', $string, $matches);

		if (count($matches)) {
			$string = str_replace($matches[0], "", $string);

			$params = $this->explode_param($matches[1]);
		}

		return $params;
	}


	// ************************************************
	// explode parameter string
	private function explode_param($string) {

		$ret = [];
		$params = explode(",", $string);

		foreach ($params as $param) {

			$key_val = explode(":", $param);

			if (count($key_val) > 1) {
				$ret[trim($key_val[0])] = trim($key_val[1]);
			}
		}

		return $ret;
	}


	// ************************************************
	// get quoted
	private function get_type_string(&$type_string) {

		$type = "";
		$string = "";

		$field_type = explode(":", $type_string);

		if (count($field_type) > 1) {

			$type_string = trim($field_type[0]);

			if ($this->is_quoted($field_type[1])) {
				$string = trim($field_type[1]);
			}
			else {
				$type = trim($field_type[1]);
			}
		}

		return [$type, $string];
	}


	// ************************************************
	// checks if string is quoted
	// false if no quotes
	// string without quotes if true
	private function is_quoted($string) {

		preg_match('/[\"]([^\"]+)\"/', $string, $match);

		// is quoted
		if ($match) {
			return ($match[1]);
		}

		// no quotes
		return false;
	}	
}

?>