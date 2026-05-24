<?php

class ModelRole
{
    public int $id;
    public string $nom;
    
    public function __construct(int $idrole, string $nom)
    {
        $this->id = $idrole;
        $this->nom = $nom;
    }
    
}