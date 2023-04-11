<?php

/**
 * Représente l'ensemble des livres 
 * d'une bibliothèque
 */
class Bibliotheque {
    /**
     * @var Livre[]
     */
    public array $livres = [];
    public string $nomFichier = 'books.csv';

    public function __construct(string $nomFichier = 'books.csv') {
        $this->nomFichier = __DIR__ . '/../exports/' . $nomFichier;

        if (file_exists($this->nomFichier)) {
            CSV::lireLivres($this);
        }
    }

    /**
     * Ajoute un livre à la bibliothèque
     * @param Livre $livre Le livre à ajouter
     */
    public function ajouterLivre(Livre $livre) {
        $this->livres[] = $livre;
    }

    /**
     * Recherche parmi les livres et
     * renvoie ceux qui correspondent
     * @param string $recherche La recherche à effectuer
     * @return Livre[] Les livres qui correspondent
     */
    public function chercherLivres(string $recherche): array {
        $resultat = [];

        foreach ($this->livres as $livre) {
            if ($livre->correspondreALaRecherche($recherche)) {
                $resultat[] = $livre;
            }
        }

        return $resultat;
    }
}
