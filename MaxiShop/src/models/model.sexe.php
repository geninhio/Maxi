<?php

class ModelSexe
{
    public int $id;
    public string $nom;
    
    public function __construct(int $idsexe, string $nom)
    {
        $this->id = $idsexe;
        $this->nom = $nom;
    }
    
}