<?php

require_once 'init.php';

$livre = InputOutput::creerLivre($bibliotheque);
$bibliotheque->ajouterLivre($livre);