<?php

namespace App;

use App\CSV;
use App\Livre;
use App\InputOutput;
use App\Statistique;
use App\Bibliotheque;

class Application {
    const
        QUITTER = 0,
        CREER_LIVRE = 1,
        CONSULTER_LIVRE = 2,
        EMPRUNTER_LIVRE = 3,
        MODIFIER_LIVRE = 4,
        SUPPRIMER_LIVRE = 5,
        STATS = 6;

    public Bibliotheque $bibliotheque;

    public function __construct() {
        $this->bibliotheque = new Bibliotheque;
    }

    /**
     * Renvoie toutes les fonctionnalités 
     * disponibles dans l'application
     */
    public function getChoix(): array {
        return [
            static::QUITTER => 'Quitter le logiciel',
            static::CREER_LIVRE => 'Enregistrer un nouveau livre',
            static::CONSULTER_LIVRE => 'Consulter les informations d\'un livre',
            static::EMPRUNTER_LIVRE => 'Enregistrer un emprunt',
            static::MODIFIER_LIVRE => 'Modifier les informations d\'un livre',
            static::SUPPRIMER_LIVRE => 'Supprimer un livre',
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

        do {
            $titre = readline('Titre : ');
            // On continue tant que le titre n'est pas rempli
        } while (empty($titre));

        $sousTitre = readline('Sous-titre (optionnel) : ');
        $auteur = readline('Auteur (optionnel) : ');

        do {
            $isbn = readline('ISBN : ');
            // On continue tant que le titre n'est pas rempli
        } while (empty($titre));

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

    /**
     * Affiche les statistiques de la bibliothèque à l'utilisateur
     */
    public function consulterStats() {
        $stats = new Statistique($this->bibliotheque);

        InputOutput::printLn('Voici les statistiques de la bibliothèque :');
        InputOutput::printLn('- Nombre de livres : ' . $stats->getNbLivres());
        InputOutput::printLn('- Nombre de livres actuellement empruntés : ' . $stats->getNbLivresEmpruntes());
        InputOutput::printLn('- Nombre de livres actuellement disponibles : ' . $stats->getNbLivresDispos());
        InputOutput::printLn('- Nombre de livres actuellement en retard : ' . $stats->getNbLivresEnRetard());
        InputOutput::printLn('- Durée moyenne des emprunts récents : ' . $stats->getDureeMoyenneEmprunt() . ' jour(s)');
    }

    /**
     * Recherche un livre et en affiche les informations à l'utilisateur
     */
    public function consulterLivre() {
        $livre = InputOutput::rechercherLivre($this->bibliotheque);
        InputOutput::printLn();

        InputOutput::printLn('ISBN : ' . $livre->isbn);
        InputOutput::printLn('Titre : ' . $livre->titre);

        if (!empty($livre->sousTitre))
            InputOutput::printLn('Sous-titre : ' . $livre->sousTitre);

        if (!empty($livre->auteur))
            InputOutput::printLn('Auteur : ' . $livre->auteur);

        if (!empty($livre->datePublication))
            InputOutput::printLn('Date de publication : ' . $livre->datePublication);

        if (!empty($livre->resume))
            InputOutput::printLn('Résumé : ' . $livre->resume);

        if (!empty($livre->emprunteur)) {
            if ($livre->rendu) {
                $string = 'Emprunté pour la dernière fois par ' . $livre->emprunteur
                    . ', du ' . $livre->dateEmprunt->format('d/m/Y')
                    . ' au ' . $livre->dateRetour->format('d/m/Y') . '.';
            } else {
                $string = 'Actuellement emprunté par ' . $livre->emprunteur
                    . ', depuis le ' . $livre->dateEmprunt->format('d/m/Y')
                    . '. Retour prévu le ' . $livre->dateRetour->format('d/m/Y') . '.';
            }
        } else {
            $string = 'Ce livre n\'a encore jamais été emprunté.';
        }
        InputOutput::printLn('EMPRUNT : ' . $string);
    }

    /**
     * Recherche un livre et en supprime les données
     */
    public function supprimerLivre() {
        $livreASupprimer = InputOutput::rechercherLivre($this->bibliotheque);

        foreach ($this->bibliotheque->livres as $index => $livre) {
            if (
                $livre->isbn === $livreASupprimer->isbn
                && $livre->titre === $livreASupprimer->titre
                && $livre->sousTitre === $livreASupprimer->sousTitre
            ) {
                // On s'assure, grâce à l'ISBN, le titre et le sous-titre
                // De supprimer le bon livre
                // Puis on le supprime
                unset($this->bibliotheque->livres[$index]);
            }
        }

        InputOutput::printLn();
        InputOutput::printLn('Livre supprimé avec succès.');
    }

    /**
     * Recherche un livre et propose d'en modifier les données
     */
    public function modifierLivre() {
        $livre = InputOutput::rechercherLivre($this->bibliotheque);

        InputOutput::printLn('Processus de modification de livre enclenché.');
        InputOutput::printLn('Pendant toute la procédure, laissez la saisie vide si vous ne souhaitez pas modifier l\'ancienne valeur.');
        InputOutput::printLn();

        $titre = readline('Titre [' . $livre->titre . '] : ');
        $sousTitre = readline('Sous-titre [' . $livre->sousTitre . '] : ');
        $auteur = readline('Auteur [' . $livre->auteur . '] : ');
        $isbn = readline('ISBN [' . $livre->isbn . ']: ');

        do {
            // On répète au moins une fois
            $datePublication = readline('Date de publication (format jj/mm/aaaa) [' . $livre->datePublication . '] : ');

            // et tant que ce qu'on a donné est invalide
        } while (!empty($datePublication) && date_create_from_format('d/m/Y', $datePublication) === false);

        $resume = readline('Résumé [' . $livre->resume . '] : ');

        if (!empty($titre))
            $livre->titre = $titre;

        if (!empty($sousTitre))
            $livre->sousTitre = $sousTitre;

        if (!empty($auteur))
            $livre->auteur = $auteur;

        if (!empty($isbn))
            $livre->isbn = $isbn;

        if (!empty($datePublication))
            $livre->datePublication = $datePublication;

        if (!empty($resume))
            $livre->resume = $resume;

        InputOutput::printLn();
        InputOutput::printLn('Livre modifié avec succès.');
    }
}
