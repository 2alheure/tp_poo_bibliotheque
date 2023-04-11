<?php

class InputOutput {
    /**
     * Affiche le contenu SANS sauter une ligne
     * @param ?string $prompt Ce qui est à afficher
     */
    public static function print(?string $prompt) {
        echo $prompt;
    }

    /**
     * Affiche le contenu PUIS saute une ligne
     * @param ?string $prompt Ce qui est à afficher
     */
    public static function printLn(?string $prompt) {
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
    public static function createBook(): Livre {
        static::printLn('Processus de création de livre enclenché :');

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

        return new Livre($titre, $isbn, $sousTitre, $auteur, $datePublication, $resume);
    }
}
