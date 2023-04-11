<?php

class InputOutput {
    /**
     * Affiche le contenu SANS sauter une ligne
     * @param string $prompt Ce qui est à afficher
     */
    public static function print(string $prompt = '') {
        echo $prompt;
    }

    /**
     * Affiche le contenu PUIS saute une ligne
     * @param string $prompt Ce qui est à afficher
     */
    public static function printLn(string $prompt = '') {
        static::print($prompt . PHP_EOL);
    }

    /**
     * Dump & die
     * Affiche toutes les variables fournies (façon débuguage)
     * puis met fin à l'exécution du script
     * @param mixed $var Ce qui doit être débugué
     */
    public static function dd(mixed ...$var) {
        foreach ($var as $variable) {
            var_dump($variable);
        }

        die;
    }

    /**
     * Affiche un menu pour rechercher un livre, 
     * jusqu'à ce qu'un seul livre soit sélectionné
     * @param Bibliotheque $bibliotheque La bibliothèque de laquelle le livre est recherché
     * @return Livre Le livre trouvé, résultat de la recherche
     */
    public static function rechercherLivre(Bibliotheque $bibliotheque): Livre {
        InputOutput::printLn('Recherchons le livre concerné...');

        $recherche = ''; // On part avec une recherche vide

        do {
            if ($recherche !== '') {

                if (!empty($livres[$recherche])) {
                    // Si la personne a saisi, dans la recherche, le numéro du livre
                    return $livres[$recherche]; // Le boulot est terminé, on peut renvoyer le livre
                }

                // Si une recherche a été faite
                // On récupère les résultats
                $livres = $bibliotheque->chercherLivres($recherche);

                if (count($livres) === 1) {
                    // On a trouvé un seul livre 
                    // ==> C'est celui qu'on veut emprunter
                    return $livres[0]; // Le boulot est terminé, on peut renvoyer le livre
                } else {
                    // Sinon on affiche les résultats de la recherche
                    static::printLn();
                    static::printLn(count($livres) . ' livre(s) trouvé(s) avec votre saisie.');
                }
            } else {
                // Sinon on prend tous les livres
                $livres = $bibliotheque->livres;
            }

            if (count($livres) <= 10 && count($livres) > 0) {
                // S'il y a peu de livres, on peut proposer un menu
                static::printLn('Le livre fait-il partie des suivants ?');
                static::printLn('Si oui, saisissez le numéro correspondant.');
                static::printLn();

                foreach ($livres as $index => $livre) {
                    static::printLn('[' . $index . '] ' . $livre->getAffichage());
                }
            }

            static::printLn();

            $recherche = readline('Votre saisie : ');
        } while (true); // On fait une boucle infinie, on ne peut s'arrêter que si un livre est trouvé
    }
}
