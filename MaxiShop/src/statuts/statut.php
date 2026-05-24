<?php 

    // Classe gérant les statuts de réponse HTTP
    class statut 
    {
        /**
         * Récupère le statut de réponse en fonction du code HTTP et d'un message facultatif.
         * 
         * @param int $code Code HTTP de réponse.
         * @param string $message Message de réponse (facultatif).
         * @return string Réponse au format JSON contenant le code, le statut et le message.
         */
        function GetStatut(int $code, string $message = "")
        {
            // Initialisation de la variable de statut
            $statut = "";

            // Détermination du statut en fonction du code HTTP
            switch ($code) {
                // Code 200 : OK
                case 200:
                    $statut = "OK";
                    // Message par défaut si aucun message n'est fourni
                    if($message == "") $message = "L'inventaire du magasin a été récupéré avec succès";
                    break;
                
                // Code 201 : Created
                case 201:
                    $statut = "Created";
                    // Message par défaut si aucun message n'est fourni
                    if($message == "") $message = "Achat effectué avec succès, merci d'avoir magasiné chez NikiShop";
                    break;

                // Code 400 : Bad Request
                case 400:
                    $statut = "Bad Request";
                    // Message par défaut si aucun message n'est fourni
                    if($message == "") $message = "La requète n'est pas valide et n'a donc pas pu être traitée";
                    break;

                // Code 401 : Unauthorized
                case 401:
                    $statut = "Unauthorized";
                    // Message par défaut si aucun message n'est fourni
                    if($message == "") $message = "Vous n'êtes pas autorisé(e)";
                    break;

                // Code 403 : Forbidden
                case 403:
                    $statut = "Forbidden";
                    // Message par défaut si aucun message n'est fourni
                    if($message == "") $message = "Vous n'avez pas les permissions requises pour éffectuer cette action";
                    break;

                // Code 404 : Not Found
                case 404:
                    $statut = "Not Found";
                    // Message par défaut si aucun message n'est fourni
                    if($message == "") $message = "La ressource demandée n'a pas été trouvée";
                    break;

                // Code 405 : Method Not Allowed
                case 405:
                    $statut = "Method Not Allowed";
                    // Message par défaut si aucun message n'est fourni
                    if($message == "") $message = "La méthode utilisée n'est pas authorisée pour cette action";
                    break;

                // Code par défaut : 500 Internal Server Error
                default:
                    $code = 500;
                    $statut = "Internal Error Server";
                    $message = "Une erreur inattendue est survenue";
                    break;
            }

            // Définition du code de réponse HTTP
            http_response_code($code);

            // Retourne la réponse au format JSON
            return array(
                "code"=>$code,
                "statut"=>$statut,
                "message"=>$message
            );
        }
    }
    
?>