<?php

class ModelProduit
{
    public int $id;
    public string $nom;
    public float $prix;
    public int $quantite;
    public ModelMarque $marque;
    public int $actif;
    
    public function __construct(int $idproduit, string $nom, float $prix, int $quantite, ModelMarque $marque, int $actif)
    {
        $this->id = $idproduit;
        $this->nom = $nom;
        $this->prix = $prix;
        $this->quantite = $quantite;
        $this->marque = $marque;
        $this->actif = $actif;    
    }
    
}