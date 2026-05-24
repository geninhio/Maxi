<?php

// Dépendances au Framework Slim
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;


ini_set('display_errors', 1);

require __DIR__ . '/../../vendor/autoload.php';

// Dépendances aux controllers
require_once(dirname(__FILE__) . '/controllers/_controller.php');
require_once(dirname(__FILE__) . '/controllers/controller.produit.php');
require_once(dirname(__FILE__) . '/controllers/controller.employe.php');
require_once(dirname(__FILE__) . '/controllers/controller.activite.php');
require_once(dirname(__FILE__) . '/controllers/controller.panier.php');


// Dépendances aux repositories
require_once(dirname(__FILE__) . '/repositories/repository.activite.php');
require_once(dirname(__FILE__) . '/repositories/repository.produit.php');
require_once(dirname(__FILE__) . '/repositories/repository.employe.php');
require_once(dirname(__FILE__) . '/repositories/repository.panier.php');

// Autres dépendances
require_once(__DIR__ . '/../config.bd.include.php');
require_once(dirname(__FILE__) . '/connectors/Class.connexionBD.php');

$app = AppFactory::create();

$app->addRoutingMiddleware();

$connexionBD = new ConnexionBD(BDUTILISATEURECRIRE,mdp);

/**
 * Middleware qui ajoute la connexion BD à la requête.
 *
 * @param Request $request Requête HTTP.
 * @param RequestHandler $handler Handler de la requête.
 * @return ResponseInterface Réponse HTTP après traitement du handler.
 */
$middleware = function (Request $request, RequestHandler $handler) use ($connexionBD){

    $request = $request->withAttribute('requete', $connexionBD); // Ajoute la connexion BD à la requête
    $request = $handler->handle($request); // Passe la requête au handler suivant

    return $request; // Retourne la réponse
};

$app->group('/produits', function ($app) use ($connexionBD)
{
    // Références aux classes controller et repository
    $repositoryproduit = new RepositoryProduit($connexionBD);
    $controllerproduit = new ControllerProduit($repositoryproduit);

    // Methode pour toute requête sur la racine /produits (mauvaise requête)
    $app->any('/',              [$controllerproduit, "throwBadRequest"]);

    // Methodes non autorisées sur la racine /produits
    $app->map(['PUT', 'DELETE', 'PATCH'], '', [$controllerproduit, "throwMethodNotAllowed"]);
    // Methodes POST et GET authentifiées sur la racine /produits
    $app->map(['POST', 'GET'], '', [$controllerproduit, "authenticate"]);

    // Methodes non autorisées sur /produits/{id} pour POST, PUT, PATCH
    $app->map(['POST', 'PUT', 'PATCH'], '/{id}', [$controllerproduit, "throwMethodNotAllowed"]);
    // Methodes DELETE et GET authentifiées sur /produits/{id}
    $app->map(['DELETE', 'GET'], '/{id}', [$controllerproduit, "authenticate"]);


})->add($middleware);

$app->group('/employes', function ($app) use ($connexionBD)
{
    // Références aux classes controller et repository
    $repositoryemploye = new RepositoryEmploye($connexionBD);
    $controlleremploye = new ControllerEmploye($repositoryemploye);

    // Methode pour toute requête sur la racine /employes (mauvaise requête)
    $app->any('/',              [$controlleremploye, "throwBadRequest"]);

    // Methodes non autorisées sur la racine /employes
    $app->map(['PUT', 'DELETE', 'PATCH'], '', [$controlleremploye, "throwMethodNotAllowed"]);
    // Methodes POST et GET authentifiées sur la racine /employes
    $app->map(['POST', 'GET'], '', [$controlleremploye, "authenticate"]);


    // Methodes non autorisées sur /employes/{id} pour POST, PUT, PATCH
    $app->map(['POST', 'PUT', 'PATCH'], '/{id}', [$controlleremploye, "throwMethodNotAllowed"]);
    // Methodes DELETE et GET authentifiées sur /employes/{id}
    $app->map(['DELETE', 'GET'], '/{id}', [$controlleremploye, "authenticate"]);



})->add($middleware);

$app->group('/paniers', function ($app) use ($connexionBD)
{
    // Références aux classes controller et repository
    $repositorypanier = new RepositoryPanier($connexionBD);
    $controllerpanier = new ControllerPanier($repositorypanier);

    // Methode pour toute requête sur la racine / (mauvaise requête)
    $app->any('/',              [$controllerpanier, "throwBadRequest"]);

    // Methodes non autorisées sur la racine /paniers
    $app->map(['PUT', 'DELETE', 'PATCH'], '', [$controllerpanier, "throwMethodNotAllowed"]);
    // Methodes POST et GET authentifiées sur la racine /paniers
    $app->map(['POST', 'GET'], '', [$controllerpanier, "authenticate"]);

    // Methodes non autorisées sur /paniers/{id} pour POST, PUT, PATCH
    $app->map(['POST', 'PUT', 'PATCH'], '/{id}', [$controllerpanier, "throwMethodNotAllowed"]);
    // Methodes DELETE et GET authentifiées sur /paniers/{id}
    $app->map(['DELETE', 'GET'], '/{id}', [$controllerpanier, "authenticate"]);


})->add($middleware);

$app->group('/activites', function ($app) use ($connexionBD)
{
    // Références aux classes controller et repository
    $repositoryActivite = new RepositoryActivite($connexionBD);
    $controllerActivite = new ControllerActivite($repositoryActivite);

    // Methodes non autorisées sur la racine /activites
    $app->map(['POST', 'PUT', 'DELETE', 'PATCH'], '', [$controllerActivite, "throwMethodNotAllowed"]);
    // Methode GET pour récupérer l'audit des activités
    $app->get('', [$controllerActivite, "getAudit"]);


});


$controller = new Controller();
$app->any('{string:.*}', [$controller, "throwNotFound"]);


return $app;
