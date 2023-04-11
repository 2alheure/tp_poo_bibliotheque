<?php

class Application {
    const
        QUITTER = 0,
        CREER_LIVRE = 1,
        EMPRUNTER_LIVRE = 2;

    public Bibliotheque $bibliotheque;

    public function __construct() {
        $this->bibliotheque = new Bibliotheque;
        $this->bibliotheque->ajouterLivre(new Livre('Harry Potter et la Coupe de Feu', '978000000', auteur: 'J.K. Rolling', datePublication: '30/11/2005'));
        $this->bibliotheque->ajouterLivre(new Livre('Le Seigneur des Anneaux', '978000000', auteur: 'J.R.R. Tolkien', datePublication: '29/07/1954'));
        $this->bibliotheque->ajouterLivre(new Livre('Les Misérables', '978000000', auteur: 'Victor Hugo', datePublication: '03/04/1862'));
    }

    /**
     * Renvoie toutes les fonctionnalités 
     * disponibles dans l'application
     */
    public function getChoix(): array {
        return [
            static::QUITTER => 'Quitter le logiciel',
            static::CREER_LIVRE => 'Enregistrer un nouveau livre',
            static::EMPRUNTER_LIVRE => 'Enregistrer un emprunt',
        ];
    }

    public function quitter() {
        InputOutput::printLn('Nous vous souhaitons une bonne journée.');
        InputOutput::printLn('Au revoir.');

        CSV::ecrireLivres($this->bibliotheque);

        exit;
    }

    public function creerLivre() {
        InputOutput::creerLivre($this->bibliotheque);
    }

    public function emprunterLivre() {
        InputOutput::emprunterLivre($this->bibliotheque);
    }
}