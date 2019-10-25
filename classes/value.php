<?php

namespace data;

class Value {

	private $unique;
	private $name;
	private $values;
	
	// create value instance
	public function __construct($name) {
		$this->name = $name;
		$this->values = [];
	}
	
	// add single value/ array of values
	public function add($value) {
		
		if (is_array($value)) {
			array_merge($this->values, $value);
		}
		else {
			$this->values [] = $value;
		}
	}
	
	// get value by key or values array
	public function get ($index = false) {
		
		if ($index) {
			if  (isset($this->values [$index])) {
				return $this->values [$index];
			}
			else {
				return false;
			}
		}
		
		return $this->values;
	}
	
	// find value
	// return index or false
	// left, right truncation with asterisk
	public function find ($value) {
		
		$l = $r = false;
		
		if (substr($value, 0, 1) == "*") {
			$l = true;
			$value = substr($value, 1);
		}
		
		if (substr($value, strlen($value) - 1) == "*") {
			$r = true;
			$value = substr($value, 0, strlen($value) - 1);
		}
		
		//ToDo check for index
		
		foreach ($this->values as $idx => $val) {
			
			$ret = false;
			$pos = strpos($val, $value);
			
			// contains value
			if ($pos !== false) {
				
				$end = $pos + strlen($value);
				
				if ((($pos == 0 && !$l) || // not left truncated and at start
					($pos >= 0 && $l)) && // left truncated
					(($end == strlen($val) && !$r) || // not right truncated and at end
					($end <= stelen($val) && $r))) { // right truncated
				
					return $idx;
				}
			}
			
			return false;
	}
	
	// get values count
	public function count () {
		return count ($this->values);
	}
}