<?php

class ModelEmploye
{
    public int $id;
    public string $prenom;
    public string $nom;
    public string $dateNaissance;
    public ModelSexe $sexe;
    public ModelRole $role;
    public ModelPoste $poste;
    public int $actif;
    
    public function __construct(int $id, string $prenom, string $nom, string $dateNaissance, ModelSexe $sexe, ModelRole $role, ModelPoste $poste, int $actif)
    {
        $this->id = $id;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->dateNaissance = $dateNaissance;
        $this->sexe = $sexe;
        $this->role = $role;
        $this->poste = $poste;
        $this->actif = $actif;    
    }
    
}