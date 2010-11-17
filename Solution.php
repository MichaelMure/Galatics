<?php
/*
 *      Solution.php
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
 
require_once("Table.php");

class Solution {
	
	private $tables;
	private $genetic;
	private $note;
	private $noteValide;
	
	public function __construct(&$genetic) {
		$this->genetic = $genetic;
		$this->tables = array();
		$this->note = 0;
		$this->noteValide = false;
		
		for($x = 0; $x < $this->genetic->nbTable; $x++) {
			$this->tables[] = new Table($this->genetic, $x);
		}
	}
	
	public function __toString() {
		$string = 'Solution: note: '.number_format($this->note,5)."<br />\n";
		foreach($this->tables as $table) {
			$string .= $table;
		}
		return $string."<br />\n";
	}
	
	public function __clone() {
		$tables = array();
		foreach($this->tables as $table) {
			$tables[] = clone($table);
		}
		$this->tables = $tables;
	}
	
	public function getTable($index) {
		return $this->tables[$index];
	}		

	public function getNote() {
		if(!$this->noteValide)
			$this->computeNote();
		return $this->note;
	}
	
	public function generate() {
		$count = $this->genetic->getPeopleCount();
		$nbPlace = $this->genetic->nbPlace;
		$nbTable = $this->genetic->nbTable;
		
		if($nbTable * $nbPlace < $count)
			die("Pas assez de place !");
		
		$peoples = $this->genetic->getPeoples();
		shuffle($peoples);
		$x = 0;
		foreach($peoples as $people) {
			$this->tables[$x%$nbTable]->addPeople($people);
			$x++;
		}
		
		while($x < $nbTable * $nbPlace) {
			$this->tables[$x%$nbTable]->addPeople(new People($x + 10000,"vide",1));
			$x++;
		}
		
		$this->noteValide = false;
	}
	
	private function computeNote() {
		$a = 0;
		$b = 1;
		
		foreach($this->tables as $table) {
			$ponderedWishRespected = $table->getPonderedNbWishRespected();
			$wishCount = $table->getTotalWishes();

			$a += $ponderedWishRespected;
			if($wishCount <= 1)
				$wishCount = 1;

			$b += $ponderedWishRespected / $wishCount;
		}

		$this->note = (5 * $a + $b) * (5 * $a + $b) / 1000;
		$this->noteValide = true;
	}

	//Comptage simple des relations respectée
	/*private function computeNote() {
		$note = 0;

		foreach($this->tables as $table) {
			$note += $table->getNbWishRespected();
		}

		$this->note = $note;
		$this->noteValide = true;
	}*/

	public function mutation() {
		$nbTable = $this->genetic->nbTable;
		$nbPlace = $this->genetic->nbPlace;
		$nbMutationMax = $this->genetic->mutationRatioMax * ($nbTable * $nbPlace) / 100;
		$nbMutation = rand(0,$nbMutationMax);
		
		for($x = 0; $x < $nbMutation; $x++) {
			$table1 = rand(0,$nbTable-1);
			$table2 = rand(0,$nbTable-1);
			$temp1 = $this->tables[$table1]->removeRndPeople();
			$temp2 = $this->tables[$table2]->removeRndPeople();
			$this->tables[$table1]->addPeople($temp2);
			$this->tables[$table2]->addPeople($temp1);
		}
		
		$this->noteValide = false;
	}

	public function create_graphviz() {
		$graph = "";

		$graph .= "digraph G {\n";
		foreach($this->genetic->getPeoples() as $people) {
			foreach($people->getWishes() as $wish) {
				$graph .=  '  '.$people->getName().' -> '.$wish->getTarget()->getName();
				$graph .= '  [color= "#'.dechex(mt_rand(0, 0xffffff)).'"]'."\n";
			}
		}

		foreach($this->tables as $table) {
			$graph .= 'subgraph cluster_'.$table->getName()." {\n";
			$graph .= "\tnode [style=filled];\n";
			$graph .= "\t".'label = "Table '.$table->getName().'";'."\n";
			$graph .= "\tcolor=blue;\n";

			foreach($table->getPeoples() as $people) {
				$graph .= "\t".$people->getName().' [style=filled, fillcolor= "#'.dechex(255-$people->getWeight()*2.56).dechex($people->getWeight()*2.56).'00"];'."\n";
			}

			$graph .= "\n}\n\n";
		}

		$graph .= "}";
		file_put_contents("solution.dot", $graph);
		exec("dot -T png -o solution.png solution.dot");
	}
}

?>
