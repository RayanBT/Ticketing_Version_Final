<?php
session_start();
require_once 'Config.php';
require 'fonction_creation_table.php';
require 'fonction_save_logs_csv.php';

// Vérification de la connexion et du rôle de l'utilisateur
if (!isset($_SESSION['login']) || $_SESSION['user_role'] !== "admin_systeme") {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}

// Enregistrement des logs au format CSV
saveLogAsCSV('app.log');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bibliothèque des fichiers csv</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="../CSS/style_user.css" rel="stylesheet">
    <link href="../CSS/style_volet_information.css" rel="stylesheet">
    <link href="../CSS/style_page_adm_systeme.css" rel="stylesheet">
    <link href="../CSS/fichier_log.css" rel="stylesheet">
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: #333;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input[type="date"] {
            padding: 5px;
            font-size: 16px;
        }
        .search-bar button {
            padding: 5px 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
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
                <a href="page_adm_systeme_log2.php"><i class="fa fa-history"></i> &nbsp; Aperçu des logs</a>
            </li>
            <li>
                <a href="page_adm_systeme_log.php"><i class="fa fa-history"></i> &nbsp; Aperçu des événements du jour</a>
            </li>
            <li>
                <a href="profil.php"><i class="fa fa-user"></i> &nbsp; Profil</a>
            </li>
            <a class ="deconnexion" href="../PHP/Deconnexion.php" class="bouton"><i class="fa fa-sign-out"></i> Déconnexion</a>
        </ul>
    </nav>
    <div class="corps">
        <main>
            <h3 class="phrase_acceuil">Bienvenue sur la page des logs.</h3>
            <form action="" method="GET">
                <div class="search-bar">
                    <input type="date" name="search_date">
                    <button type="submit">Rechercher</button>
                    <button type="button" onclick="window.location.href='page_adm_systeme_log2.php'">Réinitialiser</button>
                </div>
            </form>
            <div class="grid-container">
                <?php
                // Chemin vers le répertoire où sont stockés les fichiers CSV
                $logDirectory = 'logs/';

                // Récupération de la date de recherche
                $searchDate = isset($_GET['search_date']) ? date('d-m-Y', strtotime($_GET['search_date'])) : null;

                // Récupération de tous les fichiers CSV dans le répertoire
                $logFiles = glob($logDirectory . '*.csv');

                // Tri des fichiers par date (du plus récent au plus ancien)
                usort($logFiles, function ($a, $b) {
                    // Extraction de la date des noms de fichiers
                    $dateA = substr($a, strrpos($a, '_') + 1, 10);
                    $dateB = substr($b, strrpos($b, '_') + 1, 10);
                    // Comparaison des dates
                    return strtotime($dateB) - strtotime($dateA);
                });

                // Vérification si la recherche est vide
                if ($_GET['search_date'] === '') {
                    // Chargement de tous les fichiers CSV dans le répertoire
                    $logFiles = glob($logDirectory . '*.csv');
                } else {
                    // Filtrage des fichiers par date si une date de recherche est spécifiée
                    if ($searchDate !== null) {
                        $logFiles = array_filter($logFiles, function ($file) use ($searchDate) {
                            return strpos($file, $searchDate) !== false;
                        });
                    }
                }

                // Pagination
                $nombreFichiers = count($logFiles);
                $nombreLignesParPage = 10;
                $nombrePages = ceil($nombreFichiers / $nombreLignesParPage);
                $page = isset($_GET['page']) ? min(max($_GET['page'], 1), $nombrePages) : 1;
                $offset = ($page - 1) * $nombreLignesParPage;
                $logFiles = array_slice($logFiles, $offset, $nombreLignesParPage);

                // Affichage des fichiers sous forme de grille avec une carte pour chaque fichier
                foreach ($logFiles as $logFile) {
                    echo '<a href="' . $logFile . '" download>';
                    echo '<div class="grid-item">';
                    echo '<img src="../IMG/file-csv.png" alt="File Icon">';
                    echo '<p>' . basename($logFile) . '</p>';
                    echo '</div>';
                    echo '</a>';
                }
                ?>
            </div>
            <div class="pagination">
                <?php if ($nombrePages > 1): ?>
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo ($page - 1); ?>">Précédent</a>
                <?php endif; ?>
                Page <?php echo $page; ?> de <?php echo $nombrePages; ?>
                <?php if ($page < $nombrePages): ?>
                <a href="?page=<?php echo ($page + 1); ?>">Suivant</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php if (empty($logFiles)): ?>
                <p>Aucun fichier trouvé.</p>
            <?php endif; ?>
        </main>
    </div>
</div>
</body>
</html>

