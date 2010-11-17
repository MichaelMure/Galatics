<?php
/*
 *      TestData.php
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
?>
-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 23 Avril 2010 à 20:43
-- Version du serveur: 5.1.45
-- Version de PHP: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `galia`
--

-- --------------------------------------------------------

--
-- Structure de la table `souhait`
--

CREATE TABLE IF NOT EXISTS `souhait4` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `source` bigint(20) DEFAULT NULL,
  `cible` bigint(20) DEFAULT NULL,
  `poids` bigint(20) DEFAULT '5',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `souhait`
--

INSERT INTO `souhait4` (`id`, `source`, `cible`, `poids`) VALUES
<?php
$id = 0;
$personne = 1;

while($personne < 150) {
    $tailleGroupe = rand(2,15);

    for($y = 0; $y < $tailleGroupe * 2; $y++) {
		do {
			$p1 = rand($personne+1,$personne+$tailleGroupe);
			$p2 = rand($personne+1,$personne+$tailleGroupe);
		} while ($p1 == $p2);
		
        echo "($id, $p1, $p2, 10),\n";
        $id++;
        echo "($id, $p2, $p1, 10),\n";
        $id++;
    }

    /*for($y = 0; $y < $tailleGroupe / 2; $y++) {
        echo "($id, ".rand($personne,$personne+$tailleGroupe).', '.rand(1,150).", 10),\n";
        $id++;
    }*/

    $personne += $tailleGroupe;
}

/*for($y = 0; $y < 15 / 2; $y++) {
    echo "($id, ".rand(1,150).', '.rand(1,150).", 10),\n";
    $id++;
}*/

echo "($id, ".rand(1,150).', '.rand(1,150).", 10);\n";
?>
