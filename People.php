<?php
/*
 *      People.php
 *      
 *      Copyright 2010 Michael Muré <batolettre@gmail.com>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
 
require_once("Wish.php");


class People {
	
	private $id;
	private $name;
	private $wishes;
	private $weight;
	
	public function __construct($id,$name,$weight) {
		$this->id = $id;
		$this->name = $name;
		$this->weight = $weight;
		$this->wishes = array();
	}
	
	public function __destruct() {
		foreach($this->wishes as $wish) {
			unset($wish);
		}
	}
	
	public function __toString() {
		return "People: ".$this->name."<br />\n";
	}
	
	public function echoWishes() {
		foreach($this->wishes as $wish)
			echo $wish;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getWishes() {
		return $this->wishes;
	}

	public function getWeight() {
		return $this->weight;
	}
	
	public function getWishCount() {
		return count($this->wishes);
	}
	
	public function addWish(Wish $wish) {
		$this->wishes[] = $wish;
	}
}

?>
