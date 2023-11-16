<?php
/**
 * @autor:  Thibault NIGGEL, Jules FAVRE
 * @date : Promo Gphy 2025 - Année 2022 - 2023
 *
 */
include('fonctionality/bdd.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_POST['recherche'])) {
    $rec = $_POST['recherche'];

    $req = $bdd->prepare("SELECT idUtilisateur FROM utilisateur WHERE nom = :rec AND statut = 'etudiant'");
    $req->bindParam(':rec', $rec);
    $req->execute();
    $resultat = $req->fetchAll();

    if ($resultat) {
        if (count($resultat) == 1) {
            header("Location: ./dashboardSUIVIFORUM2.php?value=".$resultat[0]['idUtilisateur']);
        } else {
            // Si il y a plusieurs élèves avec le même nom
            header("Location: ./dashboardADMIN.php");
        }
    } else {
        header("Location: ./dashboardADMIN.php");
    }
}