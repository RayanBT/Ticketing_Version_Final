<?php
// Fonction pour lire le contenu du fichier CSV du jour
function readCSVForToday($csvDirectory)
{
    // Création du nom de fichier CSV avec la date d'aujourd'hui
    $csvFileName = 'log_' . date('d-m-Y') . '.csv';
    $csvFilePath = $csvDirectory . $csvFileName;

    // Vérifier si le fichier CSV existe
    if (file_exists($csvFilePath)) {
        // Lire le contenu du fichier CSV
        $lines = file($csvFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Inverser l'ordre des lignes
        $lines = array_reverse($lines);

        return $lines;
    } else {
        return false;
    }
}
?>
