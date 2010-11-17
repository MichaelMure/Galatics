<?php
/*
 *      index.php
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

if(isset($_POST['graphviz'])) {
	include("genetic.php");
	$g = unserialize(file_get_contents("./storage.genetics"));
	$g->graphviz();
	die();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Gala'tics</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
	<style type="text/css">
		td {
			border: solid 1px;
		}
	</style>
	
	<script type="text/javascript">
		function refresh() {
			window.location = "index.php";
		}
	</script>
</head>

<?php 
if(!isset($_POST["stop"]) && !isset($_POST["new"])) 
	echo '<body onLoad="refresh()">';
else
	echo '<body>';
?>

	<h1>Gala'tics</h1>
	<form action="index.php" method="post">
		<input type="submit" name="new" value="Redémarrer le calcul" />
		<?php if(isset($_POST["stop"]) || isset($_POST["new"])) {
			echo '<input type="submit" name="start" value="Lancer le calcul" />'."\n";
			echo '<input type="submit" name="stop" value="Une itération" />'."\n";
			echo '<input type="submit" name="graphviz" value="Visualiser le resultat" />'."\n";
		}
		else {
			echo '<input type="submit" name="stop" value="Arretter le calcul" />'."\n";
		} ?>
	</form>
	<?php
		include("genetic.php");
		
		if(isset($_POST["new"]) or !file_exists("./storage.genetics"))
		{
			$g = new Genetic();
			$g->display();
		}
		else
		{
			$g = unserialize(file_get_contents("./storage.genetics"));
			$g->compute(15);
			$g->display();
		}
		
		file_put_contents("storage.genetics", serialize($g));
	?>	
	
</body>
</html>
