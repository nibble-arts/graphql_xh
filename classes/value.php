<?php

namespace data;

class Value {

	private $unique;
	private $occ;
	private $name;
	private $values;
	

	// create value instance
	// reset values array
	// set occasions to 0 => no limit
	public function __construct($name) {
		$this->name = $name;

		$this->values = [];
		$this->occ = 0;
	}
	
	// add single value/ array of values
	// returns new count of values
	// false if count after adding would be greater than max occ
	public function add($value) {
		
		$ret = false;

		// add multiple values by array
		if (is_array($value)) {

			if (!$this->occ || (($this->count() + count($value)) <= $this->occ)) {
				$this->values = array_merge($this->values, $value);
				$ret = $this->count();
			}

			else {
				$ret = false;
			}
		}

		// add single value
		else {

			if (!$this->occ || ($this->count() < $this->occ)) {
				$this->values [] = $value;
				$ret = $this->count();
			}

			else {
				$ret = false;
			}
		}

		// is unique value
		// make values unique
		if ($this->unique) {
			$this->values = array_values(array_unique($this->values));
		}

		return $ret;
	}
	
	// get value by key or values array
	public function get ($index = false) {
		
		if ($index !== false) {
			if  (isset($this->values [$index])) {
				return $this->values [$index];
			}
			else {
				return false;
			}
		}
		
		return $this->values;
	}
	
	// set value unique
	public function unique ($status) {
		$this->unique = $status;
	}

	// set max occasions
	public function occ ($count) {
		$this->occ = $count;
	}

	// find value
	// return index or false
	// left, right truncation with asterisk
	// case = true > search case sensitive
	// returns array with index and value
	public function find ($value, $case = false) {
		
		$l = $r = false;
		
		if (substr($value, 0, 1) == "*") {
			$l = true;
			$value = substr($value, 1);
		}
		
		if (substr($value, strlen($value) - 1) == "*") {
			$r = true;
			$value = substr($value, 0, strlen($value) - 1);
		}
		


// ToDo check for index


		// reset found results
		$result = false;

		foreach ($this->values as $idx => $val) {
			
			// search case sensitive
			if ($case !== false) {
				$pos = strpos($val, $value);
			}

			// search case insensitive
			else {
				$pos = stripos($val, $value);
			}
			
			// contains value
			if ($pos !== false) {
				
				$end = $pos + strlen($value);

				// check with truncation
				if ((($pos == 0 && !$l) || // not left truncated and at start
					($pos >= 0 && $l)) && // left truncated
					(($end == strlen($val) && !$r) || // not right truncated and at end
					($end <= strlen($val) && $r))) { // right truncated
				
					// add result
					$result[$idx] = $val;
				}
			}
		}
			
		return $result;
	}
	
	// get values count
	public function count () {
		return count ($this->values);
	}
}