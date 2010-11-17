<?php
/*
 *      Wish.php
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
 
require_once("People.php");

class Wish {
	
	private $target;
	private $weight;
	
	public function __construct(People &$target,$weight) {
		$this->target = $target;
		$this->weight = $weight;
	}
	
	public function __toString() {
		return "Wish: target: ".$this->target->getName()."   weight: ".$this->weight."<br />\n";
	}
	
	public function getTarget() {
		return $this->target;
	}
	
	public function getWeight() {
		return $this->weight;
	}
}

?>
