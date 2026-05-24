<?php

// Dépendances
require_once(dirname(__FILE__) . '/../../config.bd.include.php');

/**
 * Classe qui réunie les fonctions et variables necéssaires au bon-fonctionnement des requêtes sur la base de données MariaDB.
 * 
 * 
 */
class ConnexionBD
{
	// Instance PDO de la base de données
	private PDO $connexion;
	/**
	 * Constructeur de la classe qui s'occupe d'instancier une connexion PDO à la base de données MariaDB.
	 *
	 * @param string $utilisateur Nom d'utilisateur pour la connexion.
	 * @param string $mdp Mot de passe pour la connexion.
	 * @return PDO|null Retourne l'objet PDO ou null en cas d'erreur.
	 */
	public function __construct(string $utilisateur, string $mdp)
	{
		try 
		{
            $chaineConnexion = "mysql:dbname=".BDSCHEMA.";host=".BDSERVEUR.";port=3306;charset=utf8mb4"; // Chaîne de connexion PDO
            $connexion = new PDO($chaineConnexion, $utilisateur, $mdp); // Instancie PDO
			$this->connexion = $connexion; // Stocke la connexion dans l'attribut
            return $connexion; // Retourne la connexion
		} 
		catch (Exception $exception) 
		{
			error_log($exception); // Log l'erreur en cas d'échec
		} 		
	}

	/**
	 * Prépare une requête SQL via PDO.
	 *
	 * @param string $requete La requête SQL à préparer.
	 * @return PDOStatement Objet PDOStatement prêt à l'exécution.
	 */
	public function prepare(string $requete)
	{
		return $this->connexion->prepare($requete); // Prépare la requête SQL
	}
	
	/**
	 * Démarre une transaction sur la connexion PDO.
	 *
	 * @return bool Vrai si la transaction démarre, faux sinon.
	 */
	public function beginTransaction()
	{
		return $this->connexion->beginTransaction(); // Démarre la transaction
	}

	/**
	 * Annule la transaction en cours.
	 *
	 * @return bool Vrai si la transaction est annulée, faux sinon.
	 */
	public function rollBack()
	{
		return $this->connexion->rollBack(); // Annule la transaction
	}

	/**
	 * Valide la transaction en cours.
	 *
	 * @return bool Vrai si la transaction est validée, faux sinon.
	 */
	public function commit()
	{
		return $this->connexion->commit(); // Valide la transaction
	}
}