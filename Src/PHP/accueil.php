<?php
include 'fonction_creation_table.php';
require 'fonction_connexion_bd.php';

$connection = connectToDatabase();
$namedb = "BD_Ticketing";
$db = mysqli_select_db($connection, $namedb) or die("Erreur de sélection de la base de données");
$tab = "tickets";

$query = "SELECT t.id_ticket as Id, t.Login as login, lt.libelle as Libelle, t.priorite as Priorité, DATE_FORMAT(t.date_creation, '%d/%m/%Y') as 'date_creation', t.statut as Statut , t.technicien as Technicien
            FROM $tab t
            LEFT JOIN libelle_ticket lt ON t.id_libelle = lt.id_libelle
            ORDER BY id_ticket DESC LIMIT 10";

$result = mysqli_query($connection, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="../CSS/style_seconde_proposition.css" rel="stylesheet">
    <link href="../CSS/style_volet_information.css" rel="stylesheet">
    <link href="../CSS/style_tableau.css" rel="stylesheet">
</head>
<body>
<div class="page">
    <nav>
        <ul>
            <li class="logo"><img alt="logo de Rayan Ticket" src="../IMG/Proposition_logo_1.png"></li>
            <li>
                <i><h3 class="role">Role : Visiteur</h3></i>
            </li>
            <li>
                <a href="accueil.php"><i class="fa fa-home"></i> &nbsp; Accueil</a>
            </li>
            <li>
                <a href="#presentation"><i class="fa fa-question"></i> &nbsp; Qu'est ce que le ticketing ?</a>
            </li>
            <li>
                <a href="#video_explicative"><i class="fa fa-film"></i> &nbsp; Vidéo explicative</a>
            </li>
            <li>
                <a href="#tickets_recents"><i class="fa fa-ticket"></i> &nbsp; Tickets récents</a>
            </li>
            <li>
                <a href="#about"><i class="fa fa-book"></i> &nbsp; À propos</a>
            </li>
            <li>
                <a href="form_contact.php"><i class="fa fa-phone"></i> &nbsp; Contact</a>
            </li>
            <a class="connexion-inscription" href="../PHP/form_connexion_inscription.php"><i class="fa fa-user"></i> Connexion / Inscription</a>
        </ul>
    </nav>
    <div class="corps">
        <main>
            <h3 id="presentation">Qu'est ce que le ticketing ?</h3>
            <br>
            <p class="ticketing">
                Le ticketing est un processus qui englobe la création et la résolution de tickets
                par un personnel compétent. Un ticket est une demande de dépannage soumise en cas de problème,
                en particulier dans les salles machines de l'IUT. Ces tickets sont classés en fonction de
                leur niveau d'urgence, puis pris en charge par des techniciens qui sont responsables de les résoudre.
                Ainsi, le ticketing facilite la gestion efficace des problèmes en assurant une priorisation appropriée
                des demandes et en garantissant qu'elles sont traitées par des professionnels qualifiés.
            </p>
            <br>
            <br>
            <h3 id="video_explicative">Vidéo explicative</h3>
            <br>
            <p class="video">Dans cette vidéo vous retrouverez la manière d'utiliser notre plateforme de ticketing Intern</p>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/9uEyFrY2Avs?si=r3xCeONPE2Eqn1rJ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            <br>

            <?php
            if ($result) {
                creationTable($result, 'Visiteur');
            }

            ?>

            <br>
            <br>
        </main>

        <script src="../JS/Script.js"></script>

        <?php
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
            $couleur = false;
            // Appel de la fonction sans inclure à nouveau le script
            echo "<script>afficherVolet('$message', '$couleur');</script>";
            // Vider la session après utilisation
            unset($_SESSION['couleur']);
            unset($_SESSION['message']);
        } else {
            echo "<script>console.log('KO');</script>";
        }
        ?>


        <footer id="about">
            <div class="column">
                <h4>À propos de Rayan's Corp</h4>
                <p>
                    Rayan's Corp est un groupe d'étudiants en informatique en BUT2 à l'IUT de Vélizy,
                    composé de BEN TANFOUS Rayan, CLOUZEAU Armand, BADER Sarah, PESENTI Aymeric et AKBOULATOV Ismail.
                    Nous sommes passionnés par les technologies émergentes et travaillons ensemble pour créer des solutions innovantes.
                </p>
            </div>
        </footer>

    </div>
</div>
</body>
</html>
