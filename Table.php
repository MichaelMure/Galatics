<?php
/*
 *      Table.php
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

class Table {
	
	private $genetic;
	private $peoples;
	private $name;
	
	public function __construct(&$genetic, $name) {
		$this->genetic = $genetic;
		$this->peoples = array();
		$this->name = $name;
	}
	
	public function __toString() {
		$string = 'Table '.$this->name;
		$string .= '<table><tr>';
		foreach($this->peoples as $people)
			$string .= '<td>'.$people->getName()."</td>\n";
		$string .= '</tr></table>';
		return $string;
	}
	
	public function addPeople(People &$people) {
		$this->peoples[$people->getId()] = $people;
	}
	
	public function removePeople(People $people) {
		unset($this->peoples[$people->getId()]);
	}
	
	public function getNbWishRespected() {
		$total = 0;
		foreach($this->peoples as $people) {
			$wishes = $people->getWishes();
			foreach($wishes as $wish) {
				if(isset($this->peoples[$wish->getTarget()->getName()]))
					$total++;
			}
		}
		return $total;
	}

	public function getPonderedNbWishRespected() {
		$total = 0;
		foreach($this->peoples as $people) {
			$wishes = $people->getWishes();
			$nbWishRespected = 0;
			foreach($wishes as $wish) {
				if(isset($this->peoples[$wish->getTarget()->getName()]))
					$nbWishRespected++;
			}
			$total += $people->getWeight() * $nbWishRespected;
		}
		return $total;
	}
	
	public function getTotalWishes() {
		$total = 0;
		foreach($this->peoples as $people)
			$total += $people->getWishCount();
		return $total;
	}
	
	public function removeRndPeople() {
		if(count($this->peoples) ==  0)
			die("tentative de retrait d'une personne à une table vide !");
		
		$alea = rand(0,count($this->peoples)-1);
		$keys = array_keys($this->peoples);
		$key = $keys[$alea];
		$people = $this->peoples[$key];
		unset($this->peoples[$key]);
		return $people;
	}

	public function getPeoples() {
		return $this->peoples;
	}

	public function getName() {
		return $this->name;
	}
	//Lien people --> table ?
	/*public function getNeighbourTable() {
		$nt = array();
		foreach($this->peoples as $people) {
			foreach($people->getWishes() as $wish) {
				if(!isset($this->peoples[$wish->getTarget()->getId()]))
					$nt[] = 
		}
		return $nt;
	}*/
}

?>
