<?php
function connectToDatabase() {
    // Paramètres de connexion à la base de données
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        // Connexion locale
        $servername = "localhost"; // Adresse du serveur MySQL
        $username = "root"; // Identifiant MySQL
        $password_db = ""; // Mot de passe MySQL
        $dbname = "dbb_integration"; // Nom de la base de données
    } else {
        // Connexion sur le serveur
        $servername = "localhost"; // Adresse du serveur MySQL
        $username = "tai_app_2023_2024_starfish"; // Identifiant MySQL
        $password_db = "97GESCP1YP"; // Mot de passe MySQL
        $dbname = "tai_app_2023_2024_starfish"; // Nom de la base de données
    }

    // Création de la connexion
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }

    return $conn;
}