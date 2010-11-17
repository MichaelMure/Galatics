<?php
/*
 *      Population.php
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
 
require_once("Solution.php");

class Population {
	
	private $solutions;
	private $genetic;
	
	public function __construct(&$genetic) {
		$this->genetic = $genetic;
		$this->solutions = array();
	}
	
	public function __toString() {
		$string = "";
		foreach($this->solutions as $solution) {
			$string .= "<p>\n";
			$string .= $solution."</p>";
		}
		return $string;
	}
	
	public function getSolution($index) {
		return $this->solutions[$index];
	}
	
	public function generate() {
		for($x = 0; $x < $this->genetic->nbSolution ; $x++) {
			$this->solutions[$x] = new Solution($this->genetic);
			$this->solutions[$x]->generate();
		}
	}
	
	public function mutation() {
		foreach($this->solutions as $solution)
			$solution->mutation();
	}
	
	public function crossover() {
		
	}

	private function compareSolution($a, $b) {
		$noteA = $a->getNote();
		$noteB = $b->getNote();
		
		if ($noteA == $noteB) {
			return 0;
		}
		return ($noteA > $noteB) ? -1 : 1;
	}

	public function selectAndRegenerate() {
		$nbSelect = $this->genetic->selectRatio * $this->genetic->nbSolution / 100;
		$nbAlea = $this->genetic->aleaRatio * $this->genetic->nbSolution / 100;
		
		usort($this->solutions, array('Population','compareSolution'));
		$this->solutions = array_slice($this->solutions,0,$nbSelect);

		for($x = $nbSelect ; $x < ($this->genetic->nbSolution - $nbAlea) ; $x++) {
			$this->solutions[] = clone($this->solutions[$x%$nbSelect]);
		}

		for( ; $x < $this->genetic->nbSolution ; $x++) {
			$sol = new Solution($this->genetic);
			$sol->generate();
			$this->solutions[] = $sol;
		}
	}

	public function getBestSolution() {
		return $this->solutions[0];
	}

	public function getStats() {
		$count = count($this->solutions);
		if($count == 0) return array(0,0,0);
		
		$min = $this->solutions[0]->getNote();
		$max = $this->solutions[0]->getNote();
		$meanNote = 0;
		
		foreach($this->solutions as $solution) {
			$note = $solution->getNote();
			$meanNote += $note;
			if($note < $min) $min = $note;
			if($note > $max) $max = $note;
		}
		return array($min,$meanNote / $count,$max);
	}

	public function getSolutionCount() {
		return count($this->solutions);
	}
}

?>
