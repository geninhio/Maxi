<?php

// Dépendances
require_once(dirname(__FILE__) . '/../models/model.departement.php');
require_once(dirname(__FILE__) . '/../models/model.employe.php');
require_once(dirname(__FILE__) . '/../models/model.poste.php');
require_once(dirname(__FILE__) . '/../models/model.sexe.php');
require_once(dirname(__FILE__) . '/../models/model.role.php');
require_once(dirname(__FILE__) . '/repository.poste.php');
require_once(dirname(__FILE__) . '/repository.sexe.php');
require_once(dirname(__FILE__) . '/repository.role.php');
require_once(dirname(__FILE__) . '/_repository.php');


// Classe RepositoryEmploye : gère les opérations liées aux employés (lecture, ajout, désactivation, journalisation)
class RepositoryEmploye extends _repository
{
    /**
     * Transforme un tableau associatif en objet ModelEmploye avec ses dépendances.
     *
     * @param array $donnees Données de l'employé sous forme de tableau associatif.
     * @return ModelEmploye Objet employé modélisé.
     */
    private function modeliser(array $donnees)
    {
        // Création des objets dépendants (sexe, rôle, département, poste)
        $sexe = new ModelSexe($donnees['id_sexe'], $donnees['nom_sexe']);
        $role = new ModelRole($donnees['id_role'], $donnees['nom_role']);
        $departement = new ModelDepartement($donnees['id_departement'], $donnees['nom_departement']);
        $poste = new ModelPoste($donnees['id_poste'], $donnees['nom_poste'], $departement);
        // Retourne un objet ModelEmploye construit à partir des données
        return new ModelEmploye(
            $donnees['id_employes'],
            $donnees['prenom'],
            $donnees['nom'],
            $donnees['date_naissance'],
            $sexe,
            $role,
            $poste,
            $donnees['actif']
        );
    }

    /**
     * Journalise une action effectuée par un employé (ajout, consultation, désactivation...)
     *
     * @param int $idAction Identifiant de l'action.
     * @param int $idEmploye Identifiant de l'employé.
     * @param string $message Message à journaliser.
     * @return void
     */
    public function journaliser(int $idAction, int $idEmploye, string $message)
    {
        date_default_timezone_set('America/Toronto'); // Définit le fuseau horaire
        $date = date("Y-m-d H:i:s"); // Génère la date et l'heure actuelles
        $pdoRequete = $this->connexion->prepare("CALL Journaliser(:idAction, :idEmploye, :date, :message)");
        $pdoRequete->bindParam(":idAction",$idAction,PDO::PARAM_STR); // Lie l'identifiant de l'action
        $pdoRequete->bindParam(":idEmploye",$idEmploye,PDO::PARAM_STR); // Lie l'identifiant de l'employé
        $pdoRequete->bindParam(":date",$date,PDO::PARAM_STR); // Lie la date
        $pdoRequete->bindParam(":message",$message,PDO::PARAM_STR); // Lie le message
        $pdoRequete->execute(); // Exécute la requête
        $pdoRequete->closeCursor(); // Libère la connexion
    }

    /**
     * Récupère un employé à partir de son hash unique.
     *
     * @param string $idhash Hash de l'employé.
     * @return array Données de l'employé ou valeurs par défaut si non trouvé.
     */
    public function lireUnEmployeAvecSonHash(string $idhash)
    {
        $donnee = null;
        $pdoRequete = $this->connexion->prepare("CALL Select_employe_avec_hash(:hash)");
        $pdoRequete->bindParam(":hash",$idhash,PDO::PARAM_STR); // Lie le hash
        $pdoRequete->execute(); // Exécute la requête
        $employe = $pdoRequete->fetch(PDO::FETCH_ASSOC); // Récupère le résultat
        $pdoRequete->closeCursor(); // Libère la connexion
        if ($employe == FALSE) {
            // Si aucun résultat, retourne des valeurs par défaut
            $donnee = array('id_employes' => 0,
                        'prenom' => 'rien',
                        'nom' => 'rien',
                        'date_naissance' => '0000-00-00',
                        'id_hash' => 'rien',
                        'hash' => 'rien',
                        'id_sexe' => 0,
                        'nom_sexe' => 'rien',
                        'id_role' => 0,
                        'nom_role' => 'rien',
                        'id_poste' => 0,
                        'nom_poste' => 'rien',
                        'id_departement' => 0,
                        'nom_departement' => 'rien',
                        'actif' => 0);

        }
        else {
            $donnee = $employe;
        }
        
        return $donnee;
    }

    /**
     * Récupère un employé à partir de son identifiant, journalise la consultation si besoin.
     *
     * @param int $idemploye Identifiant de l'employé à lire.
     * @param int $idEmploye Identifiant de l'employé qui consulte (optionnel).
     * @return ModelEmploye Objet employé modélisé.
     */
    public function lireUnEmploye(int $idemploye, int $idEmploye = 0)
    {
        $donnee = null;

        $pdoRequete = $this->connexion->prepare("CALL Select_employe_avec_id(:id)");
        $pdoRequete->bindParam(":id",$idemploye,PDO::PARAM_STR); // Lie l'identifiant
        $pdoRequete->execute(); // Exécute la requête
        $employe = $pdoRequete->fetch(PDO::FETCH_ASSOC); // Récupère le résultat
        $pdoRequete->closeCursor(); // Libère la connexion
        if ($employe == FALSE) {
            // Si aucun résultat, retourne des valeurs par défaut
            $donnee = array('id_employes' => 0,
                            'prenom' => 'rien',
                            'nom' => 'rien',
                            'date_naissance' => '0000-00-00',
                            'id_hash' => 'rien',
                            'hash' => 'rien',
                            'id_sexe' => 0,
                            'nom_sexe' => 'rien',
                            'id_role' => 0,
                            'nom_role' => 'rien',
                            'id_poste' => 0,
                            'nom_poste' => 'rien',
                            'id_departement' => 0,
                            'nom_departement' => 'rien',
                            'actif' => 0);
        }
        else {
            $donnee = $employe;

            if ($idEmploye != 0) {
                $employe = $this->lireUnEmploye($idEmploye); // Récupère l'employé qui consulte
                if ($donnee['actif'] == 1 || ($donnee['actif'] == 0 && $employe->role->id == 1)) {
                    // Journalise la consultation de l'employé
                    $this->journaliser(3, $idEmploye, "a consulté l'employé ".$donnee['nom']." ".$donnee['prenom']);
                }
            }

        }
        return $this->modeliser($donnee); // Retourne l'objet employé modélisé
    }
    
    /**
     * Récupère tous les employés, possibilité de ne retourner que les actifs.
     *
     * @param int $idEmploye Identifiant de l'employé qui consulte (optionnel).
     * @param bool $superviseur Si vrai, retourne tous les employés, sinon seulement les actifs.
     * @return array Liste des objets employés.
     */
    public function lireTousLesEmployes(int $idEmploye = 0, bool $superviseur = true)
    {
        $donnee = null;

        $pdoRequete = $this->connexion->prepare("CALL Select_employe_avec_id(NULL)");
        $pdoRequete->execute(); // Exécute la requête
        $donnees = $pdoRequete->fetchAll(PDO::FETCH_ASSOC); // Récupère tous les résultats
        $pdoRequete->closeCursor(); // Libère la connexion
        $employesActifs = array();
        $employes = array();

        foreach ($donnees as $donnee) {
            $employe = $this->modeliser($donnee); // Modélise chaque employé
            if ($employe->actif == 1) {
                // Ajoute à la liste des employés actifs
                array_push($employesActifs, $employe);
            }
            array_push($employes, $employe); // Ajoute à la liste complète
        }

        if ($idEmploye != 0) {
            $employe = $this->lireUnEmploye($idEmploye); // Récupère l'employé qui consulte
            if ($employe->role->id == 1 || (count($employesActifs) != 0 && $employe->role->id != 1)) {
                // Journalise la consultation de tous les employés
                $this->journaliser(3, $idEmploye, "a consulté tous les employés.");
            }
        }

        if ($superviseur) {
            return $employes; // Retourne tous les employés
        }
        else {
            return $employesActifs; // Retourne seulement les actifs
        }
    }

    /**
     * Ajoute un nouvel employé en base, vérifie la validité des dépendances, journalise l'ajout.
     *
     * @param string $prenom Prénom de l'employé.
     * @param string $nom Nom de l'employé.
     * @param string $dateNaissance Date de naissance.
     * @param int $idSexe Identifiant du sexe.
     * @param int $idPoste Identifiant du poste.
     * @param int $idRole Identifiant du rôle.
     * @param string $idHash Identifiant hash.
     * @param string $hash Hash.
     * @param int $idEmploye Identifiant de l'employé qui ajoute.
     * @return ModelEmploye Objet employé modélisé.
     */
    public function AjouterUnEmploye(string $prenom, string $nom, string $dateNaissance, int $idSexe, int $idPoste, int $idRole, string $idHash, string $hash, int $idEmploye)
    {
        $donnee = null;
        $repositorySexe = new RepositorySexe($this->connexion); // Repository pour le sexe
        $repositoryPoste = new RepositoryPoste($this->connexion); // Repository pour le poste
        $repositoryRole = new RepositoryRole($this->connexion); // Repository pour le rôle
        $sexe = $repositorySexe->LireUnSexe($idSexe); // Récupère le sexe
        $poste = $repositoryPoste->LireUnPoste($idPoste); // Récupère le poste
        $role = $repositoryRole->LireUnRole($idRole); // Récupère le rôle

        if ($sexe->id == 0 || $poste->id == 0 || $role->id == 0) {
            // Si une dépendance est invalide, retourne des valeurs par défaut
            $donnee = array('id_employes' => 0,
                            'prenom' => 'rien',
                            'nom' => 'rien',
                            'date_naissance' => '0000-00-00',
                            'id_hash' => 'rien',
                            'hash' => 'rien',
                            'id_sexe' => 0,
                            'nom_sexe' => 'rien',
                            'id_role' => 0,
                            'nom_role' => 'rien',
                            'id_poste' => 0,
                            'nom_poste' => 'rien',
                            'id_departement' => 0,
                            'nom_departement' => 'rien',
                            'actif' => 0);
        }else {
            
            $pdoRequete = $this->connexion->prepare("CALL Ajouter_employe(:idSexe, :idRole, :idPoste, :prenom, :nom, :date, :idHash, :hash)");
            $pdoRequete->bindParam(":idSexe",$idSexe,PDO::PARAM_STR); // Lie le sexe
            $pdoRequete->bindParam(":idRole",$idRole,PDO::PARAM_STR); // Lie le rôle
            $pdoRequete->bindParam(":idPoste",$idPoste,PDO::PARAM_STR); // Lie le poste
            $pdoRequete->bindParam(":prenom",$prenom,PDO::PARAM_STR); // Lie le prénom
            $pdoRequete->bindParam(":nom",$nom,PDO::PARAM_STR); // Lie le nom
            $pdoRequete->bindParam(":date",$dateNaissance,PDO::PARAM_STR); // Lie la date de naissance
            $pdoRequete->bindParam(":idHash",$idHash,PDO::PARAM_STR); // Lie l'id hash
            $pdoRequete->bindParam(":hash",$hash,PDO::PARAM_STR); // Lie le hash
            $pdoRequete->execute(); // Exécute la requête
            $employeAjoute = $pdoRequete->fetch(PDO::FETCH_ASSOC); // Récupère le résultat
            $pdoRequete->closeCursor(); // Libère la connexion
            $donnee = $employeAjoute;
            // Journalise l'ajout de l'employé
            $this->journaliser(1, $idEmploye, "a ajouté l'employé : ".$donnee['nom']." ".$donnee['prenom']." (".$donnee['id_employes'].")");

        }
        return $this->modeliser($donnee); // Retourne l'objet employé modélisé
    }

    /**
     * Désactive ou réactive un employé, journalise l'action.
     *
     * @param int $idemploye Identifiant de l'employé à désactiver/réactiver.
     * @param int $idEmploye Identifiant de l'employé qui effectue l'action.
     * @return ModelEmploye Objet employé modélisé.
     */
    public function DesactiverUnEmploye(int $idemploye, int $idEmploye)
    {
        $donnee = null;

        $pdoRequete = $this->connexion->prepare("CALL Desactiver_employe(:id)");
        $pdoRequete->bindParam(":id",$idemploye,PDO::PARAM_STR); // Lie l'identifiant
        $pdoRequete->execute(); // Exécute la requête
        $employeDesactive = $pdoRequete->fetch(PDO::FETCH_ASSOC); // Récupère le résultat
        $pdoRequete->closeCursor(); // Libère la connexion
        
        if ($employeDesactive == FALSE) {
            // Si aucun résultat, retourne des valeurs par défaut
            $donnee = array('id_employes' => 0,
                            'prenom' => 'rien',
                            'nom' => 'rien',
                            'date_naissance' => '0000-00-00',
                            'id_hash' => 'rien',
                            'hash' => 'rien',
                            'id_sexe' => 0,
                            'nom_sexe' => 'rien',
                            'id_role' => 0,
                            'nom_role' => 'rien',
                            'id_poste' => 0,
                            'nom_poste' => 'rien',
                            'id_departement' => 0,
                            'nom_departement' => 'rien',
                            'actif' => 0);
        }
        else {
            $donnee = $employeDesactive;
            
            if ($donnee['actif'] == 0) {
                $pref = "a désactivé";
            }
            else {
                $pref = "a réactivé";
            }
            // Journalise la désactivation ou la réactivation
            $this->journaliser(2, $idEmploye, $pref." l'employé : '".$donnee['nom']." ".$donnee['prenom']."' (".$donnee['id_employes'].")");
        }
        return $this->modeliser($donnee); // Retourne l'objet employé modélisé
    }
}
