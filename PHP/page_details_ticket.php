<?php
session_start();
require 'fonction_get_session.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page détails ticket</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="../CSS/style_user.css" rel="stylesheet">
    <link href="../CSS/style_volet_information.css" rel="stylesheet">
    <link href="../CSS/style_page_adm_traitement_ticket.css" rel="stylesheet">
    <link href="../CSS/style_page_details_ticket.css" rel="stylesheet">
</head>
<body>
<div class="page">
    <nav>
        <ul>
            <li class="logo"><img alt="logo de Rayan Ticket" src="../IMG/Proposition_logo_1.png"></li>
            <li>
                <i><h3 class="role">Role: <?php echo $_SESSION['user_role']; ?></h3></i>
            </li>
            <li>
                <a href="authentification.php"><i class="fa fa-home"></i> &nbsp; Accueil</a>
            </li>
            <li>
                <?php
                if (getRole() == "utilisateur") {
                    echo "<a href='form_creation_ticket.php'><i class='fa fa-plus-circle'></i> &nbsp; Créer un ticket</a>";
                } elseif (getRole() == "technicien") {
                    echo "<a href='page_technicien.php#tickets-disponible'><i class='fa fa-list-ul'></i> &nbsp; Tickets disponible</a>";
                } elseif (getRole() == "admin_web") {
                    echo "<a href='form_creation_technicien.php'><i class='fa fa-plus-circle'></i> &nbsp; Création compte technicien</a>";
                }

                ?>
            </li>
            <li>
                <?php
                if(getRole() == "utilisateur") {
                    echo "<a href='utilisateur.php#hisorique-ticket'><i class='fa fa-ticket'></i> &nbsp; Historique de ticket</a>";
                } elseif (getRole() == "technicien") {
                    echo "<a href='page_technicien.php#tickets-attribues'><i class='fa fa-ticket'></i> &nbsp; Tickets attribués</a>";
                } elseif (getRole() == "admin_web") {
                    echo "<a href='page_adm_web_traitement_ticket.php'><i class='fa fa-cogs'></i> &nbsp; Traitement ticket</a>";
                }
                ?>
            </li>
            <?php
            if (getRole() == "admin_web") {
                echo "<li><a href='page_creation_libelle.php'><i class='fa fa-tag'></i> &nbsp; Gestion libellé</a></li>
                      <li><a href='#tickets-ouverts'><i class='fa fa-ticket'></i> &nbsp; Tickets Ouverts</a></li>";
            }
            ?>
            <li>
                <a href="profil.php"><i class="fa fa-user"></i> &nbsp; Profil</a>
            </li>
            <a class ="deconnexion" href="../PHP/Deconnexion.php" class="bouton"><i class="fa fa-sign-out"></i> Déconnexion</a>
        </ul>
    </nav>
    <div class="corps">
        <main>
            <?php
            $table = "tickets";
            $role = getRole();

            $connection = connectToDatabase();

            if (isset($_GET['id'])) {
                $ticket_id = $_GET['id'];
                $query = "SELECT t.id_ticket as Id, t.Login as 'Crée par', lt.libelle as Libelle, t.priorite as 'Niveau urgence', DATE_FORMAT(t.date_creation, '%d/%m/%Y') as 'date_creation', t.statut as Statut , t.technicien as Technicien, description
                    FROM $table t
                    LEFT JOIN libelle_ticket lt ON t.id_libelle = lt.id_libelle
                    WHERE id_ticket = ?";
                $stmt = mysqli_prepare($connection, $query);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $ticket_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($result && $row = mysqli_fetch_assoc($result)) {
                        echo "<h2>Détails du Ticket</h2><hr>";
                        echo "<p><strong>ID du Ticket :</strong> {$row['Id']}</p><hr>";
                        echo "<p><strong>Créé par :</strong> {$row['Crée par']}</p><hr>";
                        echo "<p><strong>Sujet :</strong> {$row['Libelle']}</p><hr>";
                        echo "<p><strong>Niveau urgence :</strong> {$row['Niveau urgence']}</p><hr>";
                        echo "<p><strong>Date de création :</strong> {$row['date_creation']}</p><hr>";
                        echo "<p><strong>Statut :</strong> {$row['Statut']}</p><hr>";
                        echo "<p><strong>Technicien en charge :</strong> {$row['Technicien']}</p><hr>";
                        echo "<p><strong>Description :</strong></p>";
                        echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;'>";
                        echo nl2br($row['description']);
                        echo "</div>";

                        if (($role == "technicien" || $role == "admin_web") && ($row['Statut'] == "Ouvert" || $row['Statut'] == "En cours")) {
                            echo "<form method='post' action='action_fermer_ticket.php'>";
                            echo "<input type='hidden' name='Id' value='{$row['Id']}'>";
                            echo "<textarea name='justification' placeholder='Détail des tâches réalisées' required></textarea><br>";
                            echo "<input type='submit' value='Clôturer le Ticket'>";
                            echo "</form>";
                        } elseif ($role == "utilisateur" && ($row['Statut'] == "Ouvert" || $row['Statut'] == "En cours")) {
                            echo "<form method='post' action='action_fermer_ticket.php'>";
                            echo "<input type='hidden' name='Id' value='{$row['Id']}'>";
                            echo "<textarea name='justification' placeholder='Raison de la fermeture du ticket' required></textarea><br>";
                            echo "<input type='submit' value='Fermer le Ticket'>";
                            echo "</form>";
                        } elseif (($role == "technicien" || $role == "admin_web" || $role == "utilisateur") && $row['Statut'] == "Fermé") {
                            echo "<form method='post' action='action_reouvrir_ticket.php'>";
                            echo "<input type='hidden' name='Id' value='{$row['Id']}'>";
                            echo "<textarea name='justification' placeholder='Raison de la réouverture du ticket' required></textarea><br>";
                            echo "<input type='submit' value='Réouvrir le Ticket'>";
                            echo "</form>";
                        } else {
                            echo "<p style='color: red; font-weight: bold;'>Vous n'êtes pas autorisé à modifier le statut du ticket.</p>";
                        }
                    } else {
                        echo "Aucun ticket trouvé avec l'ID spécifié.";
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    echo "Erreur lors de la préparation de la requête.";
                }
            } else {
                echo "ID du ticket non spécifié.";
            }

            mysqli_close($connection);
            ?>

            <h2>Historique des Actions</h2>
            <hr>

            <table class="action-table">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Personne</th>
                    <th>Justification</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // Connexion à la base de données
                $connection = connectToDatabase();

                $query = "SELECT at.date_action, u.login AS personne, at.justification 
                          FROM actions_tickets at
                          JOIN user u ON at.id_utilisateur = u.id_user
                          WHERE at.id_ticket = ?";
                $stmt = mysqli_prepare($connection, $query);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $ticket_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($result && mysqli_num_rows($result) > 0) {
                        // Affichage de chaque ligne de l'historique des actions
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$row['date_action']}</td>";
                            echo "<td>{$row['personne']}</td>";
                            echo "<td>{$row['justification']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Aucune action trouvée pour ce ticket
                        echo "<tr><td colspan='3'>Aucune action trouvée pour ce ticket.</td></tr>";
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    echo "<tr><td colspan='3'>Erreur lors de la préparation de la requête.</td></tr>";
                }

                // Fermeture de la connexion à la base de données
                mysqli_close($connection);
                ?>
                </tbody>
            </table>
        </main>
    </div>
</div>
</body>
</html>
