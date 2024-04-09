<?php
session_start();
require_once('fonction_get_session.php');
require_once('fonction_connexion_bd.php');

function reopenTicket($ticket_id, $justification) {

    $connection = connectToDatabase();

    $query = "UPDATE tickets SET statut = 'Ouvert' WHERE id_ticket = ?";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $ticket_id);
        mysqli_stmt_execute($stmt);

        // Enregistrement de l'action dans la table actions_tickets
        $action = "Réouverture";
        $username = $_SESSION['login'];
        $user_id = getUserIdByUsername($username); // Supposons que $_SESSION['user_id'] contienne l'ID de l'utilisateur
        $date_action = date("Y-m-d H:i:s"); // Date et heure actuelles

        $insert_query = "INSERT INTO actions_tickets (id_ticket, id_utilisateur, date_action, action, justification) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($connection, $insert_query);

        if ($stmt_insert) {
            mysqli_stmt_bind_param($stmt_insert, "iisss", $ticket_id, $user_id, $date_action, $action, $justification);
            mysqli_stmt_execute($stmt_insert);
            mysqli_stmt_close($stmt_insert);
        } else {
            logMessage("Erreur lors de la préparation de la requête d'insertion de l'action.", 'error');
            $_SESSION['message'] = "Erreur lors de la réouverture du ticket.";
            $_SESSION['couleur'] = false;
        }

        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        $_SESSION['message'] = "Le ticket a été réouvert avec succès.";
        $_SESSION['couleur'] = true;
    } else {
        logMessage("Erreur lors de la préparation de la requête de mise à jour du statut du ticket.", 'error');
        $_SESSION['message'] = "Erreur lors de la réouverture du ticket.";
        $_SESSION['couleur'] = false;
    }
}
?>
