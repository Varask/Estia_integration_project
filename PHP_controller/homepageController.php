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
        $role = $userInfo['role'];
        $price = $userInfo['price'];
    } else {
        // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
        header("Location: ../view/loginView.html");
        exit;
    }

    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitTaskForm'])) {
        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $type = $_POST['type'];
        $assignee = $_POST['Assignee'];
        $dateDebut = $_POST['dateDebut'];
        $dateFin = $_POST['dateFin'];
        $couleur = $_POST['couleur'];
        $tempsEstime = $_POST['tempsEstime'];

        // Convertir les dates en timestamps Unix pour la comparaison
        $timestampDebut = strtotime($dateDebut);
        $timestampFin = strtotime($dateFin);

        // Vérifier si les dates correspondent
        if ($timestampDebut < $timestampFin) {
            $result = addTask($nom, $type, $assignee, $dateDebut, $dateFin, $couleur, $tempsEstime);
            echo "<script>alert('$result'); window.location.href='../PHP_controller/homepageController.php';</script>";
        } else {
            echo "<script>alert('La date de début doit se situer avant la date de fin.');</script>";
        }
    }

    // Appelez la fonction pour récupérer les tâches
    $tasks = getTasks();
    $assignees = getAssignees();
    $roles = getRoles();
    $assigned = getAssignedTo();

    // Convertir les tâches en format JSON pour les passer à la vue HTML via JavaScript
    $tasks_json = json_encode($tasks);
    $assignees_json = json_encode($assignees);
    $roles_json = json_encode($roles);
    $assigned_json = json_encode($assigned);

    include_once('../view/homepageView.html');