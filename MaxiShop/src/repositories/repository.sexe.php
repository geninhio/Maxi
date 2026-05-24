<?php

// Dépendances
require_once(dirname(__FILE__) . '/../models/model.sexe.php');
require_once(dirname(__FILE__) . '/_repository.php');

class RepositorySexe extends _repository
{


    private function modeliser(array $donnees)
    {
        return new ModelSexe(
                $donnees["id_sexe"],
                $donnees["nom"]
        );
    }

    public function lireUnSexe(int $idSexe)
    {
        $donnee = null;

        $pdoRequete = $this->connexion->prepare("CALL Select_sexe(:id)");

        $pdoRequete->bindParam(":id",$idSexe,PDO::PARAM_STR);

        $pdoRequete->execute();

        $sexe = $pdoRequete->fetch(PDO::FETCH_ASSOC);

        if ($sexe == FALSE) {

            $donnee = array('id_sexe' => 0,
                            'nom' => 'rien');
        }
        else {
            $donnee = $sexe;
        }

        return $this->modeliser($donnee);

    }
    
}
