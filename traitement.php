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

    // Mise à jour de la table "utilisateur"
    $sqlUtilisateur = $bdd->prepare("UPDATE utilisateur SET email = ?, nom = ?, prenom = ?, numEtu = ?, parcours = ?, etatCM2 = ? WHERE idUtilisateur = ?");
    $sqlUtilisateur->execute(array($emailEtu, $nomEtu, $prenomEtu, $numEtu, $parcoursEtu, $statutEtu, $_SESSION['user']));

    if (isset($_POST['OffreM1'])) {
        $offreM1 = $_POST['OffreM1'];

        $natureCont = $_POST['natureEtu'];
        $rsEnt = $_POST['RSEntreprise'];
        $siteEnt = $_POST['SiteEntreprise'];
        $serviceEnt = $_POST['ServiceEntreprise'];
        $paysEnt = $_POST['PaysEntreprise'];
        $villeEnt = $_POST['VilleEntreprise'];
        $cpEnt = $_POST['CPEntreprise'];
        $statutCont = $_POST['StatutContrat'];

        $natureCont = $_POST['natureEtu']; convention_contrat.type_contrat
        $rsEnt = $_POST['RSEntreprise']; entreprise.nomEntreprise
$siteEnt = $_POST['SiteEntreprise']; site.nomSite
$serviceEnt = $_POST['ServiceEntreprise']; offre.secteur
$paysEnt = $_POST['PaysEntreprise']; site.pays,
$villeEnt = $_POST['VilleEntreprise']; site.ville
$cpEnt = $_POST['CPEntreprise']; site.code_postal
$statutCont = $_POST['StatutContrat']; convention_contrat.statut_contrat

        // On cherche si l'étudiant est dans la table convention_contrat avec une offre M2
        $searchCCM2 = $bdd->prepare('SELECT * FROM convention_contrat JOIN offre USING (idOffre) WHERE idUtilisateur = ? AND niveau = M2');
        $searchCCM2->execute(array($_SESSION['user']));
        $resultatCCM2 = $searchCCM2->fetch();
        $countCCM2 = $resultatCCM2->rowcount();

        // Si etudiant dans la table convention_contrat M2 alors on actualise les informations
        if ($countCCM2 != 0) {
            // UPDATE TOUTES LES INFOS DANS TOUTES LES TABLES CONCERNEES
            $updateCC1 = $bdd->prepare("UPDATE convention_contrat SET type_contrat = ? AND statut_contrat = ?");
            $updateCC1->execute(array($natureCont, $statutCont));

        } else {
            // Si la nouvelle offre découle du stage de M1
            if ($offreM1 == "ouim1") {
                // SELECT les infos de l'offre de M1
                // SELECT dans les offres M2 join site join entreprise si une offre M2 correspond à ces infos
                // Si offre existe déja avec ces infos, alors on récupère son idOffre pour le mettre dans une nouvelle ligne de convention_contrat plus bas
                // Sinon on crée une nouvelle ligne dans offre avec ces infos et on récup ensuite son idOffre par une autre requete
            } else {
                // SELECT dans les offres M2 join site join entreprise si une offre M2 correspond aux infos du $_POST
                // Si offre existe déja avec ces infos, alors on récupère son idOffre pour le mettre dans une nouvelle ligne de convention_contrat plus bas
                // Sinon on crée une nouvelle ligne dans entreprise si elle existe pas déja, dans site si il existe pas deja puis dans offre avec ces infos et on récup ensuite son idOffre par une autre requete
            }







            // On regarde si les infos ajoutées correspondent à une offre déjà enregistrée en M2
            $recupE = $bdd -> prepare ('SELECT * FROM offre JOIN site USING (idSite) JOIN entreprise USING (idEntreprise) WHERE titre = ? AND niveau = ? AND nomEntrepise = ? AND nomSite = ?');
            $recupE -> execute (array($serviceEnt, 'M2', $rsEnt, $siteEnt));
            $resultatE = $recupE -> fetch();

            if ($resultatE != null) {

            } else {

            }




            // On rajoute les infos dans la BDD : Entreprise, puis site, puis l'offre et enfin la convention_contrat
            // On crée la nouvelle entreprise si elle n'existe pas, sinon on récupère son IdEntreprise
            $idE = '';

            $recupE = $bdd -> prepare ('SELECT * FROM entreprise WHERE nomEntreprise LIKE ?');
            $recupE -> execute (array($rsEnt));
            $resultatE = $recupE -> fetch();

            if ($resultatE != null) {
                $idE = $resultatE['idEntreprise'];
            } else {
                $reqinsertE = $bdd -> prepare ('INSERT INTO entreprise (nomEntreprise) VALUES (?)');
                $reqinsertE -> execute (array($rsEnt));

                $recupE = $bdd -> prepare ('SELECT idEntreprise FROM entreprise WHERE nomEntreprise LIKE ? ORDER BY idEntreprise DESC LIMIT 1');
                $recupE -> execute (array($rsEnt));
                $resultatE = $recupE -> fetch();

                $idE = $resultatE['idEntreprise'];
            }

            // On crée le nouveau site si il n'existe pas, sinon on récupère son IdSite
            $idS = '';

            $recupS = $bdd -> prepare ('SELECT * FROM site WHERE nomSite LIKE ? AND ville LIKE ?');
            $recupS -> execute (array($siteEnt, $villeEnt));
            $resultatS = $recupS -> fetch();

            if ($resultatS != null) {
                $idS = $resultatS['idSite'];
            } else {
                $reqinsertS = $bdd -> prepare ('INSERT INTO site (nomSite, ville, pays, idEntreprise) VALUES (?,?,?,?)');
                $reqinsertS -> execute (array($siteEnt, $villeEnt, $paysEnt, $idE));

                $recupS = $bdd -> prepare ('SELECT idSite FROM site WHERE nomSite LIKE ? AND ville LIKE ? ORDER BY idSite DESC LIMIT 1');
                $recupS -> execute (array($siteEnt, $villeEnt));
                $resultatS = $recupS -> fetch();

                $idS = $resultatS['idSite'];
            }

            // On crée la nouvelle offre pour M2, en vérifiant si elle découle du stage M1
            $insertOnew = $bdd->prepare("INSERT INTO offre (titre, nbPoste, nbPostePourvu, anneeO, secteur, parcours, niveau, valider, idOffreM2, idSite) VALUES (?,?)");
            $insertOnew->execute(array($serviceEnt, ));

            // On insère une nouvelle ligne dans convention_contrat avec la nouvelle offre
            $insertCCnew = $bdd->prepare("INSERT INTO convention_contrat (type_contrat, statut_contrat) VALUES (?,?)");
            $insertCCnew->execute(array($natureCont, $statutCont));
        }




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








$debCont = $_POST['DebContrat']; convention_contrat.dateDeb
$finCont = $_POST['FinContrat']; convention_contrat.dateFin
$nomMDS = $_POST['NomMDS']; maitre_de_stage.nomMDS
$prenomMDS = $_POST['PrenomMDS']; maitre_de_stage.prenomMDS
$emailMDS = $_POST['EmailMDS']; maitre_de_stage.emailMDS
$remuneration = $_POST['Rémunération']; convention_contrat.gratification
$nomTA = $_POST['NomTA']; tuteur_academique.nomTA,
$prenomTA = $_POST['PrénomTA']; tuteur_academique.prenomTA
$emailTA = $_POST['EmailTA']; tuteur_academique.emailTA




    header("Location: formu_stage_m2.php");
} else {
    header("Location: profil.php");
}
?>