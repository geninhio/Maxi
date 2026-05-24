<?php

class ModelMarque
{
    public int $id;
    public string $nom;
    
    public function __construct(int $idmarque, string $nom)
    {
        $this->id = $idmarque;
        $this->nom = $nom;
    }
    
}