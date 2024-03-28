<?php
include('barre_nav_M1.php');
include('fonctionality/bdd.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // On récupère les données du formulaire
    $emailEtu = $_POST["email"];
    $numEtu = $_POST["numEtu"];
    $nomEtu = $_POST["nom"];
    $prenomEtu = $_POST["prenom"];
    $parcoursEtu = $_POST["parcours"];
    $rechercheEtu = $_POST["etatC"];
    $natureCont = $_POST["type_contrat"];
    $rsEnt = $_POST["nomEntreprise"];
    $siteEnt = $_POST["nomSite"];
    $serviceEnt = $_POST["secteur"];
    $paysEnt = $_POST["pays"];
    $villeEnt = $_POST["ville"];
    $cpEnt = $_POST["code_postal"];
    $statutCont = $_POST["statut_contrat"];
    $debCont = $_POST["dateDeb"];
    $finCont = $_POST["dateFin"];
    $nomMDS = $_POST["nomMDS"];
    $prenomMDS = $_POST["prenomMDS"];
    $emailMDS = $_POST["emailMDS"];
    $remuneration = $_POST["gratification"];
    $nomTA = $_POST["nomTA"];
    $prenomTA = $_POST["prénomTA"];
    $emailTA = $_POST["emailTA"];

    $Test1 = True; //$_POST['AddFields1'];
    $Test2 = True; //$_POST['AddFields2'];
    // Mise à jour de la table "utilisateur"
    $sqlUtilisateur = "UPDATE utilisateur SET
        email = :email, 
        nom = :nom, 
        prenom = :prenom, 
        numEtu = :numEtu, 
        parcours = :parcours, 
        etatC = :etat 
        WHERE idUtilisateur = :idU";
    $majUtilisateur = $bdd->prepare($sqlUtilisateur);
    $majUtilisateur->bindParam(':email', $emailEtu);
    $majUtilisateur->bindParam(':nom', $nomEtu);
    $majUtilisateur->bindParam(':prenom', $prenomEtu);
    $majUtilisateur->bindParam(':numEtu', $numEtu);
    $majUtilisateur->bindParam(':parcours', $parcoursEtu);
    $majUtilisateur->bindParam(':etat', $rechercheEtu);
    $majUtilisateur->bindParam(':idU', $_SESSION['user']);
    $majUtilisateur->execute();

    // On teste si les champs additionnels du formulaire ont été affichés ou pas
    if ($Test1 != False) {
        $sqlStage = "UPDATE convention_contrat SET
        type_contrat = :typeContrat,
        //secteur = :secteur,
        //code_postal = :cpEntreprise,
        statut_contrat = :statutContrat,
        //nom_Site = :nomSite,
        //pays_Stage = :pays,
        ville_stage = :ville,
        nom_Entreprise = :rsEnt";

        if ($Test2 == True) {
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

        if ($Test2 == True) {
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

    header("Location: formu_stage_m2.php");
} else {
    header("Location: dashboardPersonnel.php");
}
?>