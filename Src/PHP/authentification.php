<?php
session_start();

require_once('Config.php');
require 'fonction_get_session.php';
if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}
$login = getLogin();
$user_role = getRole();
$message = getMessage();
if (isset($user_role)) {
    if ($user_role == "utilisateur") {
        logMessage("Redirection vers la page utilisateur.php pour l'user avec le login : $login", "debug");
        header("Location: ../PHP/utilisateur.php");
    } elseif ($user_role == "admin_web") {
        logMessage("Redirection vers la page_adm_web pour l'user avec le login : $login", "debug");
        header("Location: ../PHP/page_adm_web.php");
    }elseif ($user_role == "technicien") {
        logMessage("Redirection vers la page_technicien pour l'user avec le login : $login", "debug");
        header("Location: ../PHP/page_technicien.php");
    }elseif ($user_role == "admin_systeme") {
        logMessage("Redirection vers la page_admin_systeme pour l'user avec le login : $login", "debug");
        header("Location: ../PHP/page_admin_systeme.php");
    }
}else{
    logMessage("Redirection vers la page accueil.php car il ne dispose pas de role pour l'user avec le login : $login", "error");
    header("Location: ../PHP/accueil.php");
}
