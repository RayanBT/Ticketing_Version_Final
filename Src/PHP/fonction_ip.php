<?php
function validateIP($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP);
}

// Fonction pour obtenir l'IP locale de l'utilisateur
function getDefaultIP() {
    return $_SERVER['REMOTE_ADDR'];
}