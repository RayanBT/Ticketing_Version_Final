<?php
// Fonction pour enregistrer le fichier de log en format CSV avec le nom du fichier contenant la date
function saveLogAsCSV($logFilePath)
{
    // Chemin vers le répertoire où seront stockés les fichiers CSV
    $csvDirectory = 'logs/';

    // Création du nom de fichier CSV avec la date
    $csvFileName = 'log_' . date('d-m-Y') . '.csv';
    $csvFilePath = $csvDirectory . $csvFileName;

    // Si le fichier CSV du jour existe déjà, ajoutez le contenu du fichier de log à ce fichier
    if (file_exists($csvFilePath)) {
        // Lecture du contenu du fichier CSV existant
        $existingContent = file_get_contents($csvFilePath);

        // Ajout du contenu du fichier de log à celui du fichier CSV existant
        $logContent = file_get_contents($logFilePath);
        $newContent = $existingContent . $logContent;

        // Écriture du contenu combiné dans le fichier CSV
        file_put_contents($csvFilePath, $newContent);
    } else {
        // Si le fichier CSV n'existe pas, créez-le et copiez simplement le contenu du fichier de log
        $logContent = file_get_contents($logFilePath);
        file_put_contents($csvFilePath, $logContent);
    }

    // Vider le contenu du fichier de log
    file_put_contents($logFilePath, '');
}
?>
