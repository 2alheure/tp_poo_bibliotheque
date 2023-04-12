<?php

namespace App;

use App\Livre;
use Exception;
use App\Bibliotheque;

class CSV {
    /**
     * Ecrit notre bibliothèque en CSV
     * @param Bibliotheque $bibliotheque La bibliothèque à écrire
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
        $fichier = fopen($bibliotheque->nomFichier, 'w'); // w = Ecriture (write) + si le fichier n'existe pas on le crée + on écrase l'ancien fichier s'il existe

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

    /**
     * Remplit notre bibliothèque à partir de son CSV
     * @param Bibliotheque $bibliotheque La bibliothèque à remplir
     * 
     * @see https://www.php.net/manual/fr/function.fputcsv.php
     * @see https://www.php.net/manual/fr/function.fopen.php
     */
    public static function lireLivres(Bibliotheque $bibliotheque) {
        /**
         * Pour lire un fichier CSV on doit :
         * 1 / Ouvrir le fichier
         * 2 / Lire toutes les lignes
         * 3 / Fermer le fichier
         */

        // 1 / Ouvrir un fichier
        $fichier = fopen($bibliotheque->nomFichier, 'r'); // r = Lecture (read)

        // 2 / Lire toutes les lignes
        $premiere_ligne = true;
        while (true) { // On le fait dans une boucle infinie 
            $ligne = fgetcsv($fichier);

            if ($ligne == false) // Si on a false, on est à la fin du fichier => on s'arrête
                break;

            if ($premiere_ligne) {
                // Si on est sur la première ligne
                // On l'ignore
                $premiere_ligne = false;
                continue;
            } else {
                $livre = new Livre($ligne[0], $ligne[3], $ligne[1], $ligne[2], $ligne[5], $ligne[4]);
                $livre->emprunteur = $ligne[6];
                $livre->rendu = $ligne[7] == 'Vrai';

                if (!empty($livre->emprunteur)) {
                    $livre->dateEmprunt = date_create_from_format('d/m/Y', $ligne[8]); // On doit passer d'une string à un Datetime
                    $livre->dateRetour = date_create_from_format('d/m/Y', $ligne[9]); // On doit passer d'une string à un Datetime
                }

                $bibliotheque->ajouterLivre($livre);
            }
        }

        // 3 / Fermer le fichier
        fclose($fichier);
    }
}
