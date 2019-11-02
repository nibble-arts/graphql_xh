<?php

namespace data;


class Link {

	private $unique;
	private $occ;
	private $target;
	private $links;


	// create target
	public function __construct($name, $target) {

		$this->target = $target;
	}


	// add link
	public function add($target, $links) {

	// make array of single link
		if (!is_array($links)) {
			$links = [$links];
		}

		// add values if occurences are correct
		if (!$this->occ || (($this->count() + count($links)) <= $this->occ)) {

			foreach ($links as $link) {

				// link is data of related group
				// add new group entry
				if (is_array($link) && $target) {

					Data::add_to_group($target, $link);
					$this->links[$target][] = Data::last_id($target);
				}

				// add link
				else {
					$this->links[$target][] = $link;
				}
			}

			$ret = $this->count();
		}

		else {
			$ret = false;
		}

		return $ret;

	}


	// get link by key or links array
	public function get ($index = false) {

		if ($index !== false) {
			if  (isset($this->links [$index])) {
				return $this->links [$index];
			}
			else {
				return false;
			}
		}
		
		return $this->links;
	}
	



	// get/set target
	public function target($target = false) {

		if ($target !== false) {
			$this->target = $target;
		}

		else {
			return $this->target;
		}
	}


	// set value unique
	public function unique ($status) {
		$this->unique = $status;
	}


	// set max occasions
	public function occ ($count) {
		$this->occ = $count;
	}


	// get values count
	public function count () {
		return count ($this->links);
	}
}