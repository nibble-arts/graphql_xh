<?php

namespace data;


class Link {

	private $unique;
	private $occ;
	private $target;


	// create target
	public function __construct($name, $target) {

		$this->target = $target;
	}


	// add link
	public function add($target, $links) {

	// make array of single ink
		if (!is_array($links)) {
			$links = [$links];
		}

		// add values if occurences are correct
		if (!$this->occ || (($this->count() + count($links)) <= $this->occ)) {


			foreach ($links as $key => $link) {

				// link is data of related group
				// add new group entry
				if (is_array($link)) {

					Data::add_to_group($target, $link);
					$this->links[$target][] = Data::last_id($target);
				}

				// add link
				else {
					$this->links[] = $link;
				}
			}

			$ret = $this->count();
		}

		else {
			$ret = false;
		}

		return $ret;

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