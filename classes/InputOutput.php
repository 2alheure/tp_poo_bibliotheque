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
     * et l'insère dans la bibliothèque
     * @param Bibliotheque $bibliotheque La bibliothèque dans laquelle créer le livre
     * @return Livre
     */
    public static function creerLivre(Bibliotheque $bibliotheque): Livre {
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

        $resume = readline('Résumé (optionnel) : ');

        static::printLn();
        static::printLn('Le livre ' . $titre . ' a correctement été créé');

        $livre = new Livre($titre, $isbn, $sousTitre, $auteur, $datePublication, $resume);
        $bibliotheque->ajouterLivre($livre);

        return $livre;
    }

    /**
     * Affiche un menu pour rechercher un livre, 
     * jusqu'à ce qu'un seul livre soit sélectionné
     * @param Bibliotheque $bibliotheque La bibliothèque de laquelle le livre est recherché
     * @return Livre Le livre trouvé, résultat de la recherche
     */
    public static function rechercherLivre(Bibliotheque $bibliotheque): Livre {
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
        } while (true); // On fait une boucle infinie, on ne peut s'arrêter que si un livre est trouvé
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

        $livreEmprunte = static::rechercherLivre($bibliotheque);
        $livreEmprunte->etreEmprunte($emprunteur);
        static::printLn();
        static::printLn($emprunteur . ' a emprunté ' . $livreEmprunte->getAffichage() . ' avec succès.');
    }
}
