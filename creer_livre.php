<?php

require_once 'init.php';

$livre = InputOutput::creerLivre();
$bibliotheque->ajouterLivre($livre);