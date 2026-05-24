<?php

class ModelDepartement
{
    public int $id;
    public string $nom;
    
    public function __construct(int $iddepartement, string $nom)
    {
        $this->id = $iddepartement;
        $this->nom = $nom;
    }
    
}