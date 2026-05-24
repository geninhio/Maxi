<?php

// Dépendances au framework Slim
use Psr\Http\Message\ResponseInterface      as PsrResponse;
use Psr\Http\Message\ServerRequestInterface as PsrRequest;

require_once(__DIR__ . '/../statuts/statut.php');

class ControllerPanier extends Controller
{
    
    private ?RepositoryPanier $repositorypanier = null;


    /**
     * Constructeur du contrôleur panier.
     * Initialise le repository, le statut et l'entête HTTP.
     *
     * @param RepositoryPanier $repositorypanier Instance du repository panier.
     * @return void
     */
    public function __construct(RepositoryPanier $repositorypanier)
    {
        $this->repositorypanier = $repositorypanier;
        $this->statut = new statut();
        $this->entete = $this->statut->GetStatut(200, "ok");
    }

    /**
     * Récupère un panier selon son identifiant et retourne la réponse HTTP correspondante.
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
            // Appeler la fonction lireUnpanier du repository
            $panier = $this->repositorypanier->lireUnPanier($arguments['id'], $this->idEmploye);
            if ($panier->id == 0) {
                // Panier non trouvé
                $code = 404;
                $this->entete = $this->statut->GetStatut(404); 
                $this->reponse = array("message" => $this->entete);
            }
            else {
                // Vérifie les droits d'accès si le panier est désactivé
                if ($panier->actif == 0 && !($this->estSuperviseur)) {
                    $code = 403;
                    $this->entete = $this->statut->GetStatut(403, "Ce panier est désactivé et vous n'avez pas les privilèges nécessaires pour accéder à ses informations."); 
                    $this->reponse = array("message" => $this->entete);
                }
                else
                {
                    $message = array("message" => $this->entete);
                    $panier = json_decode(json_encode($panier, true), true); // Transforme l'objet en tableau
                    $this->reponse = $message + $panier;
                }
            }
        }
        else 
        {
            // Id non valide
            $code = 400;
            $this->entete = $this->statut->GetStatut(400, "L'id doit être un nombre entier"); 
            $this->reponse = array("message" => $this->entete);
        }
        // Retourne la réponse HTTP encodée
        return $this->encoder($psrResponse->withStatus($code), $this->reponse);
    }

    /**
     * Récupère la collection de tous les paniers et retourne la réponse HTTP correspondante.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function getCollection(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        $paniers = $this->repositorypanier->lireTousLesPaniers($this->idEmploye, $this->estSuperviseur); // Récupère tous les paniers
        // Vérifie qu'au moins un panier a été récupéré
        if (count($paniers) == 0) {
            $this->entete = $this->statut->GetStatut(403, "Vous n'avez pas les privilèges nécessaires pour accéder à la liste des paniers, car tous ont été désactivés."); 
            $this->reponse = array("message" => $this->entete);
            return $this->encoder($psrResponse->withStatus(403), $this->reponse);
        }
        $message = array("message" => $this->entete);
        $paniers = array("paniers" => $paniers);
        $this->reponse = $message + $paniers;
        // Retourne la réponse HTTP encodée
        return $this->encoder($psrResponse->withStatus(200), $this->reponse);
    }

    /**
     * Ajoute un panier à partir des données reçues en POST et retourne la réponse HTTP correspondante.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function postSingle(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        $donnees = $psrRequest->getBody()->getContents(); // Récupère le corps de la requête
        // Vérifie que le corps est un JSON valide
        if (!(json_validate($donnees))) {
            $this->entete = $this->statut->GetStatut(400, "Le contenu du corps de la requête doit être un json valide"); 
            $this->reponse = array("message" => $this->entete);
            return $this->encoder($psrResponse->withStatus(400), $this->reponse);
        }
        $donnees = $this->decoder($psrRequest, Methodes::post); // Décode le JSON
        $modelAjout = json_decode(file_get_contents(dirname(__FILE__) . '/../models/model.ajout.panier.json'), true); // Modèle attendu
        $donneesAAjouter = $this->mapper($modelAjout, $donnees); // Mappe les données reçues
        if (count($donneesAAjouter) == 0 ) {
            // Données invalides
            return $this->encoder($psrResponse->withStatus(400), $this->reponse);
        }
        // var_dump($donneesAAjouter);
        $produitsAAjouter = $donneesAAjouter['panier']; // Récupère la liste des produits à ajouter
        // Vérifie qu'il n'y a pas de doublons de produits dans le panier
        if (count($produitsAAjouter) >= 2) {
            $idsProduits = array();
            foreach ($produitsAAjouter as $produitAAjouter) {
                array_push($idsProduits, $produitAAjouter["idProduit"]);
            }
            for ($i=0; $i < count($produitsAAjouter); $i++) { 
                array_splice($idsProduits, 0, 1);
                if (in_array($produitsAAjouter[$i]["idProduit"], $idsProduits)){
                    $this->entete = $this->statut->GetStatut(400, "Le Json du corps de la requête contient au deux fois le produit d'id ".$produitsAAjouter[$i]["idProduit"].". S'il vous plaît veuillez rassembler toutes ses occurences en une seule."); 
                    $this->reponse = array("message" => $this->entete);
                    return $this->encoder($psrResponse->withStatus(400), $this->reponse);
                }
            }
        }
        $panier = $this->repositorypanier->AjouterUnPanier($this->idEmploye, $produitsAAjouter); // Ajoute le panier via le repository
        if ($panier->id == 0) {
            // Gestion des erreurs lors de l'ajout du panier
            if ($panier->produits[0] == 'produit inexistant' || $panier->produits[0] == 'produit inactif') {
                $this->entete = $this->statut->GetStatut(400, "Le Json du corps de la requête contient au moins un produit inexistant dans notre inventaire"); 
                $this->reponse = array("message" => $this->entete);
                return $this->encoder($psrResponse->withStatus(400), $this->reponse);
            }
            else {
                $this->entete = $this->statut->GetStatut(400, "Le Json du corps de la requête contient au moins un produit demandé en ".$panier->produits[0]); 
                $this->reponse = array("message" => $this->entete);
                return $this->encoder($psrResponse->withStatus(400), $this->reponse);
            }
        }
        else {
            // Panier ajouté avec succès
            $this->entete = $this->statut->GetStatut(201, "Le panier a été créé avec succès");
            $message = array("message" => $this->entete);
            $panier = json_decode(json_encode($panier, true), true); // Transforme l'objet en tableau
            $this->reponse = $message + $panier;            
        }
        // Retourne la réponse HTTP encodée
        return $this->encoder($psrResponse->withStatus(201), $this->reponse);
    }

    /**
     * Désactive ou réactive un panier selon son identifiant et retourne la réponse HTTP correspondante.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @param array $arguments Arguments de la route (doit contenir 'id').
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function deleteSingle(PsrRequest $psrRequest, PsrResponse $psrResponse, array $arguments)
    {
        $pattern = "/^[0-9]+$/";
        // Vérifie que l'id est un entier
        if (preg_match($pattern, $arguments['id'])) {
            $code = 200;
            // Appeler la fonction DesactiverUnPanier du repository
            $panier = $this->repositorypanier->DesactiverUnPanier($arguments['id'], $this->idEmploye);
            if ($panier->id === 0) {
                // Panier non trouvé
                $code = 404;
                $this->entete = $this->statut->GetStatut(404, "Le panier spécifié n'existe pas."); 
                $this->reponse = array("message" => $this->entete);
            }
            else {
                // Panier désactivé ou réactivé
                if ($panier->actif === 0) {
                    $this->entete = $this->statut->GetStatut(200, "Le panier a bel et bien été désactivé.");
                }
                else { $this->entete = $this->statut->GetStatut(200, "Le panier a bel et bien été réactivé."); }
                $message = array("message" => $this->entete);
                $panier = json_decode(json_encode($panier, true), true); // Transforme l'objet en tableau
                $this->reponse = $message + $panier;
            }   
        }
        else 
        {
            // Id non valide
            $code = 400;
            $this->entete = $this->statut->GetStatut(400, "L'id doit être un nombre entier"); 
            $this->reponse = array("message" => $this->entete);
        }
        // Retourne la réponse HTTP encodée
        return $this->encoder($psrResponse->withStatus($code), $this->reponse);
    }
}
