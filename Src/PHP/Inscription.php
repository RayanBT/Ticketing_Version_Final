<?php
session_start();

require_once('Config.php');
require_once ('RC4.php');
// Vérifie si le formulaire a été soumis
if (isset($_POST["inscription"]) or isset($_POST["inscription_technicien"])) {
    if (isset($_POST['captcha']) && isset($_SESSION['captcha'])) {
        $userAnswer = intval($_POST['captcha']);
        $correctAnswer = $_SESSION['captcha'];
        if ($userAnswer === $correctAnswer) {
            $host = "localhost";
            $user = "root";
            $password = "";
            $key_rc4 = "Groupe1";
            if (!empty($_POST['nom']) and !empty($_POST['login']) and !empty($_POST['email']) and !empty($_POST['mot_de_passe'])) {
                // Récupère la valeur de l'input nom et la stocke dans la variable $nom
                $nom = $_POST["nom"];

                // Récupère la valeur de l'input login et la stocke dans la variable $login
                $login = $_POST["login"];

                // Récupère la valeur de l'input email et la stocke dans la variable $email
                $email = $_POST["email"];

                // Récupère la valeur de l'input mot_de_passe et la stocke dans la variable $mot_de_passe
                $mot_de_passe_chiffre = rc4_encrypt($_POST["mot_de_passe"], $key_rc4);


                /* connection serveur BD */
                $connection = mysqli_connect($host, $user, $password) or die("erreur");
                $namedb = "BD_Ticketing";
                $db = mysqli_select_db($connection, $namedb) or die("erreur");
                $tab = "User";

                // Requête préparée pour vérifier si l'e-mail existe déjà
                $query = "SELECT * FROM $tab WHERE Email = ?";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, 's', $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                // Requête préparée pour vérifier si le login existe déjà
                $query2 = "SELECT * FROM $tab WHERE Login = ?";
                $stmt2 = mysqli_prepare($connection, $query2);
                mysqli_stmt_bind_param($stmt2, 's', $login);
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_store_result($stmt2);

                if (mysqli_stmt_num_rows($stmt) > 0 and mysqli_stmt_num_rows($stmt2) > 0) {
                    $_SESSION['message'] = 'Échec inscription. Adresse e-mail et le login existent déjà.';
                    $_SESSION['couleur'] = false;
                    header('Location: ../PHP/form_connexion_inscription.php');
                    exit();
                } elseif (mysqli_stmt_num_rows($stmt) > 0) {
                    $_SESSION['message'] = 'Échec inscription. Adresse e-mail existent déjà.';
                    $_SESSION['couleur'] = false;
                    header('Location: ../PHP/form_connexion_inscription.php');
                    exit();
                } elseif (mysqli_stmt_num_rows($stmt2) > 0) {
                    $_SESSION['message'] = 'Échec de inscription. Le login existent déjà.';
                    $_SESSION['couleur'] = false;
                    header('Location: ../PHP/form_connexion_inscription.php');
                    exit();
                } else {
                    if (isset($_POST["inscription_technicien"])) {
                        $requete = "INSERT INTO `User` (`id_User`,`Nom`, `Login`, `Email`, `Mdp`, `user_role`) VALUES (NULL,?, ?, ?, ?, 'technicien')";
                    } elseif (isset($_POST["inscription"])) {
                        // Requête SQL correcte avec des marqueurs de paramètres
                        $requete = "INSERT INTO `User` (`id_User`,`Nom`, `Login`, `Email`, `Mdp`) VALUES (NULL,?, ?, ?, ?)";
                    }

                    // Préparation de la requête
                    $reqprepare = mysqli_prepare($connection, $requete);

                    if (!$reqprepare) {
                        die("Erreur de préparation de la requête : " . mysqli_error($connection));
                    } else {
                        mysqli_stmt_bind_param($reqprepare, 'ssss', $nom, $login, $email, $mot_de_passe_chiffre);

                        // Exécution de la requête préparée
                        $result = mysqli_stmt_execute($reqprepare);

                        if ($result) {
                            logMessage("Inscription réussie pour l'utilisateur avec l'adresse e-mail : $email");
                            $_SESSION['message'] = "Inscription réussie";
                            $_SESSION['couleur'] = true;
                            if (isset($_POST["inscription_technicien"])) {
                                header('Location: ../PHP/page_adm_web.php');
                                exit();
                            }
                            header('Location: ../PHP/form_connexion_inscription.php');
                            exit();
                        } else {
                            logMessage("Echec de l'inscription pour l'utilisateur avec l'adresse e-mail : $email", 'error');
                            $_SESSION['message'] = "Échec inscription";
                            $_SESSION['couleur'] = false;
                            if (isset($_POST["inscription_technicien"])) {
                                header('Location: ../PHP/form_creation_technicien.php');
                                exit();
                            }
                            header('Location: ../PHP/form_connexion_inscription.php');
                            exit();
                        }
                        // Fermeture de la requête préparée
                        mysqli_stmt_close($reqprepare);
                    }
                }

                mysqli_close($connection);
            }
        }else{
            logMessage("Tentative d'inscription. Captcha incorrect. Veuillez réessayer.", 'error');
            $_SESSION['message'] = "Captcha incorrect. Veuillez réessayer.";
            $_SESSION['couleur'] = false;
            header('Location: ../PHP/form_connexion_inscription.php');
            exit();
        }
    }else{
        logMessage("Tentative d'inscription. Veuillez remplir le captcha.", 'error');
        $_SESSION['message'] = "Veuillez remplir le captcha.";
        $_SESSION['couleur'] = false;
        header('Location: ../PHP/form_connexion_inscription.php');
        exit();
    }
}
?>

