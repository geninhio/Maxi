<?php

class ModelPanier
{
    public int $id;
    public string $dateCreation;
    public array $produits;
    public ModelEmploye $employe;
    public int $actif;
    
    public function __construct(int $id, string $dateCreation, array $produits, ModelEmploye $employe, int $actif)
    {
        $this->id = $id;
        $this->dateCreation = $dateCreation;
        $this->produits = $produits;
        $this->employe = $employe;
        $this->actif = $actif;    
    }
    
}