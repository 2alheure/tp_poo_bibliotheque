<?php

class CSV {
    const FICHIER_LIVRES = 'books.csv';

    /**
     * Ecrit notre bibliothèque en CSV
     * @param Bibliotheque $bibliotheque La bibliothèque à écrire
     * @param string $fichier Le fichier das lequel écrire
     * 
     * @see https://www.php.net/manual/fr/function.fputcsv.php
     * @see https://www.php.net/manual/fr/function.fopen.php
     */
    public static function ecrireLivres(Bibliotheque $bibliotheque, string $nom_du_fichier = self::FICHIER_LIVRES) {
        /**
         * Pour écrire un fichier CSV, on doit :
         * 1 / Ouvrir un fichier en écriture
         * 2 / Ecrire chaque ligne une par une
         * 3 / Fermer le fichier
         */

        // 1 / Ouvrir un fichier
        $fichier = fopen(__DIR__ . '/' . $nom_du_fichier, 'w+'); // w = Ecriture + si le fichier n'existe pas on le crée + on écrase l'ancien fichier s'il existe

        if ($fichier === false) {
            // On vérifie qu'on a bien réussi à ouvrir le fichier
            throw new Exception('Impossible d\'ouvrir le fichier ' . $nom_du_fichier . '.');
        }

        // 2 / Ecrire chaque ligne une par une
        $premiere_ligne = [ // D'abord notre 1re ligne (l'entête)
            'Titre',
            'Sous-titre',
            'Auteur',
            'ISBN',
            'Résumé',
            'Date de publication',
            'Emprunteur',
            'Date d\'emprunt',
            'Date de retour',
        ];
        fputcsv($fichier, $premiere_ligne);

        // Ensuite chacun de nos livres
        foreach ($bibliotheque->livres as $livre) {
            $ligne = [
                $livre->titre,
                $livre->sousTitre,
                $livre->auteur,
                $livre->isbn,
                $livre->resume,
                $livre->datePublication,
                $livre->emprunteur,
                $livre->dateEmprunt,
                $livre->dateRetour,
            ];

            fputcsv($fichier, $ligne);
        }

        // 3 / Fermer le fichier
        fclose($fichier);
    }
}
