<?php
include('bdd.php');
// Requête SQL pour récupérer l'année de promotion la plus grande
$requete_max_annee = $bdd->query("SELECT MAX(promo) AS max_annee FROM utilisateur");
$resultat_max_annee = $requete_max_annee->fetch(PDO::FETCH_ASSOC);

// Récupération de l'année de promotion la plus grande
$annee_max = $resultat_max_annee['max_annee'];

if (!isset($_SESSION['compt'])){
    $promo = $annee_max;
    $annee = $promo-1;
    $parcours = 'GPhy';
}
?>
