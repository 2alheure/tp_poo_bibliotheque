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