<?php
session_start();
require_once 'Config.php';
require 'fonction_creation_table.php';
if (!isset($_SESSION['login']) || $_SESSION['user_role'] !== 'admin_systeme') {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}
$host = "localhost";
$user = "root";
$password = "";
$database = "BD_Ticketing";
$table = "tickets";

$connection = mysqli_connect($host, $user, $password, $database) or die("Erreur de connexion à la base de données");

// Vérifie le type de tickets à afficher
$type = isset($_GET['type']) ? $_GET['type'] : 'ouverts';
if ($type == 'fermes') {
    $statut = "Fermé";
} else {
    $statut = "Ouvert";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page admin système</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="../CSS/style_user.css" rel="stylesheet">
    <link href="../CSS/style_volet_information.css" rel="stylesheet">
    <link href="../CSS/style_page_adm_systeme.css" rel="stylesheet">
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
                <a href=""><i class="fa fa-bar-chart"></i> &nbsp; Application statistique</a>
            </li>
            <li>
                <a href="page_adm_systeme_log2.php"><i class="fa fa-calendar"></i> &nbsp; Aperçu des logs</a>
            </li>
            <li>
                <a href="page_adm_systeme_log.php"><i class="fa fa-history"></i> &nbsp; Aperçu des événements du jour</a>
            </li>
            <li>
                <a href="profil.php"><i class="fa fa-user"></i> &nbsp; Profil</a>
            </li>
            <a class="deconnexion" href="../PHP/Deconnexion.php" class="bouton"><i class="fa fa-sign-out"></i> Déconnexion</a>
        </ul>
    </nav>
    <div class="corps">
        <main>
            <h3 id="tickets-ouverts">Tickets <?php echo $type; ?>:</h3>
            <br>
            <?php
            // Préparation de la requête
            $query = "SELECT t.id_ticket as Id, t.Login as Crée_par, lt.libelle as Libelle, t.priorite as Priorité, DATE_FORMAT(t.date_creation, '%d/%m/%Y') as 'date_creation', t.statut as Statut 
                    FROM $table t
                    LEFT JOIN libelle_ticket lt ON t.id_libelle = lt.id_libelle
                    WHERE t.statut=?";
            $stmt = mysqli_prepare($connection, $query);

            if ($stmt) {
                // Lie le paramètre pour le statut du ticket
                mysqli_stmt_bind_param($stmt, "s", $statut);

                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                // Afficher les tickets
                if ($result && mysqli_num_rows($result) > 0) {
                    creationTable($result, $_SESSION['user_role']);
                } else {
                    echo "Aucun ticket trouvé.";
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "Erreur lors de la préparation de la requête.";
            }
            ?>
            <br>
            <br>
            <div style="text-align: right">
                <!-- Ajout de liens pour changer entre les tickets ouverts et fermés -->
                <a href="?type=<?php echo $type == 'ouverts' ? 'fermes' : 'ouverts'; ?>" class="arrow-link left">&#8592;</a>
                <a href="?type=<?php echo $type == 'ouverts' ? 'fermes' : 'ouverts'; ?>" class="arrow-link right">&#8594;</a>
            </div>

        </main>
    </div>
</div>
</body>
</html>
