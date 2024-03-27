<?php
// Inclure le fichier de configuration des logs
require_once('Config.php');
require_once('fonction_check_connexion.php');
require_once('fonction_fermer_ticket.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check = checkLoggedIn();
    if ($check == true) {
        if (isset($_POST['Id'], $_POST['justification'])) {
            $ticket_id = $_POST['Id'];
            $justification = $_POST['justification'];
            closeTicket($ticket_id, $justification);
        } else {
            logMessage("ID du ticket ou justification non spécifiée.", 'error');
            $_SESSION['message'] = "ID du ticket ou justification non spécifiée.";
            $_SESSION['couleur'] = false;
        }
    }
} else {
    logMessage("Requête non autorisée.", 'error');
    $_SESSION['message'] = "Requête non autorisée.";
    $_SESSION['couleur'] = false;
}

header('Location: ../PHP/authentification.php');
exit();
?>
