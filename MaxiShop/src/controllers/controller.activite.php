<?php

// Dépendances au framework Slim
use Psr\Http\Message\ResponseInterface      as PsrResponse;
use Psr\Http\Message\ServerRequestInterface as PsrRequest;

// Dépendance 
require_once(__DIR__ . '/../statuts/statut.php');

class ControllerActivite extends Controller
{
    
    private RepositoryActivite $repositoryActivite;


    /**
     * Constructeur du contrôleur activité.
     * Initialise le repository et le statut.
     *
     * @param RepositoryActivite $repositoryActivite Instance du repository activité.
     * @return void
     */
    public function __construct(RepositoryActivite $repositoryActivite)
    {
        $this->repositoryActivite = $repositoryActivite; // Stocke le repository
        $this->statut = new statut(); // Initialise le statut
    }

    /**
     * Génère le journal des activités sous forme de tableau HTML.
     *
     * @return string Tableau HTML des activités.
     */
    public function Journal()
    {
        $htmlStyle = "<style>
                        table {
                        border-collapse: collapse;
                        width: 100%;
                        }

                        tr, td {
                        border: 1px solid black; /* Définit une bordure noire de 1px d'épaisseur */
                        padding: 8px;
                        text-align: left;
                        }
                    </style>";

        $html = $htmlStyle."<table>
                    <tr>
                        <td>Employé</td>
                        <td>Date</td>
                        <td>Action</td>
                        <td>Message</td>
                    </tr>";

        $activites = $this->repositoryActivite->lireToutesLesActivites(); // Récupère toutes les activités

        if (count($activites) == 0) {
            $html .= "</table>"; // Si aucune activité, ferme le tableau
        }

        for ($i=0; $i < count($activites); $i++) { 
            if ($i != count($activites) - 1) {
                // Ajoute une ligne pour chaque activité sauf la dernière
                $html .= "<tr>
                        <td>".$activites[$i]['Employé']."</td>
                        <td>".$activites[$i]['Date']."</td>
                        <td>".$activites[$i]['Action']."</td>
                        <td>".$activites[$i]['Message']."</td>
                    </tr>";
            }
            else {
                // Ajoute la dernière ligne et ferme le tableau
                $html .= "<tr>
                        <td>".$activites[$i]['Employé']."</td>
                        <td>".$activites[$i]['Date']."</td>
                        <td>".$activites[$i]['Action']."</td>
                        <td>".$activites[$i]['Message']."</td>
                    </tr></table>";
            }
        }

        return $html; // Retourne le HTML généré

    }

    /**
     * Génère le tableau des habitudes d'activité des employés (consultations, ajouts, suppressions).
     *
     * @return string Tableau HTML des habitudes d'activité.
     */
    public function Habitude()
    {
        $htmlStyle = "<style>
                        table {
                        border-collapse: collapse;
                        width: 100%;
                        }

                        tr, td {
                        border: 1px solid black; /* Définit une bordure noire de 1px d'épaisseur */
                        padding: 8px;
                        text-align: left;
                        }
                    </style>";

        $html = $htmlStyle."<table>
                    <tr>
                        <td>Actions</td>
                        <td colspan='2'>Consultations</td>
                        <td colspan='2'>Ajouts</td>
                        <td colspan='2'>Suppression</td>
                    </tr>
                    <tr>
                        <td>Employés</td>
                        <td>Préc</td><td>Cour</td>
                        <td>Préc</td><td>Cour</td>
                        <td>Préc</td><td>Cour</td>
                    </tr>";
        // Calcule les dates des semaines précédente et suivante
        if (date("l") == "Sunday") {
            $semainePrecedente = array(date("Y-m-d H:i:s", strtotime("-1 week last Monday")), date("Y-m-d H:i:s", strtotime("-1 week last Sunday")));
            $semaineSuivante = array(date("Y-m-d H:i:s", strtotime("+1 week last Monday")), date("Y-m-d H:i:s", strtotime("+1 week last Sunday")));
        }
        else {
            $semainePrecedente = array(date("Y-m-d H:i:s", strtotime("-1 week last Monday")), date("Y-m-d H:i:s", strtotime("-1 week next Sunday")));
            $semaineSuivante = array(date("Y-m-d H:i:s", strtotime("+1 week last Monday")), date("Y-m-d H:i:s", strtotime("+1 week next Sunday")));
        }

        // Récupère les activités pour les deux semaines
        $activitesSemainePrecedente = $this->repositoryActivite->compterActivitesTousLesEmployes($semainePrecedente[0], $semainePrecedente[1]);
        $activitesSemaineSuivante = $this->repositoryActivite->compterActivitesTousLesEmployes($semainePrecedente[0], $semaineSuivante[1]);

        for ($i=0; $i < count($activitesSemainePrecedente); $i++) { 
            if ($i != count($activitesSemainePrecedente) - 1) {
                // Ajoute une ligne pour chaque employé sauf le dernier
                $html .= "<tr>
                        <td>".$activitesSemainePrecedente[$i]['Employé']."</td>
                        <td>".$activitesSemainePrecedente[$i]['consultation']."</td><td>".$activitesSemaineSuivante[$i]['consultation']."</td>
                        <td>".$activitesSemainePrecedente[$i]['ajout']."</td><td>".$activitesSemaineSuivante[$i]['ajout']."</td>
                        <td>".$activitesSemainePrecedente[$i]['suppression']."</td><td>".$activitesSemaineSuivante[$i]['suppression']."</td>
                    </tr>";
            }
            else {
                // Ajoute la dernière ligne et ferme le tableau
                $html .= "<tr>
                        <td>".$activitesSemainePrecedente[$i]['Employé']."</td>
                        <td>".$activitesSemainePrecedente[$i]['consultation']."</td><td>".$activitesSemaineSuivante[$i]['consultation']."</td>
                        <td>".$activitesSemainePrecedente[$i]['ajout']."</td><td>".$activitesSemaineSuivante[$i]['ajout']."</td>
                        <td>".$activitesSemainePrecedente[$i]['suppression']."</td><td>".$activitesSemaineSuivante[$i]['suppression']."</td>
                    </tr></table>";
            }
        }

        return $html; // Retourne le HTML généré

    }

    /**
     * Génère l'audit complet (habitudes + journal) et retourne la réponse HTTP correspondante.
     *
     * @param PsrRequest $psrRequest Requête HTTP.
     * @param PsrResponse $psrResponse Réponse HTTP.
     * @return PsrResponse Réponse HTTP contenant l'audit en HTML.
     */
    public function getAudit(PsrRequest $psrRequest, PsrResponse $psrResponse)
    {
        $html1 = $this->Habitude(); // Génère le tableau des habitudes
        $html2 = $this->Journal(); // Génère le journal des activités

        $html = "<h2>Habitudes</h2>".$html1."<h2>Journal</h2>".$html2; // Concatène les deux tableaux

        $psrResponse->getBody()->write($html); // Écrit le HTML dans la réponse

        return $psrResponse->withHeader('Content-type', 'text/html'); // Retourne la réponse HTTP avec le bon header
    }
    
}

