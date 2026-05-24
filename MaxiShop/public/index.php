<?php

try {
    
    $app = (require_once(dirname(__FILE__) . '/../src/app.php'));

    // Exécuter le contenu du fichier app
    if ($app != null)
        $app->run();
}   
catch (Exception $e)
{
    require_once(dirname(__FILE__) . '/../src/statuts/statut.php');
    header('Content-Type: application/json');
    $statut = new statut();
    echo json_encode($statut->GetStatut(500, "Une erreur interne est survenue."));
    error_log($e->getMessage());
}
