<?php
/**
 * fonction de déconnexion
 * On commence une session pour ensuite détruire toutes les variables de la session en court
 * et enfin on détruit la session.
 */
session_start();
session_unset();
session_destroy();
header('Location: ../index.php');
exit;
?>

