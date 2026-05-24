<?php

// Dépendances
require_once(dirname(__FILE__) . '/../models/model.marque.php');
require_once(dirname(__FILE__) . '/_repository.php');

// Classe RepositoryMarque : gère les opérations liées aux marques (lecture, modélisation)
class RepositoryMarque extends _repository
{


    // Transforme un tableau associatif en objet ModelMarque
    private function modeliser(array $donnees)
    {
        return new ModelMarque(
                $donnees["id_marque"],
                $donnees["nom"]
        );
    }

    // Récupère une marque à partir de son identifiant
    public function lireUneMarque(int $idMarque)
    {
        $donnee = null;

        $pdoRequete = $this->connexion->prepare("CALL Select_marque(:id)");

        $pdoRequete->bindParam(":id",$idMarque,PDO::PARAM_STR);

        $pdoRequete->execute();

        $marque = $pdoRequete->fetch(PDO::FETCH_ASSOC);

        if ($marque == FALSE) {

            $donnee = array('id_marque' => 0,
                            'nom' => 'rien');
        }
        else {
            $donnee = $marque;
        }

        return $this->modeliser($donnee);

    }
    
}
