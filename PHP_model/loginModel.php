<?php
include_once('databaseModel.php');
function attemptLogin($email, $password) {
    $conn = connectToDatabase();
    $sql = "SELECT e.mail, s.password 
        FROM employee e 
        INNER JOIN security s ON e.id = s.id_employee 
        WHERE e.mail = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        if (password_verify($password, $hashed_password)) {
            return true;
        }
    }
    return false;
}
