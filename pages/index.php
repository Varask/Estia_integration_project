<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/main.css">
    <script type="text/javascript" src="../JS/testcookie.js"></script>
    <title>Document</title>
</head>
<body>
    <div class="printer">
        <?php
        // Connexion à la base de données
        $servername = "localhost"; // Adresse du serveur MySQL
        $username = "root"; // Identifiant MySQL
        $password = ""; // Mot de passe MySQL
        $dbname = "dbb_integration"; // Nom de la base de données

        // Création de la connexion
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("Connexion échouée : " . $conn->connect_error);
        }

        // Requête SQL pour sélectionner tous les e-mails de la table "employee"
        $sql = "SELECT firstname, name, mail FROM employee";
        $result = $conn->query($sql);

        // Vérifier s'il y a des résultats
        if ($result->num_rows > 0) {
            // Afficher les e-mails
            while($row = $result->fetch_assoc()) {
                echo "E-mail: " . $row["mail"] . "<br>";
                echo "Nom: " . $row["firstname"] . " " . $row["name"] . "<br><br>";
            }
        } else {
            echo "Aucun résultat trouvé.";
        }

        // Fermer la connexion
        $conn->close();
        ?>
    </div>
</body>
</html>
