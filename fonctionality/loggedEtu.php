<?php
if (!isset($_SESSION['user']) || $_SESSION['statut'] != "etudiant") {
    header("Location: ../index.php");
    exit();
}
?>