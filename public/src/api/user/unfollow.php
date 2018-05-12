<?php
/**
 * Supprime un abonnement
 * Méthode : GET
 * Paramètres :
 * - identifier : l'ID ou le nom d'utilisateur de l'utilisateur dont on veut se désabonner
 * Renvoie :
 * - status = error si l'utilisateur n'est pas trouvé
 * - status = success, <User sérialisé> sinon
 */

require_once("../../config.php");

if (isset($_GET['identifier']))
{
    $identifier = $_GET['identifier'];
}
else
    error_die("identifier", ERROR_FieldMissing);

$user = verify_logged_in();

try
{
    $u = User::findWithIDorUsername($identifier);
}
catch (UserNotFoundException $e)
{
    error_die($e->getMessage(), ERROR_NotFound);
}

$user->unfollow($u);
success_die($u);