<?php

class CSV {
    /**
     * Ecrit notre bibliothèque en CSV
     * @param Bibliotheque $bibliotheque La bibliothèque à écrire
     * @param string $fichier Le fichier das lequel écrire
     * 
     * @see https://www.php.net/manual/fr/function.fputcsv.php
     * @see https://www.php.net/manual/fr/function.fopen.php
     */
    public static function ecrireLivres(Bibliotheque $bibliotheque) {
        /**
         * Pour écrire un fichier CSV, on doit :
         * 1 / Ouvrir un fichier en écriture
         * 2 / Ecrire chaque ligne une par une
         * 3 / Fermer le fichier
         */

        // 1 / Ouvrir un fichier
        $fichier = fopen(__DIR__ . '/' . $bibliotheque->nomFichier, 'w'); // w = Ecriture + si le fichier n'existe pas on le crée + on écrase l'ancien fichier s'il existe

        if ($fichier === false) {
            // On vérifie qu'on a bien réussi à ouvrir le fichier
            throw new Exception('Impossible d\'ouvrir le fichier ' . $bibliotheque->nomFichier . '.');
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
            'Rendu ?',
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
                $livre->rendu ? 'Vrai' : 'Faux', // Cf opérateur ternaire https://www.php.net/manual/fr/language.operators.comparison.php#language.operators.comparison.ternary
                $livre->dateEmprunt?->format('d/m/Y'), // C'est un objet, il faut le transformer en string
                $livre->dateRetour?->format('d/m/Y'), // C'est un objet, il faut le transformer en string
            ];

            fputcsv($fichier, $ligne);
        }

        // 3 / Fermer le fichier
        fclose($fichier);
    }
}
