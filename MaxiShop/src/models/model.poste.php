<?php

class ModelPoste
{
    public int $id;
    public string $nom;
    public ModelDepartement $departement;
    
    public function __construct(int $idposte, string $nom, ModelDepartement $departement)
    {
        $this->id = $idposte;
        $this->nom = $nom;
        $this->departement = $departement;
    }
    
}