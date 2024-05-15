<?php
include_once('databaseModel.php');

function getUserInfo($email) {
    $conn = connectToDatabase();
    
    // Préparer la requête SQL pour récupérer le prénom et le nom de l'utilisateur en fonction de son adresse e-mail
    $sql = "SELECT firstname, name FROM employee WHERE mail = '$email'";
    
    // Exécuter la requête SQL
    $result = $conn->query($sql);
    
    // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        // Récupérer la première ligne de résultat
        $row = $result->fetch_assoc();
        
        // Récupérer le prénom et le nom de l'utilisateur
        $userInfo = array(
            'firstname' => $row['firstname'],
            'name' => $row['name']
        );

        // Retourner les informations de l'utilisateur
        return $userInfo;
    } else {
        // Si aucun résultat n'est trouvé, retourner null
        return null;
    }
}
function addTask($nom, $type, $dateDebut, $dateFin, $couleur, $tempsEstime) {
    $conn = connectToDatabase();

    // Insérer les données dans la base de données
    $sql_task = "INSERT INTO tasks (name, id_type, id_state, is_validated, color, date_debut, date_fin, estimated_time, created_at)
                     VALUES ('$nom', '$type', '2', '0', '$couleur', '$dateDebut', '$dateFin', '$tempsEstime', NOW())";

    if ($conn->query($sql_task) === TRUE) {
        return "Tâche ajoutée avec succès";
    } else {
        return "Erreur lors de l\'ajout de la tâche : " . $conn->error;
    }
}