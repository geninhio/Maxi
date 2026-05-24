<?php

// Dépendances au framework Slim
use Psr\Http\Message\ResponseInterface      as PsrResponse;
use Psr\Http\Message\ServerRequestInterface as PsrRequest;

// Dépendance 
require_once(dirname(__FILE__) . '/../repositories/repository.employe.php');

class Controller
{
    protected ?RepositoryEmploye $repositoryemploye = null;
    protected statut $statut;
    protected array $entete;
    protected array $reponse;
    protected int $idEmploye;
    protected bool $estSuperviseur;

    /**
     * Authentifie la requête avant de la traiter et redirige vers la bonne méthode du contrôleur.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @param array $arguments Arguments de la route.
     * @return PsrResponse Réponse HTTP encodée ou redirigée.
     */
    public function authenticate(PsrRequest $psrRequest, PsrResponse $psrResponse, array $arguments)
    {
        $connexionBD = $psrRequest->getAttribute('requete'); // Récupère la connexion à la base
        if ($this->repositoryemploye == null) {
            $this->repositoryemploye = new RepositoryEmploye($connexionBD); // Instancie le repository employé
        }
        if (!($psrRequest->hasHeader('Authorization'))) {
            // Vérifie la présence du header d'authentification
            $this->entete = $this->statut->GetStatut(400, "Veuillez vous authentifier en envoyant votre clé d'authentification dans l'en-tête 'Authentification' de votre requête."); 
            $this->reponse = array("message" => $this->entete);
            return $this->encoder($psrResponse->withStatus(400), $this->reponse);            
        }
        $headerValeurAuthentification = $psrRequest->getHeader('Authorization'); // Récupère la valeur du header
        if ($headerValeurAuthentification[0] == "") {
            // Clé vide
            return $this->throwUnAuthorized($psrRequest, $psrResponse);
        }
        else{
            $cle = $headerValeurAuthentification[0]; // Récupère la clé
            if (strlen($cle) != 32) {
                // Vérifie la longueur de la clé
                return $this->throwUnAuthorized($psrRequest, $psrResponse);
            }
            $idHash = $cle[5].$cle[12].$cle[8].$cle[23].$cle[17]; // Génère l'id hash
            $employe = $this->repositoryemploye->lireUnEmployeAvecSonHash($idHash); // Récupère l'employé
            if ($employe['id_employes'] == 0) {
                // Employé non trouvé
                return $this->throwUnAuthorized($psrRequest, $psrResponse);
            }
            if (!(password_verify($cle, $employe['hash']))) {
                // Vérifie le hash
                return $this->throwUnAuthorized($psrRequest, $psrResponse);
            }
            if ($employe['actif'] == 0) {
                // Employé désactivé
                return $this->throwForbidden($psrRequest, $psrResponse);
            }
            $this->idEmploye = $employe['id_employes']; // Stocke l'id employé
            if ($employe['id_role'] == 1) {
                $this->estSuperviseur = true; // Détermine le rôle
            }
            else{ $this->estSuperviseur = false; }
            //var_dump($this->estSuperviseur);
            $methode = $psrRequest->getMethod(); // Récupère la méthode HTTP
            switch($methode)
            {
                case "GET":
                    if (isset($arguments["id"])) {
                        return $this->getSingle($psrRequest, $psrResponse, $arguments); // Récupère un élément
                    }
                    else {
                        return $this->getCollection($psrRequest, $psrResponse); // Récupère la collection
                    }
                case "POST":
                    return $this->postSingle($psrRequest, $psrResponse); // Ajoute un élément
                case "DELETE":
                    return $this->deleteSingle($psrRequest, $psrResponse, $arguments); // Supprime un élément
                default:
                    break;    
            }
        }
    }

    /**
     * Encode la réponse en JSON avant de la retourner.
     *
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @param mixed $contenu Contenu à encoder.
     * @return PsrResponse Réponse HTTP encodée.
     */
    protected function encoder(PsrResponse $psrResponse, $contenu)
    {
        // Créer une réponse json contenant le array de données
        $psrResponse->getBody()->write(json_encode($contenu, JSON_UNESCAPED_UNICODE));
        return $psrResponse->withHeader('Content-type', 'application/json');
    }

    /**
     * Décode le body (json) reçu lors d'une requête avec paramètres.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param Methodes $methode Méthode HTTP utilisée.
     * @return mixed Données décodées.
     */
    protected function decoder(PsrRequest $psrRequest, Methodes $methode)
    {
        switch($methode)
        {
            case Methodes::post:
                return json_decode($psrRequest->getBody()->getContents(), true); // Décode le JSON
            default:
                break;    
        }
    }

    /**
     * Retourne une réponse HTTP 400 Bad Request.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function throwBadRequest(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        $this->entete = $this->statut->GetStatut(400); 
        $this->reponse = array("message" => $this->entete);
        return $this->encoder($psrResponse->withStatus(400), $this->reponse);
    }

    /**
     * Retourne une réponse HTTP 404 Not Found.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function throwNotFound(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        $this->statut = new statut();
        $this->entete = $this->statut->GetStatut(404); 
        $this->reponse = array("message" => $this->entete);
        return $this->encoder($psrResponse->withStatus(400), $this->reponse);
    }

    /**
     * Retourne une réponse HTTP 405 Method Not Allowed.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function throwMethodNotAllowed(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        $this->entete = $this->statut->GetStatut(405); 
        $this->reponse = array("message" => $this->entete);
        return $this->encoder($psrResponse->withStatus(405), $this->reponse);
    }

    /**
     * Retourne une réponse HTTP 401 Unauthorized.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function throwUnAuthorized(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        $this->entete = $this->statut->GetStatut(401); 
        $this->reponse = array("message" => $this->entete);
        return $this->encoder($psrResponse->withStatus(401), $this->reponse);
    }

    /**
     * Retourne une réponse HTTP 403 Forbidden.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP encodée.
     */
    public function throwForbidden(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        $this->entete = $this->statut->GetStatut(403, "Vous avez été viré de cette entreprise, vous n'êtes plus autorisé à éffectuer la moindre action."); 
        $this->reponse = array("message" => $this->entete);
        return $this->encoder($psrResponse->withStatus(403), $this->reponse);
    }

    /**
     * Mappe les données reçues avec le modèle attendu pour valider et formater les entrées.
     *
     * @param array $modelAjout Modèle attendu.
     * @param array $donneesRecues Données reçues à mapper.
     * @return array Données mappées ou tableau vide en cas d'erreur.
     */
    protected function mapper(array $modelAjout, array $donneesRecues)
    {
        // Parcourt chaque clé du modèle attendu
        foreach ($modelAjout as $cle => $valeur) {

            // Vérifie que la clé existe dans les données reçues
            if (array_key_exists($cle, $donneesRecues)) {
                
                // Vérifie que le type de la valeur correspond
                if (gettype($valeur) == gettype($donneesRecues[$cle])) {

                    // Si la valeur attendue est un tableau
                    if (gettype($valeur) == "array") {

                        // Cas où le modèle attend une liste
                        if (array_key_exists(0, $modelAjout[$cle]))
                        {
                            // Vérifie que la donnée reçue est aussi une liste
                            if (array_key_exists(0, $modelAjout[$cle]) && array_key_exists(0, $donneesRecues[$cle])) {
                                // Pour chaque élément de la liste reçue, on mappe récursivement
                                // var_dump($donneesRecues[$cle]);
                                for ($i=0; $i < count($donneesRecues[$cle]); $i++) {
                                    
                                    $donnee = $this->mapper($modelAjout[$cle][$i], $donneesRecues[$cle][$i]);
                                    // Si le mapping est valide, on ajoute à la liste
                                    if (!($donnee == array())) {
                                        array_push($modelAjout[$cle], $donnee);
                                    }
                                    else{
                                        // Si la liste est vide ou invalide, on retourne un tableau vide
                                        if (count($modelAjout[$cle]) < 2) {
                                            return array();
                                        } 
                                    }
                                }
                                // On retire l'élément d'exemple du modèle
                                array_splice($modelAjout[$cle], 0, 1);  
                            }
                            else {
                                // Erreur : la donnée reçue n'est pas une liste
                                $this->entete = $this->statut->GetStatut(400, "Dans le Json du corps de la requête, la valeur correspondante à la clé '".$cle."' doit être une liste"); 
                                $this->reponse = array("message" => $this->entete);
                                return array();
                            }
                        }

                        // Cas où le modèle attend un objet JSON
                        if (!(array_key_exists(0, $modelAjout[$cle])))
                        {
                            if (!(array_key_exists(0, $modelAjout[$cle])) && !(array_key_exists(0, $donneesRecues[$cle]))) 
                            {
                                // Mapping récursif pour un objet JSON
                                $resultat = $this->mapper($modelAjout[$cle], $donneesRecues[$cle]);
                                if ($resultat == array()) {
                                    return $resultat;
                                }
                                else{
                                    $modelAjout[$cle] = $resultat;
                                }
                            }
                            else {
                                // Erreur : la donnée reçue n'est pas un objet JSON
                                $this->entete = $this->statut->GetStatut(400, "Dans le Json du corps de la requête, la valeur correspondante à la clé '".$cle."' doit être un Json"); 
                                $this->reponse = array("message" => $this->entete);
                                return array();
                            }
                        }
                                                
                    }
                    else {
                        // Si la valeur attendue est une chaîne, elle ne doit pas être vide
                        if (gettype($donneesRecues[$cle]) == "string" && $donneesRecues[$cle] == "") {
                            $this->entete = $this->statut->GetStatut(400, "Dans le Json du corps de la requête, la valeur correspondante à la clé '".$cle."' ne doit pas être une chaîne de caractère vide."); 
                            $this->reponse = array("message" => $this->entete);
                            return array();
                        }
                        // On mappe la valeur reçue dans le modèle
                        $modelAjout[$cle] = $donneesRecues[$cle];
                    }
                    
                }
                else {
                    // Erreur de type
                    $type = gettype($valeur);
                    $this->entete = $this->statut->GetStatut(400, "Dans le Json du corps de la requête, la valeur correspondante à la clé '".$cle."' doit être de type '".$type."'"); 
                    $this->reponse = array("message" => $this->entete);
                    return array();
                }
            }
            else {
                // Erreur : clé manquante dans le JSON reçu
                $this->entete = $this->statut->GetStatut(400, "Le Json du corps de la requête doit contenir la clé '".$cle."'"); 
                $this->reponse = array("message" => $this->entete);
                return array();
            }
        }
        // Retourne le modèle mappé si tout est valide
        return $modelAjout;
    }

    /**
     * Vérifie si une date est valide selon le format donné.
     *
     * @param string $date Date à vérifier.
     * @param string $format Format attendu (par défaut 'Y-m-d H:i:s').
     * @return bool Vrai si la date est valide, faux sinon.
     */
    function valideDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date); // Crée un objet DateTime
        return $d && $d->format($format) == $date; // Vérifie la validité
    }
}

// Enumération des méthodes HTTP supportées

enum Methodes
{
    case get;
    case post;
    case delete;
}