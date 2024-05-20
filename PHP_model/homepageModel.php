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
    return $conn;
}

function getUserInfo($email) {
    try {
        $conn = connectToDatabase();
    } catch (Exception $e) {
        echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
        return null;
    }

    $sql = "SELECT e.firstname, e.name, r.name AS role
            FROM employee e
            INNER JOIN roles r ON e.id_role = r.id
            WHERE e.mail = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userInfo = array(
            'firstname' => $row['firstname'],
            'name' => $row['name'],
            'role' => $row['role']
        );
        return $userInfo;
    } else {
        return null;
    }
}

function addTask($nom, $type, $assignee, $dateDebut, $dateFin, $couleur, $tempsEstime) {
    $conn = connectToDatabase();
    $conn->begin_transaction();

    try {
        $sql_task = "INSERT INTO tasks (name, id_type, id_state, is_validated, color, date_debut, date_fin, estimated_time, created_at)
                     VALUES (?, ?, '2', '0', ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql_task);
        $stmt->bind_param("ssssss", $nom, $type, $couleur, $dateDebut, $dateFin, $tempsEstime);
        $stmt->execute();

        $task_id = $stmt->insert_id;

        $sql_assigned_tasks = "INSERT INTO assigned_tasks (id_task, id_employee) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_assigned_tasks);
        $stmt->bind_param("ii", $task_id, $assignee);
        $stmt->execute();

        $conn->commit();
        return "Tâche ajoutée et assignée avec succès";
    } catch (Exception $e) {
        $conn->rollback();
        return "Erreur lors de l'ajout de la tâche ou de l'assignation : " . $e->getMessage();
    } finally {
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

    $sql = "SELECT t.id, t.name, ty.name AS type, s.name AS state, t.is_validated, t.color, t.date_debut, t.date_fin, t.estimated_time, t.created_at,
                   e.firstname, e.name AS employee_name
            FROM tasks t
            INNER JOIN types ty ON t.id_type = ty.id
            INNER JOIN states s ON t.id_state = s.id
            LEFT JOIN assigned_tasks at ON t.id = at.id_task
            LEFT JOIN employee e ON at.id_employee = e.id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $tasks = array();
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
        return $tasks;
    } else {
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

    $sql = "SELECT e.id, e.name, e.firstname
            FROM employee e";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $assignees = array();
        while ($row = $result->fetch_assoc()) {
            $assignees[] = $row;
        }
        return $assignees;
    } else {
        return null;
    }
}

function getActualTotalCost() {
    try {
        $conn = connectToDatabase();
    } catch (Exception $e) {
        echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
        return null;
    }

    $sql = "SELECT SUM(e.SommeTravailPasse) AS total_cost FROM employee e";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_cost'];
    } else {
        return null;
    }
}

function getActualTotalHours() {
    try {
        $conn = connectToDatabase();
    } catch (Exception $e) {
        echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
        return null;
    }

    $sql = "SELECT SUM(t.estimated_time) AS total_hours FROM tasks t WHERE t.is_validated = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_hours'];
    } else {
        return null;
    }
}

function getActualCostbyHours() {
    $totalCost = getActualTotalCost();
    $totalHours = getActualTotalHours();
    if ($totalCost !== null && $totalHours !== null && $totalHours != 0) {
        return $totalCost / $totalHours;
    } else {
        return null;
    }
}

function getPrevisionalNumberOfPlannedTasks() {
    try {
        $conn = connectToDatabase();
    } catch (Exception $e) {
        echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
        return null;
    }

    $sql = "SELECT COUNT(*) AS planned_tasks FROM tasks t WHERE t.is_validated = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['planned_tasks'];
    } else {
        return null;
    }
}

function getPrevisionalNumberOfHours() {
    try {
        $conn = connectToDatabase();
    } catch (Exception $e) {
        echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
        return null;
    }

    $sql = "SELECT SUM(t.estimated_time) AS planned_hours FROM tasks t WHERE t.is_validated = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['planned_hours'];
    } else {
        return null;
    }
}

function getPrevisionalTasksCost() {
    try {
        $conn = connectToDatabase();
    } catch (Exception $e) {
        echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
        return null;
    }

    $sql = "SELECT SUM(e.SommeTravailAVenir) AS planned_cost FROM employee e";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['planned_cost'];
    } else {
        return null;
    }
}

function getTotalCost() {
    $actualTotalCost = getActualTotalCost();
    $previsionalTotalCost = getPrevisionalTasksCost();
    if ($actualTotalCost !== null && $previsionalTotalCost !== null) {
        return $actualTotalCost + $previsionalTotalCost;
    } else {
        return null;
    }
}

function getPrevisionalTotalCost() {
    $previsionalTasksCost = getPrevisionalTasksCost();
    $previsionalNumberOfHours = getPrevisionalNumberOfHours();
    if ($previsionalTasksCost !== null && $previsionalNumberOfHours !== null && $previsionalNumberOfHours != 0) {
        return $previsionalTasksCost / $previsionalNumberOfHours;
    } else {
        return null;
    }
}

function getProjectReport() {
    $report = array(
        'actual_total_cost' => getActualTotalCost(),
        'actual_total_hours' => getActualTotalHours(),
        'actual_cost_by_hours' => getActualCostbyHours(),
        'previsional_number_of_planned_tasks' => getPrevisionalNumberOfPlannedTasks(),
        'previsional_number_of_hours' => getPrevisionalNumberOfHours(),
        'previsional_tasks_cost' => getPrevisionalTasksCost(),
        'total_cost' => getTotalCost(),
        'previsional_total_cost' => getPrevisionalTotalCost()
    );
    return $report;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buttonContent = $_POST['buttonContent'];
    $taskId = $_POST['taskId'];
    try {
        $conn = connectToDatabase();
        switch ($buttonContent) {
            case 'Mettre en attente':
                $newStateId = 3;
                $valide = 0;
                break;
            case 'Valider':
                $newStateId = 1;
                $valide = 1;
                break;
            case 'Rouvrir':
                $newStateId = 2;
                $valide = 0;
                break;
            case 'Fermer':
                $newStateId = 4;
                $valide = 1;
                break;
            default:
                break;
        }
        $sql = "UPDATE tasks SET id_state = ?, is_validated = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $newStateId, $valide, $taskId);
        $stmt->execute();

        $stmt->close();
        $conn->close();
        
        echo "L'état de la tâche a été mis à jour avec succès.";
    } catch (Exception $e) {
        echo "Erreur lors de la mise à jour de l'état de la tâche : " . $e->getMessage();
    }
}
?>
