<?php

// Dépendances
require_once(dirname(__FILE__) . '/../models/model.produit.php');
require_once(dirname(__FILE__) . '/../models/model.marque.php');
require_once(dirname(__FILE__) . '/repository.employe.php');
require_once(dirname(__FILE__) . '/repository.marque.php');
require_once(dirname(__FILE__) . '/_repository.php');

// Fichier : repository.activite.php
// Rôle : fournir des méthodes pour lire et compter les activités (actions) des employés en base de données.
// Les méthodes utilisent des procédures stockées et retournent des tableaux associatifs prêts à l'affichage.


class RepositoryActivite extends _repository
{
    /**
     * Retourne toutes les activités enregistrées avec des informations lisibles pour l'affichage.
     *
     * @return array Tableau des activités formatées pour l'affichage.
     */
    public function lireToutesLesActivites()
    {
        $donnee = null;
        // Prépare l'appel à la procédure stockée qui récupère les actions des employés
        $pdoRequete = $this->connexion->prepare("CALL Select_actions_employe()");
        $pdoRequete->execute(); // Exécute la requête
        // Récupère toutes les lignes sous forme de tableau associatif
        $activites = $pdoRequete->fetchAll(PDO::FETCH_ASSOC);
        $pdoRequete->closeCursor(); // Libère la connexion
        if ($activites != FALSE) {

            $donnee = array();

            foreach ($activites as $activite) {
                // Construit un tableau d'informations lisibles pour chaque enregistrement
                // On concatène le prénom, le nom et l'identifiant de l'employé pour l'affichage
                $info = array("Employé" => $activite['prenom']." ".$activite['nom']." "."(".$activite['id_employe'].")",
                            "Date" => $activite['date'],
                            "Action" => $activite['nom_action'],
                            "Message" => $activite['message']
                );
                array_push($donnee, $info); // Ajoute l'activité formatée au tableau
            }
        }
        else {
            // Aucun résultat -> on retourne un tableau vide
            $donnee = array();

        }
        return $donnee; // Retourne le tableau des activités
    }

    /**
     * Compte le nombre d'actions par type (ajout, suppression, consultation) pour un employé sur une plage de dates.
     *
     * @param int $idEmploye Identifiant de l'employé.
     * @param string $date1 Date de début de la période.
     * @param string $date2 Date de fin de la période.
     * @return array Tableau associatif avec les clés 'ajout', 'suppression', 'consultation'.
     */
    public function compterActivitesEmploye(int $idEmploye, string $date1, string $date2)
    {
        $donnee = null;

        // Appel de la procédure stockée avec paramètres nommés
        $pdoRequete = $this->connexion->prepare("CALL Compter_actions_employe(:id, :date1, :date2)");
        $pdoRequete->bindParam(":id",$idEmploye,PDO::PARAM_STR); // Lie l'identifiant
        $pdoRequete->bindParam(":date1",$date1,PDO::PARAM_STR); // Lie la date de début
        $pdoRequete->bindParam(":date2",$date2,PDO::PARAM_STR); // Lie la date de fin
        $pdoRequete->execute(); // Exécute la requête
        // Récupère le résultat agrégé par type d'action
        $activites = $pdoRequete->fetchAll(PDO::FETCH_ASSOC);
        $pdoRequete->closeCursor(); // Libère la connexion
        if ($activites != FALSE) {
            // Initialise les compteurs à zéro par défaut
            $donnee = array("ajout" => 0,
                            "suppression" => 0,
                            "consultation" => 0);

            foreach ($activites as $activite) {
                // Selon l'identifiant de l'action, on alimente le compteur correspondant
                if ($activite['id_action'] == 1) {
                    $donnee["ajout"] = $activite['nombre_action'];
                }

                if ($activite['id_action'] == 2) {
                    $donnee["suppression"] = $activite['nombre_action'];
                }

                if ($activite['id_action'] == 3) {
                    $donnee["consultation"] = $activite['nombre_action'];
                }
            }

        }
        else {

            // Si aucun résultat, on renvoie les compteurs à zéro
            $donnee = array("ajout" => 0,
                            "suppression" => 0,
                            "consultation" => 0);

        }
        return $donnee; // Retourne le tableau des compteurs
    }

    /**
     * Parcourt tous les employés et récupère le nombre d'actions par type pour chacun sur la période donnée.
     *
     * @param string $date1 Date de début de la période.
     * @param string $date2 Date de fin de la période.
     * @return array Tableau associant le nom de l'employé aux compteurs d'actions.
     */
    public function compterActivitesTousLesEmployes(string $date1, string $date2)
    {
        $repositoryEmploye = new RepositoryEmploye($this->connexion); // Instancie le repository employé
        $employes = $repositoryEmploye->lireTousLesEmployes(); // Récupère tous les employés
        $activiteEmployes = array(); // Tableau de résultats
        foreach ($employes as $employe) {
            
            // Appelle la méthode précédente pour chaque employé et construit une ligne lisible
            $activite = $this->compterActivitesEmploye($employe->id, $date1, $date2);
            $activiteEmploye = array("Employé" => $employe->prenom." ".$employe->nom) + $activite;
            array_push($activiteEmployes, $activiteEmploye); // Ajoute au tableau final
        }
        return $activiteEmployes; // Retourne le tableau des activités par employé
    }
}
