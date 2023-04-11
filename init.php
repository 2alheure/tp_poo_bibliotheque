<?php

/**
 * Est capable, pour chaque FQCN fourni
 * de faire le require_once du bon fichier
 * @param string $fqcn Le FQCn dont on doit faire le require
 */
function fqcn_vers_fichier(string $fqcn) {
    require_once __DIR__ . '/' . str_replace('\\', '/', $fqcn) . '.php';
}

spl_autoload_register('fqcn_vers_fichier');

// On crée une petite bibliothèque pour tester notre code 
$bibliotheque = new Bibliotheque;
$bibliotheque->ajouterLivre(new Livre('Harry Potter et la Coupe de Feu', '978000000', auteur: 'J.K. Rolling', datePublication: '30/11/2005'));
$bibliotheque->ajouterLivre(new Livre('Le Seigneur des Anneaux', '978000000', auteur: 'J.R.R. Tolkien', datePublication: '29/07/1954'));
$bibliotheque->ajouterLivre(new Livre('Les Misérables', '978000000', auteur: 'Victor Hugo', datePublication: '03/04/1862'));