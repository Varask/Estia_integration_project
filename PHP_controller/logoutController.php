<?php
// Détruire la session active
session_start();
session_destroy();

// Rediriger l'utilisateur vers la page de connexion
header("Location: ../view/loginView.html");
exit;