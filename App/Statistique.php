<?php

namespace App;

use DateTime;
use App\Bibliotheque;

class Statistique {
    public Bibliotheque $bibliotheque;

    public function __construct(Bibliotheque $bibliotheque) {
        $this->bibliotheque = $bibliotheque;
    }

    public function getNbLivres() {
        // Juste à compter les cases du tableau
        return count($this->bibliotheque->livres);
    }

    public function getNbLivresEmpruntes() {
        $resultat = 0;

        foreach ($this->bibliotheque->livres as $livre) {
            // Pour chaque livre
            // On regarde s'il est rendu
            if (!$livre->rendu) {
                // S'il ne l'est pas on compte +1
                $resultat++;
            }
        }

        return $resultat;
    }

    public function getNbLivresDispos() {
        // Les livres dispos sont ceux qui ne sont pas empruntés ;-)
        return $this->getNbLivres() - $this->getNbLivresEmpruntes();
    }

    public function getNbLivresEnRetard() {
        $resultat = 0;
        $maintenant = new DateTime;

        foreach ($this->bibliotheque->livres as $livre) {
            if (!$livre->rendu && $livre->dateRetour < $maintenant) {
                // Les livres en retard sont ceux qui ne sont pas rendus
                // Et dont la date de retour est dépassée
                $resultat++;
            }
        }

        return $resultat;
    }

    /**
     * Calcule la durée moyenne d'emprunt (en jours)
     */
    public function getDureeMoyenneEmprunt() {
        // Pour une moyenne on doit avoir 2 choses
        $nbEmprunts = 0;
        $dureeTotalEmpruntEnJours = 0;

        foreach ($this->bibliotheque->livres as $livre) {
            if (!empty($livre->dateRetour) && !empty($livre->dateEmprunt)) {
                // On calcule la différence
                // = un objet DateInterval
                $difference = $livre->dateRetour->diff($livre->dateEmprunt, true);

                $nbEmprunts++;
                $dureeTotalEmpruntEnJours += $difference->d; // Le "d" donne les jours (days)
            }
        }

        if ($nbEmprunts === 0) {
            // S'il n'y a eu aucun emprunt, on renvoie 0
            return 0;
        }

        // On renvoie avec 2 nombres après la virgule
        return number_format($dureeTotalEmpruntEnJours / $nbEmprunts, 2, ',');
    }
}
