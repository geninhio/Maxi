<?php

// Dépendances
require_once(dirname(__FILE__) . '/../models/model.produit.php');
require_once(dirname(__FILE__) . '/../models/model.marque.php');
require_once(dirname(__FILE__) . '/repository.employe.php');
require_once(dirname(__FILE__) . '/repository.marque.php');
require_once(dirname(__FILE__) . '/_repository.php');


class RepositoryProduit extends _repository
{
    /**
     * Transforme un tableau associatif en objet ModelProduit avec sa marque.
     *
     * @param array $donnees Données du produit sous forme de tableau associatif.
     * @return ModelProduit Objet produit modélisé.
     */
    private function modeliser(array $donnees)
    {
        $marque = new ModelMarque($donnees['id_marque'], $donnees['nom_marque']); // Crée l'objet marque

        return new ModelProduit(
            $donnees['id_produit'],
            $donnees['nom'],
            $donnees['prix'],
            $donnees['quantite'],
            $marque,
            $donnees['actif']
        ); // Retourne l'objet produit modélisé
    }

    /**
     * Récupère un produit à partir de son identifiant, journalise la consultation si besoin.
     *
     * @param int $idproduit Identifiant du produit à lire.
     * @param int $idEmploye Identifiant de l'employé qui consulte (optionnel).
     * @return ModelProduit Objet produit modélisé.
     */
    public function lireUnProduit(int $idproduit, int $idEmploye = 0)
    {
        $donnee = null;

        $pdoRequete = $this->connexion->prepare("CALL Select_produit(:id)"); // Prépare la requête

        $pdoRequete->bindParam(":id",$idproduit,PDO::PARAM_STR); // Lie l'identifiant du produit

        $pdoRequete->execute(); // Exécute la requête

        $produit = $pdoRequete->fetch(PDO::FETCH_ASSOC); // Récupère le résultat

        $pdoRequete->closeCursor(); // Libère la connexion

        if ($produit == FALSE) {
            // Si aucun résultat, retourne des valeurs par défaut
            $donnee = array('id_produit' => 0,
                            'nom' => 'rien',
                            'prix' => 0.0,
                            'quantite' => 0,
                            'actif' => 0,
                            'id_marque' => 0,
                            'nom_marque' => 'rien');
        }
        else {
            $donnee = $produit;

            if ($idEmploye != 0) {
                $repositoryEmploye = new RepositoryEmploye($this->connexion); // Instancie à nouveau pour journaliser
                $employe = $repositoryEmploye->lireUnEmploye($idEmploye); // Récupère l'employé qui consulte
                if ($donnee['actif'] == 1 || ($donnee['actif'] == 0 && $employe->role->id == 1)) {
                    // Journalise la consultation du produit
                    $repositoryEmploye->journaliser(3, $idEmploye, "a consulté le produit #".$idproduit." : '".$donnee['nom']."'.");
                }
            }
        }

        return $this->modeliser($donnee); // Retourne l'objet produit modélisé

    }
    
    /**
     * Récupère tous les produits, possibilité de ne retourner que les produits actifs.
     *
     * @param int $idEmploye Identifiant de l'employé qui consulte.
     * @param bool $superviseur Si vrai, retourne tous les produits, sinon seulement les actifs.
     * @return array Liste des objets produits.
     */
    public function lireTousLesProduits(int $idEmploye, bool $superviseur = true)
    {
        $donnee = null;

        $pdoRequete = $this->connexion->prepare("CALL Select_produit(NULL)"); // Prépare la requête

        $pdoRequete->execute(); // Exécute la requête

        $donnees = $pdoRequete->fetchAll(PDO::FETCH_ASSOC); // Récupère tous les résultats
        $pdoRequete->closeCursor(); // Libère la connexion

        $produitsActifs = array();
        $produits = array();

        foreach ($donnees as $donnee) {
            
            $produit = $this->modeliser($donnee); // Modélise chaque produit

            if ($produit->actif == 1) {
                 
                array_push($produitsActifs, $produit); // Ajoute à la liste des produits actifs
            }

            array_push($produits, $produit); // Ajoute à la liste complète
        }

        $repositoryEmploye = new RepositoryEmploye($this->connexion); // Instancie le repository employé
        $employe = $repositoryEmploye->lireUnEmploye($idEmploye); // Récupère l'employé qui consulte
        if ($employe->role->id == 1 || (count($produitsActifs) != 0 && $employe->role->id != 1)) {
            $repositoryEmploye->journaliser(3, $idEmploye, "a consulté tous les produits."); // Journalise la consultation
        }

        if ($superviseur) {
            return $produits; // Retourne tous les produits
        }
        else {
            return $produitsActifs; // Retourne seulement les actifs
        }
    }

    /**
     * Ajoute un nouveau produit, vérifie la validité de la marque, journalise l'ajout.
     *
     * @param int $idMarque Identifiant de la marque.
     * @param string $nom Nom du produit.
     * @param float $prix Prix du produit.
     * @param int $quantite Quantité du produit.
     * @param int $idEmploye Identifiant de l'employé qui ajoute.
     * @return ModelProduit Objet produit modélisé.
     */
    public function AjouterUnProduit(int $idMarque, string $nom, float $prix, int $quantite, int $idEmploye)
    {
        $donnee = null;
        $repositoryMarque = new RepositoryMarque($this->connexion); // Instancie le repository marque
        $marque = $repositoryMarque->LireUneMarque($idMarque); // Récupère la marque

        if ($marque->id == 0) {
            // Si la marque est invalide, retourne des valeurs par défaut
            $donnee = array('id_produit' => 0,
                'nom' => 'rien',
                'prix' => 0.0,
                'quantite' => 0,
                'actif' => 0,
                'id_marque' => 0,
                'nom_marque' => 'rien');
        }else {
            $pdoRequete = $this->connexion->prepare("CALL Ajouter_produit(:id, :nom, :prix, :quantite)"); // Prépare la requête

            $pdoRequete->bindParam(":id",$idMarque,PDO::PARAM_STR); // Lie la marque
            $pdoRequete->bindParam(":nom",$nom,PDO::PARAM_STR); // Lie le nom
            $pdoRequete->bindParam(":prix",$prix,PDO::PARAM_STR); // Lie le prix
            $pdoRequete->bindParam(":quantite",$quantite,PDO::PARAM_STR); // Lie la quantité

            $pdoRequete->execute(); // Exécute la requête

            $produitAjoute = $pdoRequete->fetch(PDO::FETCH_ASSOC); // Récupère le résultat
            $donnee = $produitAjoute;
            $pdoRequete->closeCursor(); // Libère la connexion

            $repositoryEmploye = new RepositoryEmploye($this->connexion); // Instancie le repository employé
            $repositoryEmploye->journaliser(1, $idEmploye, "a ajouté le produit #".$donnee['id_produit']." : '".$donnee['nom']."'."); // Journalise l'ajout
        }
        

        return $this->modeliser($donnee); // Retourne l'objet produit modélisé

    }

    /**
     * Désactive ou réactive un produit, journalise l'action.
     *
     * @param int $idproduit Identifiant du produit à désactiver/réactiver.
     * @param int $idEmploye Identifiant de l'employé qui effectue l'action.
     * @return ModelProduit Objet produit modélisé.
     */
    public function DesactiverUnProduit(int $idproduit, int $idEmploye)
    {
        $donnee = null;

        $pdoRequete = $this->connexion->prepare("CALL Desactiver_produit(:id)"); // Prépare la requête

        $pdoRequete->bindParam(":id",$idproduit,PDO::PARAM_STR); // Lie l'identifiant du produit

        $pdoRequete->execute(); // Exécute la requête

        $produitDesactive = $pdoRequete->fetch(PDO::FETCH_ASSOC); // Récupère le résultat
        $pdoRequete->closeCursor(); // Libère la connexion

        if ($produitDesactive == FALSE) {
            // Si aucun résultat, retourne des valeurs par défaut
            $donnee = array('id_produit' => 0,
                            'nom' => 'rien',
                            'prix' => 0.0,
                            'quantite' => 0,
                            'actif' => 0,
                            'id_marque' => 0,
                            'nom_marque' => 'rien');
        }
        else {
            $donnee = $produitDesactive;
            if ($donnee['actif'] == 0) {
                $pref = "a désactivé";
            }
            else {
                $pref = "a réactivé";
            }
            $repositoryEmploye = new RepositoryEmploye($this->connexion); // Instancie le repository employé
            $repositoryEmploye->journaliser(2, $idEmploye, $pref." le produit #".$idproduit." : '".$donnee['nom']."'."); // Journalise l'action
        }

        return $this->modeliser($donnee); // Retourne l'objet produit modélisé
    }
}
