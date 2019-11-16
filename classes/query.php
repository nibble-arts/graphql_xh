<?php

namespace data;

class Query {

	private $query;


	public function __construct($query) {

		$this->query = $this->parse($query);
	}


	private function parse($query) {

		if ($query[0]["op"] == "query") {

debug($query[0]["children"]);
		}

		return $query;
	}
}

?>