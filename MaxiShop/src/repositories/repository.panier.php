<?php

// Dépendances
require_once(dirname(__FILE__) . '/../models/model.panier.php');
require_once(dirname(__FILE__) . '/repository.employe.php');
require_once(dirname(__FILE__) . '/repository.produit.php');
require_once(dirname(__FILE__) . '/_repository.php');


class RepositoryPanier extends _repository
{
    /**
     * Transforme un tableau associatif en objet ModelPanier.
     *
     * @param array $donnees Données du panier sous forme de tableau associatif.
     * @return ModelPanier Objet panier modélisé.
     */
    private function modeliser(array $donnees)
    {
        // Retourne un objet ModelPanier construit à partir des données
        return new ModelPanier(
            $donnees['id_panier'],
            $donnees['date_creation'],
            $donnees['produits'],
            $donnees['employe'],
            $donnees['actif']
        );
    }

    /**
     * Récupère un panier à partir de son identifiant, construit les objets produits et employé associés, journalise la consultation si besoin.
     *
     * @param int $idpanier Identifiant du panier à lire.
     * @param int $idEmploye Identifiant de l'employé qui consulte (optionnel).
     * @return ModelPanier Objet panier modélisé.
     */
    public function lireUnPanier(int $idpanier, int $idEmploye = 0)
    {
        $donnee = null;
        $repositoryEmploye = new RepositoryEmploye($this->connexion); // Instancie le repository employé
        $pdoRequete = $this->connexion->prepare("CALL Select_panier(:id)");
        $pdoRequete->bindParam(":id",$idpanier,PDO::PARAM_STR); // Lie l'identifiant du panier
        $pdoRequete->execute(); // Exécute la requête
        $panier = $pdoRequete->fetch(PDO::FETCH_ASSOC); // Récupère le résultat
        $pdoRequete->closeCursor(); // Libère la connexion
        if ($panier == FALSE) {
            $employe = $repositoryEmploye->lireUnEmploye(0); // Employé par défaut
            $donnee = array('id_panier' => 0,
                            'date_creation' => '0000-00-00',
                            'produits' => array(),
                            'employe' => $employe,
                            'actif' => 0);
        }
        else {
            $produits = array();
            $repositoryProduit = new RepositoryProduit($this->connexion); // Instancie le repository produit
            $employe = $repositoryEmploye->lireUnEmploye($panier['id_employe']); // Récupère l'employé associé au panier
            $requete = $this->connexion->prepare("CALL Select_produit_panier(:id)");
            $requete->bindParam(":id",$idpanier,PDO::PARAM_STR); // Lie l'identifiant du panier
            $requete->execute(); // Exécute la requête
            $produitsPanier = $requete->fetchAll(PDO::FETCH_ASSOC); // Récupère les produits du panier
            $requete->closeCursor(); // Libère la connexion
            foreach ($produitsPanier as $produitPanier) {
                $prod = $repositoryProduit->lireUnProduit($produitPanier['id_produit']); // Récupère le produit
                $produit = array(
                            "produit" => $prod,
                            "quantite" => $produitPanier['quantite_achetee']
                );
                array_push($produits, $produit); // Ajoute le produit au tableau
            }

            $donnee = array('id_panier' => $idpanier,
                            'date_creation' => $panier['date_creation'],
                            'produits' => $produits,
                            'employe' => $employe,
                            'actif' => $panier['actif']);

            if ($idEmploye != 0) {
                $repositoryEmploye = new RepositoryEmploye($this->connexion); // Instancie à nouveau pour journaliser
                $employe = $repositoryEmploye->lireUnEmploye($idEmploye); // Récupère l'employé qui consulte
                if ($panier['actif'] == 1 || ($panier['actif'] == 0 && $employe->role->id == 1)) {
                    $repositoryEmploye->journaliser(3, $idEmploye, "a consulté le panier #".$idpanier."."); // Journalise la consultation
                }
            }
        }
        return $this->modeliser($donnee); // Retourne l'objet panier modélisé
    }
    
    /**
     * Récupère tous les paniers, possibilité de ne retourner que les paniers actifs.
     *
     * @param int $idEmploye Identifiant de l'employé qui consulte.
     * @param bool $superviseur Si vrai, retourne tous les paniers, sinon seulement les actifs.
     * @return array Liste des objets paniers.
     */
    public function lireTousLesPaniers(int $idEmploye, bool $superviseur = true)
    {
        $donnee = null;
        $pdoRequete = $this->connexion->prepare("CALL Select_panier(NULL)");
        $pdoRequete->execute(); // Exécute la requête
        $donnees = $pdoRequete->fetchAll(PDO::FETCH_ASSOC); // Récupère tous les résultats
        $pdoRequete->closeCursor(); // Libère la connexion
        $paniersActifs = array();
        $paniers = array();
        
        foreach ($donnees as $donnee) {
            $panier = $this->lireUnPanier($donnee['id_panier']); // Récupère chaque panier
            if ($panier->actif == 1) {
                array_push($paniersActifs, $panier); // Ajoute à la liste des paniers actifs
            }
            array_push($paniers, $panier); // Ajoute à la liste complète
        }

        $repositoryEmploye = new RepositoryEmploye($this->connexion); // Instancie le repository employé
        $employe = $repositoryEmploye->lireUnEmploye($idEmploye); // Récupère l'employé qui consulte

        if ($employe->role->id == 1 || (count($paniersActifs) != 0 && $employe->role->id != 1)) {
            $repositoryEmploye->journaliser(3, $idEmploye, "a consulté tous les paniers."); // Journalise la consultation
        }

        if ($superviseur) {
            return $paniers; // Retourne tous les paniers
        }
        else {
            return $paniersActifs; // Retourne seulement les paniers actifs
        }

}

    /**
     * Ajoute un nouveau panier, vérifie la validité des produits, gère la transaction et journalise l'ajout.
     *
     * @param int $idEmploye Identifiant de l'employé qui ajoute.
     * @param array $infosProduits Informations sur les produits à ajouter au panier.
     * @return ModelPanier Objet panier modélisé ou erreur.
     */
    public function AjouterUnPanier(int $idEmploye, array $infosProduits)
    {
        $donnee = null;
        $rollBack = false;
        $repositoryProduit = new RepositoryProduit($this->connexion); // Instancie le repository produit
        $repositoryEmploye = new RepositoryEmploye($this->connexion); // Instancie le repository employé
        $this->connexion->beginTransaction(); // Démarre la transaction
        $pdoRequete1 = $this->connexion->prepare("CALL Ajouter_panier(:idEmploye)");
        $pdoRequete1->bindParam(":idEmploye",$idEmploye,PDO::PARAM_STR); // Lie l'identifiant de l'employé
        $pdoRequete1->execute(); // Exécute la requête
        $panier = $pdoRequete1->fetch(PDO::FETCH_ASSOC); // Récupère le panier créé
        $pdoRequete1->closeCursor(); // Libère la connexion

        foreach ($infosProduits as $info) {
            $produit = $repositoryProduit->lireUnProduit($info['idProduit']); // Récupère le produit
            
            if ($produit->id == 0) {
                $rollBack = true; // Produit inexistant, annule la transaction
                $employe = $repositoryEmploye->lireUnEmploye(0); // Employé par défaut
                $donnee = $this->modeliser(array('id_panier' => 0,
                                'date_creation' => '0000-00-00',
                                'produits' => array('produit inexistant'),
                                'employe' => $employe,
                                'actif' => 0));
                
                break;                               
            }elseif ($produit->actif == 0) {
                $rollBack = true; // Produit inactif, annule la transaction
                $employe = $repositoryEmploye->lireUnEmploye(0); // Employé par défaut
                $donnee = $this->modeliser(array('id_panier' => 0,
                                'date_creation' => '0000-00-00',
                                'produits' => array('produit inactif'),
                                'employe' => $employe,
                                'actif' => 0));
                
                break; 
            }

            if ($info['quantite'] > $produit->quantite || $info['quantite'] <= 0) {
                
                if ($info['quantite'] <= 0)
                {
                    $prod = array('quantité inférieure ou égale à 0');
                }
                else { $prod = array('quantité supérieure à celle disponible en stock'); }
                $rollBack = true; // Quantité invalide, annule la transaction
                $employe = $repositoryEmploye->lireUnEmploye(0); // Employé par défaut
                $donnee = $this->modeliser(array('id_panier' => 0,
                                'date_creation' => '0000-00-00',
                                'produits' => $prod,
                                'employe' => $employe,
                                'actif' => 0));
                
                break; 
            }
            $quantiteRestante = $produit->quantite - $info['quantite']; // Calcule la quantité restante
            $pdoRequete2 = $this->connexion->prepare("CALL Lier_produit_a_panier(:idPanier, :idProduit, :quantiteAchetee, :quantiteRestante)");
            $pdoRequete2->bindParam(":idPanier",$panier['id_panier'],PDO::PARAM_STR); // Lie l'id du panier
            $pdoRequete2->bindParam(":idProduit",$info['idProduit'],PDO::PARAM_STR); // Lie l'id du produit
            $pdoRequete2->bindParam(":quantiteAchetee",$info['quantite'],PDO::PARAM_STR); // Lie la quantité achetée
            $pdoRequete2->bindParam(":quantiteRestante",$quantiteRestante,PDO::PARAM_STR); // Lie la quantité restante
            $pdoRequete2->execute(); // Exécute la requête
            $pdoRequete2->closeCursor();           
        }
        if ($rollBack) {
            $this->connexion->rollBack(); // Annule la transaction
        }
        else {
            $this->connexion->commit(); // Valide la transaction
            $donnee = $this->lireUnPanier($panier['id_panier']); // Récupère le panier ajouté
            $repositoryEmploye->journaliser(1, $idEmploye, "a ajouté le panier #".$panier['id_panier']); // Journalise l'ajout
        }

        return $donnee;       

    }

    /**
     * Désactive ou réactive un panier, journalise l'action.
     *
     * @param int $idpanier Identifiant du panier à désactiver/réactiver.
     * @param int $idEmploye Identifiant de l'employé qui effectue l'action.
     * @return ModelPanier Objet panier modélisé.
     */
    public function DesactiverUnPanier(int $idpanier, int $idEmploye)
    {
        $donnee = null;

        $pdoRequete = $this->connexion->prepare("CALL Desactiver_panier(:id)");
        $pdoRequete->bindParam(":id",$idpanier,PDO::PARAM_STR); // Lie l'identifiant du panier
        $pdoRequete->execute(); // Exécute la requête
        $panierDesactive = $pdoRequete->fetch(PDO::FETCH_ASSOC); // Récupère le résultat
        $pdoRequete->closeCursor(); // Libère la connexion
        if ($panierDesactive == FALSE) {
            $repositoryEmploye = new RepositoryEmploye($this->connexion); // Instancie le repository employé
            $employe = $repositoryEmploye->lireUnEmploye(0); // Employé par défaut
            $donnee = array('id_panier' => 0,
                            'date_creation' => '0000-00-00',
                            'produits' => array(),
                            'employe' => $employe,
                            'actif' => 0);
            return $this->modeliser($donnee); // Retourne le panier modélisé par défaut
        }
        else {
            $donnee = $this->lireUnPanier($idpanier); // Récupère le panier modélisé
            if ($donnee->actif == 0) {
                $pref = "a désactivé";
            }
            else {
                $pref = "a réactivé";
            }
            $repositoryEmploye = new RepositoryEmploye($this->connexion); // Instancie le repository employé
            $repositoryEmploye->journaliser(2, $idEmploye, $pref." le panier #".$idpanier); // Journalise l'action
            
            return $donnee; // Retourne le panier modélisé
        }
    }
}
