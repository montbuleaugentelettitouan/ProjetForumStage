<?php
session_start();
include('fonctionality/bdd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // On récupère les données du formulaire
    $emailEtu = $_POST["emailEtu"];
    $numEtu = $_POST["numEtu"];
    $nomEtu = $_POST["nomEtu"];
    $prenomEtu = $_POST["prenomEtu"];
    $parcoursEtu = $_POST["parcoursEtu"];
    $statutEtu = $_POST["statutEtu"];


    if (isset($_POST['natureEtu'])) {
        $natureCont = $_POST['natureEtu'];
        $rsEnt = $_POST['RSEntreprise'];
        $siteEnt = $_POST['SiteEntreprise'];
        $serviceEnt = $_POST['ServiceEntreprise'];
        $paysEnt = $_POST['PaysEntreprise'];
        $villeEnt = $_POST['VilleEntreprise'];
        $cpEnt = $_POST['CPEntreprise'];
        $statutCont = $_POST['StatutContrat'];

        if (isset($_POST['DebContrat'])) {
            $debCont = $_POST['DebContrat'];
            $finCont = $_POST['FinContrat'];
            $nomMDS = $_POST['NomMDS'];
            $prenomMDS = $_POST['PrenomMDS'];
            $emailMDS = $_POST['EmailMDS'];
            $remuneration = $_POST['Rémunération'];
            $nomTA = $_POST['NomTA'];
            $prenomTA = $_POST['PrénomTA'];
            $emailTA = $_POST['EmailTA'];
        }
    }

    // Mise à jour de la table "utilisateur"
    $sqlUtilisateur = $bdd->prepare("UPDATE utilisateur SET email = ?, nom = ?, prenom = ?, numEtu = ?, parcours = ?, etatC = ? WHERE idUtilisateur = ?");
    $sqlUtilisateur->execute(array($emailEtu, $nomEtu, $prenomEtu, $numEtu, $parcoursEtu, $statutEtu, $_SESSION['user']));
/*
    // On teste si les champs additionnels du formulaire ont été affichés ou pas
    if ($Suite1 != False) {
        $sqlStage = "UPDATE convention_contrat SET
        type_contrat = :typeContrat,
        //secteur = :secteur,
        //code_postal = :cpEntreprise,
        statut_contrat = :statutContrat,
        //nom_Site = :nomSite,
        //pays_Stage = :pays,
        ville_stage = :ville,
        nom_Entreprise = :rsEnt";

        if ($Suite2 == True) {
            $sqlStage .= ",
            dateDeb = :debutContrat,
            dateFin = :finContrat,
            nomTuteur = :nomMDS,
            prenomTuteur = :prenomMDS,
            emailTuteur = :emailMDS,
            gratification = :remuneration,
            nom_tuteur_academique = :nomTA,
            prenom_tuteur_academique = :prenomTA,
            email_tuteur_academique = :emailTA";
        }

        $sqlStage .= " WHERE idUtilisateur = :idU";

        $majStage = $bdd->prepare($sqlStage);
        $majStage->bindParam(':typeContrat', $natureCont);
        $majStage->bindParam(':secteur', $serviceEnt);
        $majStage->bindParam(':cpEntreprise', $cpEnt);
        $majStage->bindParam(':statutContrat', $statutCont);
        $majStage->bindParam(':nomSite', $siteEnt);
        $majStage->bindParam(':pays', $paysEnt);
        $majStage->bindParam(':ville', $villeEnt);
        $majStage->bindParam(':rsEnt', $rsEnt);
        $majStage->bindParam(':idU', $_SESSION['user']);

        if ($Suite2 == True) {
            $majStage->bindParam(':debutContrat', $debCont);
            $majStage->bindParam(':finContrat', $finCont);
            $majStage->bindParam(':nomMDS', $nomMDS);
            $majStage->bindParam(':prenomMDS', $prenomMDS);
            $majStage->bindParam(':emailMDS', $emailMDS);
            $majStage->bindParam(':remuneration', $remuneration);
            $majStage->bindParam(':nomTA', $nomTA);
            $majStage->bindParam(':prenomTA', $prenomTA);
            $majStage->bindParam(':emailTA', $emailTA);
        }

        $majStage->execute();
    }
*/
    header("Location: formu_stage_m2.php");
} else {
    header("Location: dashboardPersonnel.php");
}
?>