<?php

// Dépendances
require_once(dirname(__FILE__) . '/../models/model.role.php');
require_once(dirname(__FILE__) . '/_repository.php');

class RepositoryRole extends _repository
{


    private function modeliser(array $donnees)
    {
        return new ModelRole(
                $donnees["id_role"],
                $donnees["nom"]
        );
    }

    public function lireUnRole(int $idRole)
    {
        $donnee = null;

        $pdoRequete = $this->connexion->prepare("CALL Select_role(:id)");

        $pdoRequete->bindParam(":id",$idRole,PDO::PARAM_STR);

        $pdoRequete->execute();

        $role = $pdoRequete->fetch(PDO::FETCH_ASSOC);

        if ($role == FALSE) {

            $donnee = array('id_role' => 0,
                            'nom' => 'rien');
        }
        else {
            $donnee = $role;
        }

        return $this->modeliser($donnee);

    }
    
}
