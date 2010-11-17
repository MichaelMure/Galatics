<?php
/*
 *      genetic.php
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
require_once("Wish.php");
require_once("Table.php");
require_once("Solution.php");
require_once("Population.php");


class Genetic {
	public $nbTable = 18;
	public $nbPlace = 10;
	
	public $nbSolution = 250;
	public $mutationRatioMax = 1.0; // %
	public $selectRatio = 10; // %
	public $aleaRatio = 5; //%
	
	private $peoples;
	private $population;
	
	private $iteration;
	private $iterations;
	private $stats; // {min, mean, max}
	private $absolutBestSolution;
	private $absolutBestNote;
	private $lastMeanNote;

	private $displatScale;
	
	public function __construct() {
		$this->loadPersonnes();
		$this->population = new Population($this);
		$this->population->generate();
		
		$this->iteration = 0;
		$this->iterations = array(0);
		$this->stats = array(array(0),array(0),array(0));
		$this->absolutBestSolution = new Solution($this);
		$this->absolutBestNote = 0;
		$this->lastMeanNote = 0;
		$this->displatScale = 1;
	}

	public function loadPersonnes() {
		//TODO with final data structure
		require_once("mysql.php");
		
		for($x = 1; $x<=170 ; $x++) {
			$this->peoples[$x] = new People($x,$x,10);
		}
		
		connexion();
		$result = requete("SELECT * FROM souhait4;");
		
		while($row = mysql_fetch_array($result)) {
			$people = $row["source"];
			$target = $row["cible"];
			$weight = $row["poids"];
			$wish = new Wish($this->peoples[$target],$weight);
			$this->peoples[$people]->addWish($wish);
		}		
		deconnexion();
	}
	
	public function addPeople(People &$people) {
		$this->peoples[] = $people;
	}
	
	public function getPeopleCount() {
		return count($this->peoples);
	}
	
	public function getPeople($index) {
		return $this->peoples[$index];
	}
	
	public function getPeoples() {
		return $this->peoples;
	}
	
	//Genetic algorithm core
	public function compute($duration) {
		$start = time();
		while(time() < $start + $duration) {
			$this->iteration++;

			$this->population->selectAndRegenerate();
			$bestSolution = $this->population->getBestSolution();

			if($bestSolution->getNote() > $this->absolutBestNote) {
				$this->absolutBestSolution = clone($bestSolution);
				$this->absolutBestNote = $bestSolution->getNote();
			}
			$stats = $this->population->getStats();
			if(!($this->iteration % $this->displatScale)) {
				$this->stats[0][] = $stats[0];
				$this->stats[1][] = $stats[1];
				$this->stats[2][] = $stats[2];
				$this->iterations[] = $this->iteration;
			}
			$this->population->mutation();
		}
	}
	
	public function display() {
		if(count($this->iterations) > 100) {
			$this->iterations = $this->reduce($this->iterations);
			$this->stats[0] = $this->reduce($this->stats[0]);
			$this->stats[1] = $this->reduce($this->stats[1]);
			$this->stats[2] = $this->reduce($this->stats[2]);
			$this->displatScale *= 2;
		}
		
		include("../pChart/pData.class");  
		include("../pChart/pChart.class");  
		  
		// Dataset definition
		$DataSet = new pData;
		$DataSet->AddPoint($this->iterations,"iterations");
		$DataSet->AddPoint($this->stats[0],"min");
		$DataSet->AddPoint($this->stats[1],"mean");
		$DataSet->AddPoint($this->stats[2],"max");
		$DataSet->AddAllSeries();
		
		// Initialise the graph
		$graph = new pChart(700,260);  
		$graph->setFontProperties("../pChart/Fonts/tahoma.ttf",10);  
		$graph->setGraphArea(40,30,680,200);  
		$graph->drawGraphAreaGradient(0,0,0,-100,TARGET_BACKGROUND);
		$graph->drawXYScale($DataSet->GetData(),$DataSet->GetDataDescription(),"max","iterations",213,217,221,TRUE,45);
		  
		// Draw the line graph
		$graph->drawXYGraph($DataSet->GetData(),$DataSet->GetDataDescription(),"min","iterations",1);
		$graph->drawXYGraph($DataSet->GetData(),$DataSet->GetDataDescription(),"mean","iterations",2);
		$graph->drawXYGraph($DataSet->GetData(),$DataSet->GetDataDescription(),"max","iterations",3);
		  
		// Finish the graph
		$graph->setFontProperties("../pChart/Fonts/tahoma.ttf",10);  
		$graph->drawTitle(60,22,"Evolution de la note moyenne",250,250,250,585);  
		$graph->Render("graph.png");
		
		echo "<p><img src='graph.png' alt='Evolution de la note moyenne' /></p>\n";
		
		echo "Nombre d'itération: ".$this->iteration."<br />";
		echo 'Nombre de solution: '.$this->population->getSolutionCount()."<br />\n";
		$delta = end($this->stats[1]) - $this->lastMeanNote;
		$delta = number_format($delta,5);
		if($delta > 0)
			$delta = '+'.$delta;
		$this->lastMeanNote = end($this->stats[1]);
		echo 'Note moyenne: '.number_format(end($this->stats[1]),5).' ('.$delta.')';
		echo '<h2>Meilleure solution</h2>';
		echo $this->absolutBestSolution;
	}

	private function reduce($array) {
		$temp = array();
		$x = 0;
		foreach($array as $val) {
			if(!($x%2)) $temp[] = $val;
			$x++;
		}
		return $temp;
	}

	public function graphviz() {
		$this->absolutBestSolution->create_graphviz();
		header('Location: solution.png');
	}
}

?>
