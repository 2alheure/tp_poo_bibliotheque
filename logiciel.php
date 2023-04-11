<?php

/**
 * Ce fichier sera le "router"
 * Il appelle toutes les fonctionnalités
 * 
 * Comme mon code est relativement court, 
 * je vais tout mettre sur ce fichier, séparé dans des fonctions
 */



/**
 * Est capable, pour chaque FQCN fourni
 * de faire le require_once du bon fichier
 * @param string $fqcn Le FQCn dont on doit faire le require
 */
function fqcn_vers_fichier(string $fqcn) {
    require_once __DIR__ . '/classes/' . str_replace('\\', '/', $fqcn) . '.php';
}

spl_autoload_register('fqcn_vers_fichier');

$application = new Application;

try {
    // On met un immense try catch
    // Pour attraper toutes les erreurs de l'application

    do {
        do {
            // On propose toutes les fonctionnalités à l'utilisateur
            InputOutput::printLn('Que souhaitez-vous faire ?');

            $fonctionnalites = $application->getChoix();

            foreach ($fonctionnalites as $choix => $nom) {
                InputOutput::printLn('[' . $choix . '] ' . $nom);
            }
            InputOutput::printLn();
            $choix = readline('Votre choix : ');
            InputOutput::printLn();


            // On répète tant que le choix n'est pas correct
        } while (empty($fonctionnalites[$choix]));

        // Selon ce que l'utilisateur a choisi
        // On fait qqchose de différent
        switch ($choix) {
            case Application::QUITTER:
                $application->quitter();
                break;

            case Application::CREER_LIVRE:
                $application->creerLivre();
                break;

            case Application::EMPRUNTER_LIVRE:
                $application->emprunterLivre();
                break;

            case Application::STATS:
                $application->consulterStats();
                break;

            case Application::CONSULTER_LIVRE:
                $application->consulterLivre();
                break;

            case Application::SUPPRIMER_LIVRE:
                $application->supprimerLivre();
                break;

            case Application::MODIFIER_LIVRE:
                $application->modifierLivre();
                break;

            default:
                throw new Exception('Un mauvais choix a réussi à se glisser ici.');
        }

        InputOutput::printLn();
    } while (true); // On fait une boucle infinie, on quitte cette boucle uniquement quand le logiciel s'arrête

} catch (Exception $e) {
    InputOutput::printLn();
    InputOutput::printLn('Une erreur est survenue.');
    InputOutput::printLn($e->getMessage());
    InputOutput::printLn('L\'application doit s\'arrêter.');
} finally {
    $application->quitter();
}
