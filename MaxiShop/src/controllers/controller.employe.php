<?php

// Dépendances au framework Slim
use Psr\Http\Message\ResponseInterface      as PsrResponse;
use Psr\Http\Message\ServerRequestInterface as PsrRequest;

require_once(__DIR__ . '/../statuts/statut.php');

class ControllerEmploye extends Controller
{
    

    /**
     * Constructeur du contrôleur employé.
     * Initialise le repository, le statut et l'entête HTTP.
     *
     * @param RepositoryEmploye $repositoryemploye Instance du repository employé.
     * @return void
     */
    public function __construct(RepositoryEmploye $repositoryemploye)
    {
        $this->repositoryemploye = $repositoryemploye;
        $this->statut = new statut();
        $this->entete = $this->statut->GetStatut(200);
        // var_dump($this->repositoryemploye);
    }

    /**
     * Récupère un employé selon son identifiant et retourne la réponse HTTP correspondante.
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
            // Appeler la fonction lireUnemploye du repository
            $employe = $this->repositoryemploye->lireUnEmploye($arguments['id'], $this->idEmploye);
            if ($employe->id == 0) {
                // Employé non trouvé
                $code = 404;
                $this->entete = $this->statut->GetStatut(404); 
                $this->reponse = array("message" => $this->entete);
            }
            else {
                // Vérifie les droits d'accès si l'employé est désactivé
                if ($employe->actif == 0 && !($this->estSupervieur)) {
                    $code = 403;
                    $this->entete = $this->statut->GetStatut(403, "Cet employé est désactivé et vous n'avez pas les privilèges nécessaires pour accéder à ses informations."); 
                    $this->reponse = array("message" => $this->entete);
                }
                else
                {
                    $this->entete = $this->statut->GetStatut(200, "L'employé a été récupéré avec succès.");
                    $message = array("message" => $this->entete);
                    $employe = json_decode(json_encode($employe, true), true); // Transforme l'objet en tableau
                    $this->reponse = $message + $employe;
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
     * Récupère la collection de tous les employés et retourne la réponse HTTP correspondante.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function getCollection(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        //error_log("controllerGroupe->getCollection");
        $employes = $this->repositoryemploye->lireTousLesEmployes($this->idEmploye, $this->estSuperviseur); // Récupère tous les employés
        // Vérifie qu'au moins un employé aété récupéré
        if (count($employes) == 0) {
            $this->entete = $this->statut->GetStatut(403, "Vous n'avez pas les privilèges nécessaires pour accéder à la liste des employés, car tous ont été désactivés."); 
            $this->reponse = array("message" => $this->entete);
            return $this->encoder($psrResponse->withStatus(403), $this->reponse);
        }
        $this->entete = $this->statut->GetStatut(200, "Les employés ont été récupérés avec succès.");
        $message = array("message" => $this->entete);
        $employes = array("employes" => $employes);
        $this->reponse = $message + $employes;
        // Retourne la réponse HTTP encodée
        return $this->encoder($psrResponse, $this->reponse);
    }

    /**
     * Ajoute un employé à partir des données reçues en POST et retourne la réponse HTTP correspondante.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function postSingle(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        // Vérifie les droits d'accès (seul un superviseur peut ajouter)
        if (!($this->estSuperviseur)) {
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
        $modelAjout = json_decode(file_get_contents(dirname(__FILE__) . '/../models/model.ajout.employe.json'), true); // Modèle attendu
        $donneesAAjouter = $this->mapper($modelAjout, $donnees); // Mappe les données reçues
        if (count($donneesAAjouter) == 0 ) {
            // Données invalides
            return $this->encoder($psrResponse->withStatus(400), $this->reponse);
        }
        $pattern = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";
        // Vérifie le format de la date de naissance
        if (!(preg_match($pattern, $donneesAAjouter['dateNaissance']))) {
            $this->entete = $this->statut->GetStatut(400, "La date de naissance spécifiée doit être sous le format 'yyyy-mm-jj'."); 
            $this->reponse = array("message" => $this->entete);
            return $this->encoder($psrResponse->withStatus(400), $this->reponse);
        }
        else {
            $anneeEnCours = date("Y");
            $annee = explode('-', $donneesAAjouter['dateNaissance'])[0];
            // Vérifie l'âge de l'employé
            if (((int)$anneeEnCours - (int)$annee >= 18) && ((int)$anneeEnCours - (int)$annee <= 60 )) {
                if (!($this->valideDate($donneesAAjouter['dateNaissance'], 'Y-m-d'))) {
                    $this->entete = $this->statut->GetStatut(400, "La date de naissance spécifiée doit être une date valide."); 
                    $this->reponse = array("message" => $this->entete);
                    return $this->encoder($psrResponse->withStatus(400), $this->reponse);
                }
            }
            else {
                $this->entete = $this->statut->GetStatut(400, "Nous n'embauchons que des employés âgés entre 18 et 60 ans inclusivement."); 
                $this->reponse = array("message" => $this->entete);
                return $this->encoder($psrResponse->withStatus(400), $this->reponse);
            }
        }
        // Génération d'une clé aléatoire sécurisée de 32 caractères
        $cle = bin2hex(random_bytes(16));
        var_dump($cle);
        // Création d'un id de hash à partir de la clé
        $idHash = $cle[5].$cle[12].$cle[8].$cle[23].$cle[17];
        //var_dump($idHash);
        // Hashage de la clé pour stockage sécurisé
        $hash = password_hash($cle, PASSWORD_DEFAULT);
        //var_dump($hash);
        // Ajoute l'employé via le repository
        $employe = $this->repositoryemploye->AjouterUnEmploye($donneesAAjouter['prenomEmploye'], $donneesAAjouter['nomEmploye'], $donneesAAjouter['dateNaissance'], $donneesAAjouter['idSexe'], $donneesAAjouter['idPoste'], $donneesAAjouter['idRole'], $idHash, $hash, $this->idEmploye);
        $code = 201;
        if ($employe->id === 0) {
            // Erreur lors de l'ajout (dépendance inexistante)
            $code = 400;
            $this->entete = $this->statut->GetStatut(400, "Le sexe, le poste ou le role spécifié n'existe pas chez nous."); 
            $this->reponse = array("message" => $this->entete);
        }
        else {
            // Employé ajouté avec succès
            $this->entete = $this->statut->GetStatut(201, "L'employé a été créé avec succès");
            $message = array("message" => $this->entete);
            $employe = json_decode(json_encode($employe, true), true); // Transforme l'objet en tableau
            $this->reponse = $message + $employe;
        }
        // Retourne la réponse HTTP encodée
        return $this->encoder($psrResponse->withStatus($code), $this->reponse);
    }

    /**
     * Désactive ou réactive un employé selon son identifiant et retourne la réponse HTTP correspondante.
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
            // Appeler la fonction DesactiverUnEmploye du repository
            $employe = $this->repositoryemploye->DesactiverUnEmploye($arguments['id'], $this->idEmploye);
            if ($employe->id === 0) {
                // Employé non trouvé
                $code = 404;
                $this->entete = $this->statut->GetStatut(404, "L'employé spécifié n'existe pas."); 
                $this->reponse = array("message" => $this->entete);
            }
            else {
                // Employé désactivé ou réactivé
                if ($employe->actif === 0){
                    $this->entete = $this->statut->GetStatut(200, "L'employé a bel et bien été désactivé."); 
                }
                else { $this->entete = $this->statut->GetStatut(200, "L'employé a bel et bien été réactivé."); }
                $message = array("message" => $this->entete);
                $employe = json_decode(json_encode($employe, true), true); // Transforme l'objet en tableau
                $this->reponse = $message + $employe;
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
