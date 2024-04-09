<?php
session_start();
require_once 'Config.php';
require 'fonction_creation_table.php';

// Vérification de la connexion et du rôle de l'utilisateur
if (!isset($_SESSION['login']) || $_SESSION['user_role'] !== "admin_systeme") {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}


require 'fonction_read_csv_today.php';

// Chemin vers votre répertoire où sont stockés les fichiers CSV
$csvDirectory = 'logs/';

// Lecture du contenu du fichier CSV du jour
$contenuFichier = readCSVForToday($csvDirectory);

// Si le fichier CSV du jour existe, afficher son contenu
if ($contenuFichier !== false) {
    // Nombre de lignes par page
    $nombreLignesParPage = 15;

    // Calcul du nombre total de pages
    $nombreTotalPages = ceil(count($contenuFichier) / $nombreLignesParPage);

    // Numéro de page par défaut
    $numeroPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // Si le numéro de page est supérieur au nombre total de pages, le définir sur la dernière page
    if ($numeroPage > $nombreTotalPages) {
        $numeroPage = $nombreTotalPages;
    }

    // Calcul de l'offset
    $offset = ($numeroPage - 1) * $nombreLignesParPage;

    // Récupération des lignes à afficher pour la page actuelle
    $lignesPageCourante = array_slice($contenuFichier, $offset, $nombreLignesParPage);
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Page des logs du jour</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
        <link href="../CSS/style_user.css" rel="stylesheet">
        <link href="../CSS/style_volet_information.css" rel="stylesheet">
        <link href="../CSS/style_page_adm_systeme.css" rel="stylesheet">
        <style>
            .page {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            .corps {
                flex-grow: 1;
            }
            .pagination {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-bottom: 20px;
            }
            .pagination a, .pagination select {
                margin: 0 5px;
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
    <br>
    <br>
        <?php if ($contenuFichier !== false): ?>
            <?php foreach ($lignesPageCourante as $ligne): ?>
                <?php echo $ligne . '<hr>'; ?>
            <?php endforeach; ?>
            <div class="pagination">
                <!-- Affichage des liens de pagination -->
                <?php if ($numeroPage > 1): ?>
                    <a href="?page=<?php echo ($numeroPage - 1); ?>">Précédent</a>
                <?php endif; ?>
                <!-- Affichage de la liste déroulante pour sélectionner le numéro de la page -->
                <form action="" method="GET">
                    Aller à la page :
                    <select name="page" onchange="this.form.submit()">
                        <?php for ($i = 1; $i <= $nombreTotalPages; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php if ($i === $numeroPage) echo 'selected'; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </form>
                <?php if ($numeroPage < $nombreTotalPages): ?>
                    <a href="?page=<?php echo ($numeroPage + 1); ?>">Suivant</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>Aucun fichier CSV n'a été trouvé pour aujourd'hui.</p>
        <?php endif; ?>
    </main>
    </div>
</div>
</body>
</html>

