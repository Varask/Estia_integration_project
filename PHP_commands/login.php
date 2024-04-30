<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connexion à la base de données
    $servername = "localhost"; // Adresse du serveur MySQL
    $username = "root"; // Identifiant MySQL
    $password_db = ""; // Mot de passe MySQL
    $dbname = "dbb_integration"; // Nom de la base de données

    // Création de la connexion
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }

    // Préparer la requête SQL pour récupérer le mot de passe crypté
    $sql = "SELECT e.mail, s.password 
        FROM employee e 
        INNER JOIN security s ON e.id = s.id_employee 
        WHERE e.mail = '$email'";
    $result = $conn->query($sql);

    // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        // Récupérer le mot de passe crypté depuis la base de données
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Vérifier si le mot de passe correspond en utilisant password_verify()
        if (password_verify($password, $hashed_password)) {
            // Les informations de connexion sont correctes, rediriger vers la page d'accueil
            header("Location: ../pages/homepage.html");
            exit;
        } else {
            // Les informations de connexion sont incorrectes, rediriger vers la page de connexion avec un message d'erreur
            echo "<script>alert('E-mail ou mot de passe incorrect'); window.location.href='../pages/login.html';</script>";
            exit;
        }
    } else {
        // Les informations de connexion sont incorrectes, rediriger vers la page de connexion avec un message d'erreur
        echo "<script>alert('E-mail ou mot de passe incorrect'); window.location.href='../pages/login.html';</script>";
        exit;
    }
} else {
    // Rediriger vers la page de connexion si le formulaire n'a pas été soumis
    header("Location: ../pages/login.html");
    exit;
}