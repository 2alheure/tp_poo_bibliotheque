<?php

class Application {
    const
        QUITTER = 0,
        CREER_LIVRE = 1,
        EMPRUNTER_LIVRE = 2,
        STATS = 3;

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
            static::STATS => 'Consulter les statistiques',
        ];
    }

    public function quitter() {
        InputOutput::printLn('Nous vous souhaitons une bonne journée.');
        InputOutput::printLn('Au revoir.');

        CSV::ecrireLivres($this->bibliotheque);

        exit;
    }

    /**
     * Demande les informations à l'utilisateur pour créer un livre
     * et l'insère dans la bibliothèque
     */
    public function creerLivre() {
        InputOutput::printLn('Processus de création de livre enclenché.');

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

        InputOutput::printLn();
        InputOutput::printLn('Le livre ' . $titre . ' a correctement été créé');

        $livre = new Livre($titre, $isbn, $sousTitre, $auteur, $datePublication, $resume);
        $this->bibliotheque->ajouterLivre($livre);

        return $livre;
    }

    /**
     * Demande les informations à l'utilisateur pour emprunter un livre
     * Propose une recherche si l'information saisie ne correspond à aucun livre
     */
    public function emprunterLivre() {
        InputOutput::printLn('Processus d\'emprunt enclenché.');

        do {
            $emprunteur = readline('Nom et prénom de l\'emprunteur : ');
            // On redemande tant que c'est vide
        } while (empty($emprunteur));

        $livreEmprunte = InputOutput::rechercherLivre($this->bibliotheque);
        $livreEmprunte->etreEmprunte($emprunteur);
        InputOutput::printLn();
        InputOutput::printLn($emprunteur . ' a emprunté ' . $livreEmprunte->getAffichage() . ' avec succès.');
    }

    public function consulterStats() {
        $stats = new Statistique($this->bibliotheque);

        InputOutput::printLn('Voici les statistiques de la bibliothèque :');
        InputOutput::printLn('- Nombre de livres : ' . $stats->getNbLivres());
        InputOutput::printLn('- Nombre de livres actuellement empruntés : ' . $stats->getNbLivresEmpruntes());
        InputOutput::printLn('- Nombre de livres actuellement disponibles : ' . $stats->getNbLivresDispos());
        InputOutput::printLn('- Nombre de livres actuellement en retard : ' . $stats->getNbLivresEnRetard());
        InputOutput::printLn('- Durée moyenne des emprunts récents : ' . $stats->getDureeMoyenneEmprunt() . ' jour(s)');
    }
}
