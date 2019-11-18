<?php

namespace data;

class Query {

	private $query;


	public function __construct($query) {

		$this->query = $this->parse($query);
	}


	private function parse($query) {

// debug($query);
		// foreach ($query[0] as $entry) {

		// 	debug($entry);
		// }

// 		if ($query[0]["op"] == "query") {

// // debug($query[0]["children"]);
// 		}

// 		return $query;
	}
}

?>