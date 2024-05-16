<?php
include_once('databaseModel.php');


function verifyConnection($conn) {
    if ($conn == null) {
        try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }
    }
}

function getUserInfo($email) {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }
    
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
function addTask($nom, $type, $assignee, $dateDebut, $dateFin, $couleur, $tempsEstime) {
    $conn = connectToDatabase();
    
    // Débuter une transaction
    $conn->begin_transaction();

    try {
        // Insérer la nouvelle tâche dans la table `tasks`
        $sql_task = "INSERT INTO tasks (name, id_type, id_state, is_validated, color, date_debut, date_fin, estimated_time, created_at)
                        VALUES (?, ?, '2', '0', ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql_task);
        $stmt->bind_param("ssssss", $nom, $type, $couleur, $dateDebut, $dateFin, $tempsEstime);
        $stmt->execute();

        // Récupérer l'ID de la tâche nouvellement créée
        $task_id = $stmt->insert_id;

        // Insérer dans la table `assigned_tasks`
        $sql_assigned_tasks = "INSERT INTO assigned_tasks (id_task, id_employee) VALUES (?, ?)";

        $stmt = $conn->prepare($sql_assigned_tasks);
        $stmt->bind_param("ii", $task_id, $assignee);
        $stmt->execute();

        // Valider la transaction
        $conn->commit();

        return "Tâche ajoutée et assignée avec succès";
    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $conn->rollback();

        return "Erreur lors de l'ajout de la tâche ou de l'assignation : " . $e->getMessage();
    } finally {
        // Fermer le statement et la connexion
        $stmt->close();
        $conn->close();
    }
}
function getTasks() {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }

    // Préparer la requête SQL pour récupérer les tâches
    $sql = "SELECT t.id, t.name, ty.name AS type, s.name AS state, t.is_validated, t.color, t.date_debut, t.date_fin, t.estimated_time, t.created_at,
                   e.firstname, e.name AS employee_name
            FROM tasks t
            INNER JOIN types ty ON t.id_type = ty.id
            INNER JOIN states s ON t.id_state = s.id
            LEFT JOIN assigned_tasks at ON t.id = at.id_task
            LEFT JOIN employee e ON at.id_employee = e.id";

    // Exécuter la requête SQL
    $result = $conn->query($sql);

    // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        // Créer un tableau pour stocker les tâches
        $tasks = array();

        // Parcourir les résultats
        while ($row = $result->fetch_assoc()) {
            // Ajouter chaque tâche au tableau
            $tasks[] = $row;
        }

        // Retourner le tableau de tâches
        return $tasks;
    } else {
        // Si aucun résultat n'est trouvé, retourner null
        return null;
    }
}

function getAssignees() {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }

    // Préparer la requête SQL pour récupérer les tâches
    $sql = "SELECT e.id, e.name, e.firstname
            FROM employee e";

    // Exécuter la requête SQL
    $result = $conn->query($sql);

    // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        // Créer un tableau pour stocker les tâches
        $assignees = array();

        // Parcourir les résultats
        while ($row = $result->fetch_assoc()) {
            // Ajouter chaque tâche au tableau
            $assignees[] = $row;
        }

        // Retourner le tableau de tâches
        return $assignees;
    } else {
        // Si aucun résultat n'est trouvé, retourner null
        return null;
    }
}


function getCoutTotal() {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }
    $sql = "SELECT SUM(e.SommeTravailPasse * r.price) AS cout_total
            FROM employee e
            JOIN roles r ON e.id_role = r.id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['cout_total'];
    }
    else {
        print_r("Erreur lors de la récupération du coût total : " . $conn->error);
        return null;
    }
}


function getNombreHeuresTotales() {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }
    $sql = "SELECT SUM(e.SommeTravailPasse + e.SommeTravailAVenir) AS nombre_heures_totales
            FROM employee e";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre_heures_totales'];
    }
    else {
        print_r("Erreur lors de la récupération du Nb Heure Totale : " . $conn->error);
        return null;
    }
}


function getCoutHoraireMoyen() {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }
    $sql = "SELECT AVG(r.price) AS cout_horaire
            FROM employee e
            JOIN roles r ON e.id_role = r.id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['cout_horaire'];
    }
    else {
        print_r("Erreur lors de la récupération du Horaire Moyen : " . $conn->error);
        return null;
    }
}


function getListeUtilisateurs() {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }
    $sql = "SELECT e.name, e.firstname, (e.SommeTravailPasse + e.SommeTravailAVenir) AS total_heures
            FROM employee e";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $users = array();
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
    else {
        print_r("Erreur lors de la récupération des Utilisateurs : " . $conn->error);
        return null;
    }
}


function getNombreTachesPlanifiees() {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }
    $sql = "SELECT COUNT(*) AS nombre_taches_planifiees
            FROM tasks
            WHERE id_state = (SELECT id FROM states WHERE name = 'Current')";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre_taches_planifiees'];
    }
    else {
        print_r("Erreur lors de la récupération du Taches Planifiées : " . $conn->error);
        return null;
    }
}


function getCoutTachesPlanifiees() {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }
    $sql = "SELECT SUM(t.estimated_time * r.price) AS cout_taches_planifiees
            FROM tasks t
            JOIN assigned_tasks at ON t.id = at.id_task
            JOIN employee e ON at.id_employee = e.id
            JOIN roles r ON e.id_role = r.id
            WHERE t.id_state = (SELECT id FROM states WHERE name = 'Current')";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['cout_taches_planifiees'];
    }
    else {
        print_r("Erreur lors de la récupération du Couts Taches Planifiées: " . $conn->error);
        return null;
    }
}


function getCoutTotalProjet() {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }
    $sql = "SELECT 
                (SELECT SUM(e.SommeTravailPasse * r.price) 
                 FROM employee e
                 JOIN roles r ON e.id_role = r.id) + 
                (SELECT SUM(t.estimated_time * r.price) 
                 FROM tasks t
                 JOIN assigned_tasks at ON t.id = at.id_task
                 JOIN employee e ON at.id_employee = e.id
                 JOIN roles r ON e.id_role = r.id
                 WHERE t.id_state = (SELECT id FROM states WHERE name = 'Current')) AS cout_total_projet";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['cout_total_projet'];
    }
    else {
        print_r("Erreur lors de la récupération du coût total Projet : " . $conn->error);
        return null;
    }
}


function getNombreHeuresPlanifiees() {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }
    $sql = "SELECT SUM(t.estimated_time) AS nombre_heures_planifiees
            FROM tasks t
            WHERE t.id_state = (SELECT id FROM states WHERE name = 'Current')";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre_heures_planifiees'];
    }
    else {
        print_r("Erreur lors de la récupération Nombre Heures Planifiees : " . $conn->error);
        return null;
    }
}


function getBilanProjet() {
    try {
            $conn = connectToDatabase();
        } catch (Exception $e) {
            echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            return null;
        }
    $CoutTotal = getCoutTotal();
    $NombreHeuresTotales = getNombreHeuresTotales();
    $CoutHoraireMoyen = getCoutHoraireMoyen();
    $ListeUtilisateurs = getListeUtilisateurs();
    $NombreTachesPlanifiees = getNombreTachesPlanifiees();
    $CoutTachesPlanifiees = getCoutTachesPlanifiees();
    $CoutTotalProjet = getCoutTotalProjet();
    $NombreHeuresPlanifiees = getNombreHeuresPlanifiees();

    return array(
        'CoutTotal' => $CoutTotal,
        'NombreHeuresTotales' => $NombreHeuresTotales,
        'CoutHoraireMoyen' => $CoutHoraireMoyen,
        'ListeUtilisateurs' => $ListeUtilisateurs,
        'NombreTachesPlanifiees' => $NombreTachesPlanifiees,
        'CoutTachesPlanifiees' => $CoutTachesPlanifiees,
        'CoutTotalProjet' => $CoutTotalProjet,
        'NombreHeuresPlanifiees' => $NombreHeuresPlanifiees
    );

}
