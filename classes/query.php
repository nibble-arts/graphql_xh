<?php

namespace data;

class Query {

	private $query;


	public function __construct($query) {

		$this->query = $this->parse($query);
	}


	private function parse($query) {
debug($query);
		return $query;
	}
}

?>