<?php
if (!isset($_SESSION['user']) || $_SESSION['statut'] != "administrateur") {
    header("Location: ../index.php");
    exit();
}
?>