<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = $_POST['Name'];
    $firstName = $_POST['FirstName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    // Vérifier si les mots de passe correspondent
    if ($password != $password2) {
        echo "<script>alert('Les mots de passe ne correspondent pas'); window.location.href='login.html';</script>";
        exit;
    }

    // Crypter le mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

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

    // Vérifier si l'e-mail existe déjà dans la base de données
    $sql = "SELECT * FROM employee WHERE mail = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<script>alert('L\'e-mail existe déjà dans la base de données'); window.location.href='login.html';</script>";
        exit;
    }

    // Insérer les données dans la base de données
    $sql_employee = "INSERT INTO employee (name, firstname, mail, id_role, SommeTravailPasse, SommeTravailAVenir, created_at) VALUES ('$name', '$firstName', '$email', '5', '0', '0', NOW())";
    $sql_security = "INSERT INTO security (password) VALUES ('$hashed_password')";

    if ($conn->query($sql_employee) === TRUE && $conn->query($sql_security) === TRUE) {
        echo "<script>alert('Nouveau compte créé avec succès');</script>";
    } else {
        echo "<script>alert('Erreur lors de la création du compte : " . addslashes($conn->error) . "'); window.location.href='login.html';</script>";
    }

    // Fermer la connexion
    $conn->close();
} else {
    // Rediriger vers la page de connexion si le formulaire n'a pas été soumis
    header("Location: login.html");
    exit;
}