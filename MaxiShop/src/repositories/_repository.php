<?php

// Dépendances


class _repository
{

    protected ConnexionBD $connexion;

    
    public function __construct(ConnexionBD $connexion)
	{
		$this->connexion = $connexion;
	}

}
