<?php
include_once('../PHP_model/loginModel.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];
    $result = attemptLogin($email, $password);

    // Vérifier s'il y a des résultats
    if ($result) {
        // Les informations de connexion sont correctes, rediriger vers la page d'accueil
        header("Location: ../view/homepageView.html");
        exit;
    } else {
        // Les informations de connexion sont incorrectes, rediriger vers la page de connexion avec un message d'erreur
        echo "<script>alert('E-mail ou mot de passe incorrect'); window.location.href='../view/loginView.html';</script>";
        exit;
    }
} else {
    // Rediriger vers la page de connexion si le formulaire n'a pas été soumis
    header("Location: ../view/loginView.html");
    exit;
}