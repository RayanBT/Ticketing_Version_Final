<?php

include 'fonction_connexion_bd.php';
//Script regroupant les fonctions pour avoir les infos sur la SESSION
//session_start();
function getRole(){
    return $_SESSION['user_role'];
}

function getLogin(){
    return $_SESSION['login'];
}

function getCaptcha(){
    return $_SESSION['captcha'];
}

function getMessage(){
    return $_SESSION['message'];
}

function getCouleur(){
    return $_SESSION['couleur'];
}

function getAll(){
    foreach ($_SESSION as $key => $value) {
        echo $key . ': ' . $value . '<br>';
    }
}

function getUserIdByUsername($username) {
    global $host, $user, $password, $database;

    $connection = connectToDatabase($host, $user, $password, $database);

    $query = "SELECT id_user FROM user WHERE login = ?";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $userId);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
        return $userId;
    } else {
        // Gérer l'erreur de préparation de la requête
        return null;
    }
}