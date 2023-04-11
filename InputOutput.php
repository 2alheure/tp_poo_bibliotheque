<?php

class InputOutput {
    /**
     * Affiche le contenu SANS sauter une ligne
     * @param ?string $prompt Ce qui est à afficher
     */
    public static function print(string $prompt = '') {
        echo $prompt;
    }

    /**
     * Affiche le contenu PUIS saute une ligne
     * @param ?string $prompt Ce qui est à afficher
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
     * Demande les informations à l'utilisateur pour créer un livre
     * @return Livre
     */
    public static function creerLivre(): Livre {
        static::printLn('Processus de création de livre enclenché.');

        $titre = readline('Titre : ');
        $sousTitre = readline('Sous-titre (optionnel) : ');
        $auteur = readline('Auteur (optionnel) : ');
        $isbn = readline('ISBN : ');

        do {
            // On répète au moins une fois
            $datePublication = readline('Date de publication (optionnel, format jj/mm/aaaa) : ');

            // et tant que ce qu'on a donné n'est pas vide (puisqu'optionnel)
            // et est invalide
        } while (!empty($datePublication) && date_create_from_format('d/m/Y', $datePublication) === false);

        $resume = readline('Résumé : ');

        static::printLn();
        static::printLn('Le livre ' . $titre . ' a correctement été créé');

        return new Livre($titre, $isbn, $sousTitre, $auteur, $datePublication, $resume);
    }

    /**
     * Demande les informations à l'utilisateur pour emprunter un livre
     * Propose une recherche si l'information saisie ne correspond à aucun livre
     * @param Bibliotheque $bibliotheque La bibliothèque de laquelle le livre est emprunté
     */
    public static function emprunterLivre(Bibliotheque $bibliotheque) {
        static::printLn('Processus d\'emprunt enclenché.');

        do {
            $emprunteur = readline('Nom et prénom de l\'emprunteur : ');
            // On redemande tant que c'est vide
        } while (empty($emprunteur));

        $recherche = ''; // On part avec une recherche vide

        do {
            if ($recherche !== '') {

                if (!empty($livres[$recherche])) {
                    // Si la personne a saisi, dans la recherche, le numéro du livre
                    $livres[$recherche]->etreEmprunte($emprunteur);

                    static::printLn();
                    static::printLn($emprunteur . ' a emprunté ' . $livres[0]->getAffichage() . ' avec succès.');

                    return; // Le boulot est terminé, on peut sortir de la méthode
                }

                // Si une recherche a été faite
                // On récupère les résultats
                $livres = $bibliotheque->chercherLivres($recherche);

                if (count($livres) === 1) {
                    // On a trouvé un seul livre 
                    // ==> C'est celui qu'on veut emprunter
                    $livres[0]->etreEmprunte($emprunteur);

                    static::printLn();
                    static::printLn($emprunteur . ' a emprunté ' . $livres[0]->getAffichage() . ' avec succès.');

                    return; // Le boulot est terminé, on peut sortir de la méthode
                } else {
                    // Sinon on affiche les résultats de la recherche
                    static::printLn();
                    static::printLn(count($livres) . ' livre(s) trouvé(s) avec votre saisie.');
                }
            } else {
                // Sinon on prend tous les livres
                $livres = $bibliotheque->livres;
            }

            if (count($livres) <= 5 && count($livres) > 0) {
                // S'il y a peu de livres, on peut proposer un menu
                static::printLn('Le livre emprunté fait-il partie des suivants ?');
                static::printLn('Si oui, saisissez le numéro correspondant.');
                static::printLn();

                foreach ($livres as $index => $livre) {
                    static::printLn('[' . $index . '] ' . $livre->getAffichage());
                }
            }

            static::printLn();
            static::printLn('Saisissez le livre emprunté. Si votre saisie ne correspond à rien, une recherche sera menée pour vous aider.');

            $recherche = readline('Votre saisie : ');
        } while (true);
    }
}
