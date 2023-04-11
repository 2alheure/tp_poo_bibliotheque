<?php

class Livre {
    public string $titre;
    public string $sousTitre;
    public string $auteur;
    public string $datePublication;
    public string $isbn;
    public string $resume;

    // On ajoute 4 infos pour les emprunts
    public bool $emprunte = false;
    public string $emprunteur = '';
    public ?DateTime $dateEmprunt = null;
    public ?DateTime $dateRetour = null;

    // On emprunte toujours pour 14 jours (2 semaines)
    const NB_JOURS_EMPRUNT = 14;

    public function __construct(
        string $titre,
        string $isbn,
        string $sousTitre = '',
        string $auteur = '',
        string $datePublication = '',
        string $resume = '',
    ) {
        $this->titre = $titre;
        $this->isbn = $isbn;
        $this->sousTitre = $sousTitre;
        $this->auteur = $auteur;
        $this->datePublication = $datePublication;
        $this->resume = $resume;
    }

    public function getAffichage(): string {
        $affichage = $this->titre;

        if (!empty($this->sousTitre)) {
            $affichage .= ' : ' . $this->sousTitre;
        }

        if (!empty($this->auteur)) {
            $affichage .= ', de ' . $this->auteur;
        }

        return $affichage;
    }

    /**
     * Met à jour toutes les infos
     * quand on emprunte un livre
     * @param string $emprunteur La personne qui emprunte le livre
     */
    public function etreEmprunte(string $emprunteur) {
        $this->emprunte = true;
        $this->emprunteur = $emprunteur;
        $this->dateEmprunt = new DateTime;
        $this->dateRetour = new DateTime('+' . static::NB_JOURS_EMPRUNT . ' days');
    }

    /**
     * Indique si le livre correspond à la chaîne de caractères
     * qui est recherchée
     * @return bool Si a recherche est trouvée dans le titre, le sous-titre, l'auteur ou l'ISBN
     */
    public function correspondreALaRecherche(string $recherche): bool {
        // On met tout en minuscule pour comparer que des minuscules
        $recherche = mb_strtolower($recherche);

        return
            str_contains(mb_strtolower($this->titre), $recherche) // Trouvé dans le titre
            || str_contains(mb_strtolower($this->sousTitre), $recherche) // Trouvé dans le sous-titre
            || str_contains($this->isbn, $recherche) // Trouvé dans l'ISBN
            || str_contains($this->auteur, $recherche); // Trouvé dans l'auteur
    }
}
