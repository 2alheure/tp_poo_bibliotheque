<?php

class Livre {
    public string $titre;
    public string $sousTitre;
    public string $auteur;
    public string $datePublication;
    public string $isbn;
    public string $resume;

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
}
