<?php
include_once('../PHP_model/registerModel.php');




// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = $_POST['Name'];
    $firstName = $_POST['FirstName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $assignee_id = $_POST['assignee'];
    // Vérifier si les mots de passe correspondent
    if ($password != $password2) {
        echo "<script>alert('Les mots de passe ne correspondent pas'); window.location.href='../view/loginView.html';</script>";
        exit;
    }

    $result = registerUser($name, $firstName, $email, $password);
    $lastId = getLastTaskId();
    $result2 = addAssignee($lastId, $assignee_id);
    
    echo "<script>alert('$result2'); window.location.href='../view/loginView.html';</script>";


} else {
    // Rediriger vers la page de connexion si le formulaire n'a pas été soumis
    header("Location: ../view/loginView.html");
    exit;
}

