<?php

// Dépendances au framework Slim
use Psr\Http\Message\ResponseInterface      as PsrResponse;
use Psr\Http\Message\ServerRequestInterface as PsrRequest;

require_once(__DIR__ . '/../statuts/statut.php');

class ControllerProduit extends Controller
{
    
    private ?RepositoryProduit $repositoryproduit = null;


    /**
     * Constructeur du contrôleur produit.
     * Initialise le repository, le statut et l'entête HTTP.
     *
     * @param RepositoryProduit $repositoryproduit Instance du repository produit.
     * @return void
     */
    public function __construct(RepositoryProduit $repositoryproduit)
    {
        $this->repositoryproduit = $repositoryproduit;
        $this->statut = new statut();
        $this->entete = $this->statut->GetStatut(200);
        // var_dump($this->repositoryproduit);
    }

    /**
     * Récupère un produit selon son identifiant et retourne la réponse HTTP correspondante.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @param array $arguments Arguments de la route (doit contenir 'id').
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function getSingle(PsrRequest $psrRequest, PsrResponse $psrResponse, array $arguments)
    {
        $code = 200;
        $pattern = "/^[0-9]+$/";
        // Vérifie que l'id est un entier
        if (preg_match($pattern, $arguments['id'])) {
            // Appeler la fonction lireUnProduit du repository
            $produit = $this->repositoryproduit->lireUnProduit($arguments['id'], $this->idEmploye);

            if ($produit->id === 0) {
                // Produit non trouvé
                $code = 404;
                $this->entete = $this->statut->GetStatut(404); 
                $this->reponse = array("message" => $this->entete);
            }
            else {
                // Vérifie les droits d'accès si le produit est désactivé
                if ($produit->actif == 0 && !($this->estSuperviseur)) {

                    $code = 403;
                    $this->entete = $this->statut->GetStatut(403, "Ce produit est désactivé et vous n'avez pas les privilèges nécessaires pour accéder à ses informations."); 
                    $this->reponse = array("message" => $this->entete);
                }
                else {
                    $message = array("message" => $this->entete);
                    $produit = json_decode(json_encode($produit, true), true); // Transforme l'objet en tableau
                    $this->reponse = $message + $produit;
                }
            }
        }
        else 
        {
            // Id non valide
            $code = 404;
            $this->entete = $this->statut->GetStatut(400, "L'id doit être un nombre entier"); 
            $this->reponse = array("message" => $this->entete);
        }
        // Retourne la réponse HTTP encodée
        return $this->encoder($psrResponse->withStatus($code), $this->reponse);
    }

    /**
     * Récupère la collection de tous les produits et retourne la réponse HTTP correspondante.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function getCollection(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        //error_log("controllerGroupe->getCollection");
        $produits = $this->repositoryproduit->lireTousLesProduits($this->idEmploye, $this->estSuperviseur); // Récupère tous les produits
        // Vérifie qu'au moins un produit a été récupéré
        if (count($produits) == 0) {
            $this->entete = $this->statut->GetStatut(403, "Vous n'avez pas les privilèges nécessaires pour accéder à la liste des produits, car tous ont été désactivés."); 
            $this->reponse = array("message" => $this->entete);
            return $this->encoder($psrResponse->withStatus(403), $this->reponse);
        }
        $message = array("message" => $this->entete);
        $produits = array("produits" => $produits);
        $this->reponse = $message + $produits;
        // Retourne la réponse HTTP encodée
        return $this->encoder($psrResponse->withStatus(200), $this->reponse);
    }

    /**
     * Ajoute un produit à partir des données reçues en POST et retourne la réponse HTTP correspondante.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function postSingle(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        $code = 201;
        // Vérifie les droits d'accès (seul un non-superviseur peut ajouter)
        if ($this->estSuperviseur) {
            $this->entete = $this->statut->GetStatut(403); 
            $this->reponse = array("message" => $this->entete);

            return $this->encoder($psrResponse->withStatus(403), $this->reponse);
        }
        $donnees = $psrRequest->getBody()->getContents(); // Récupère le corps de la requête
        // Vérifie que le corps est un JSON valide
        if (!(json_validate($donnees))) {
           
            $this->entete = $this->statut->GetStatut(400, "Le contenu du corps de la requête doit être un json valide"); 
            $this->reponse = array("message" => $this->entete);

            return $this->encoder($psrResponse->withStatus(400), $this->reponse);
        }
        $donnees = $this->decoder($psrRequest, Methodes::post); // Décode le JSON
        $modelAjout = json_decode(file_get_contents(dirname(__FILE__) . '/../models/model.ajout.produit.json'), true); // Modèle attendu
        $donneesAAjouter = $this->mapper($modelAjout, $donnees); // Mappe les données reçues¸
        
        if (count($donneesAAjouter) == 0 ) {
            // Données invalides
            return $this->encoder($psrResponse->withStatus(400), $this->reponse);
        }
        // Ajoute le produit via le repository
        $produit = $this->repositoryproduit->AjouterUnProduit($donneesAAjouter['idMarque'], $donneesAAjouter['nom'], $donneesAAjouter['prix'], $donneesAAjouter['quantite'], $this->idEmploye);

        if ($produit->id === 0) {
            // Erreur lors de l'ajout (marque inexistante)
            $code = 400;
            $this->entete = $this->statut->GetStatut(400, "L'id de marque spécifié ne correspond à aucune de nos marques"); 
            $this->reponse = array("message" => $this->entete);
        }
        else {
            // Produit ajouté avec succès
            $this->entete = $this->statut->GetStatut(201, "Le produit a été créé avec succès");
            $message = array("message" => $this->entete);
            $produit = json_decode(json_encode($produit, true), true); // Transforme l'objet en tableau
            $this->reponse = $message + $produit;
        }
        // Retourne la réponse HTTP encodée
        return $this->encoder($psrResponse->withStatus($code), $this->reponse);
    }

    /**
     * Désactive ou réactive un produit selon son identifiant et retourne la réponse HTTP correspondante.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @param array $arguments Arguments de la route (doit contenir 'id').
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function deleteSingle(PsrRequest $psrRequest, PsrResponse $psrResponse, array $arguments)
    {
        // Vérifie les droits d'accès (seul un superviseur peut désactiver)
        if (!($this->estSuperviseur)) {
            $this->entete = $this->statut->GetStatut(403); 
            $this->reponse = array("message" => $this->entete);
            return $this->encoder($psrResponse->withStatus(403), $this->reponse);
        }

        $code = 200;
        $pattern = "/^[0-9]+$/";
        // Vérifie que l'id est un entier
        if (preg_match($pattern, $arguments['id'])) {
            // Appeler la fonction DesactiverUnProduit du repository
            $produit = $this->repositoryproduit->DesactiverUnProduit($arguments['id'], $this->idEmploye);

            if ($produit->id === 0) {
                // Produit non trouvé
                $code = 404;
                $this->entete = $this->statut->GetStatut(404, "Le produit spécifié n'existe pas."); 
                $this->reponse = array("message" => $this->entete);
            }
            else {
                // Produit désactivé ou réactivé
                if ($produit->actif === 0){
                    $this->entete = $this->statut->GetStatut(200, "Le produit a bel et bien été désactivé."); 
                }
                else { $this->entete = $this->statut->GetStatut(200, "Le produit a bel et bien été réactivé."); }

                $message = array("message" => $this->entete);
                $produit = json_decode(json_encode($produit, true), true); // Transforme l'objet en tableau
                $this->reponse = $message + $produit;
            }   
        }
        else 
        {
            // Id non valide
            $code = 404;
            $this->entete = $this->statut->GetStatut(400, "L'id doit être un nombre entier"); 
            $this->reponse = array("message" => $this->entete);
        }
        // Retourne la réponse HTTP encodée
        return $this->encoder($psrResponse->withStatus($code), $this->reponse);
    }
}
