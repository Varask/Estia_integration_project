<?php
include_once('databaseModel.php');
function registerUser($name, $firstName, $email, $password) {
    $conn = connectToDatabase();

    // Vérifier si l'e-mail existe déjà dans la base de données
    $sql_check_email = "SELECT * FROM employee WHERE mail = '$email'";
    $result = $conn->query($sql_check_email);

    if ($result->num_rows > 0) {
        return "L'e-mail existe déjà dans la base de données";
    }

    // Crypter le mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insérer les données dans la base de données
    $sql_employee = "INSERT INTO employee (name, firstname, mail, id_role, SommeTravailPasse, SommeTravailAVenir, created_at) VALUES ('$name', '$firstName', '$email', '5', '0', '0', NOW())";
    $sql_security = "INSERT INTO security (password) VALUES ('$hashed_password')";

    if ($conn->query($sql_employee) === TRUE && $conn->query($sql_security) === TRUE) {
        return "Nouveau compte créé avec succès";
    } else {
        return "Erreur lors de la création du compte : " . $conn->error;
    }
}
