<?php
    // Vérifier si la session est active
    session_start();

    include_once('../PHP_model/homepageModel.php');

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        // Inclure le fichier de connexion à la base de données
        include_once('../PHP_model/loginModel.php');

        $userInfo = getUserInfo($_SESSION['user_email']); // Cette fonction devrait retourner le nom et le prénom de l'utilisateur connecté

        $userFirstName = $userInfo['firstname'];
        $userLastName = $userInfo['name'];
    } else {
        // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
        header("Location: ../view/loginView.html");
        exit;
    }

    include_once('../view/homepageView.html');