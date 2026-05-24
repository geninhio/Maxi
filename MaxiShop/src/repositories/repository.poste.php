<?php

// Dépendances
require_once(dirname(__FILE__) . '/../models/model.poste.php');
require_once(dirname(__FILE__) . '/../models/model.departement.php');
require_once(dirname(__FILE__) . '/_repository.php');

class RepositoryPoste extends _repository
{


    private function modeliser(array $donnees)
    {
        $departement = new ModelDepartement($donnees["id_departement"], $donnees["nom_departement"]);

        return new ModelPoste(
                $donnees["id_poste"],
                $donnees["nom"],
                $departement
        );
    }

    public function lireUnPoste(int $idPoste)
    {
        $donnee = null;

        $pdoRequete = $this->connexion->prepare("CALL Select_poste(:id)");

        $pdoRequete->bindParam(":id",$idPoste,PDO::PARAM_STR);

        $pdoRequete->execute();

        $poste = $pdoRequete->fetch(PDO::FETCH_ASSOC);

        if ($poste == FALSE) {

            $donnee = array('id_poste' => 0,
                            'id_departement' => 0,
                            'nom' => 'rien',
                            'nom_departement' => 'rien');
        }
        else {
            $donnee = $poste;
        }

        return $this->modeliser($donnee);

    }
    
}
