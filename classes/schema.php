<?php

/*
 * GraphQL schema parser
 * @Author: Thomas Winkler
 * @Copyright: 2019
 */

namespace data;

class Schema {

	private $schema;
	private $default_types;
	private $schema_types;

	// create schema object
	// set base path
	// optional: set type = schema (default), query, mutation, data
	public function __construct($path, $type = false) {

		$this->schema_types = ["schema", "query", "mutation", "data"];

		// if no type, use first in types list as default
		if (!$type) {
			$type = $this->schema_types[0];
		}
		

		// load from path
		if (file_exists($path)) {
			$schema = file_get_contents($path);
		}

		// load from file
		else {
			$schema = $path;
		}

		$sections = $this->parse_schema($schema, $type);
	}


	// ************************************************
	public function query($query) {
// debug($query);
	}


	// ************************************************
	// parse schema
	private function parse_schema ($schema, $type) {

		$this->schema = $this->parse_sections($schema);
		$this->schema = $this->parse_by_type($type);
		
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

					case "type":

						$lines = $this->split_lines($part["type"]);

						// get last type entry
						$last = end($lines);

						if (count($lines)) {
							$ret_array[$idx] = $lines;

// 							debug(new Scalar($lines));
						}
						break;

					case "child":

						// get index of last type entry
						$sub_idx = count($ret_array[$idx-1])-1;

						// is not on root level
						if ($sub_idx >= 0) {

							$ret_array[$idx-1][$sub_idx]["children"] = $this->parse_sections($part["child"]);
						}

						$last = "";
						break;
				}
			}
		}

		// return type
		else {
			return $schema;
		}

		return $ret_array;
	}


	// ************************************************
	// find { } sections and return array of type and sections
	private function extract_sections($string) {

		$ret = [];

		preg_match_all('/\{((?:[^{}]++|(?R))*)}/', $string, $matches, PREG_OFFSET_CAPTURE);

		if (count($matches)) {

			$cursor = 0;

			foreach ($matches[1] as $hit) {

				if (($hit[1] - $cursor) > 0) {
					$ret[] = ["type" => substr($string, $cursor, ($hit[1] - $cursor - 1))];
				}

				$ret[] = ["child" => $hit[0]];

				$cursor = $hit[1] + strlen($hit[0]) + 1;
			}

			// add rest
			if ($cursor < (strlen($string))) {
				$ret[] = ["type" => substr($string, $cursor)];
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
// $lines = preg_split('(/\n\ )/', $string);

		foreach ($lines as $line) {

			$line = trim($line);

			if ($line != "") {

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
			"name" => $line,
			"type" => $type,
			"value" => $string,
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

			return $params;
		}
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


	// ************************************************
	// parse schema array by type
	private function parse_by_type($type) {

		if (in_array($type, $this->schema_types)) {

			$class = "data\\type_parser_" . $type;

			$schema = $class::parse($this->schema);

		}

		return $schema;
	}	
}

?>