<?php
session_start();
include('fonctionality/bdd.php');
include('fonctionality/annee+promo.php');

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

        // On cherche si l'étudiant est dans la table convention_contrat avec une offre M2
        $searchCCM2 = $bdd->prepare('SELECT * FROM convention_contrat JOIN offre USING (idOffre) WHERE idUtilisateur = ? AND niveau = "M2"');
        $searchCCM2->execute(array($_SESSION['user']));
        $countCCM2 = $searchCCM2->rowcount();

        // Si etudiant dans la table convention_contrat M2 alors on actualise les informations
        if ($countCCM2 != 0) {
            $resultatCCM2 = $searchCCM2->fetch();
            // UPDATE TOUTES LES INFOS DANS TOUTES LES TABLES CONCERNEES
            $updateCC1 = $bdd->prepare('UPDATE convention_contrat JOIN offre USING (idOffre) SET type_contrat = ?, statut_contrat = ? WHERE idUtilisateur = ? AND niveau = "M2"');
            $updateCC1->execute(array($natureCont, $statutCont, $_SESSION['user']));

            $updateE1 = $bdd->prepare('UPDATE entreprise JOIN site USING (idEntreprise) JOIN offre USING (idSite) JOIN convention_contrat USING (idOffre) SET nomEntreprise = ? WHERE idUtilisateur = ? AND niveau = "M2"');
            $updateE1->execute(array($rsEnt, $_SESSION['user']));

            $updateS1 = $bdd->prepare('UPDATE site JOIN offre USING (idSite) JOIN convention_contrat USING (idOffre) SET nomSite = ?, pays = ?, ville = ?, code_postal = ? WHERE idUtilisateur = ? AND niveau = "M2"');
            $updateS1->execute(array($siteEnt, $paysEnt, $villeEnt, $cpEnt, $_SESSION['user']));

            $updateO1 = $bdd->prepare('UPDATE offre JOIN convention_contrat USING (IdOffre) SET titre = ? WHERE idUtilisateur = ? AND niveau = "M2"');
            $updateO1->execute(array($serviceEnt, $_SESSION['user']));

        } else {
            // Si la nouvelle offre découle du stage de M1
            if ($offreM1 == 'ouim1') {
                // SELECT les infos importantes de l'offre de M1
                $reqOuiM1 = $bdd->prepare("
                        SELECT
                            offre.idOffre,
                            offre.titre,
                            site.idSite,
                            entreprise.idEntreprise
                        FROM
                            convention_contrat
                        LEFT JOIN offre ON convention_contrat.idOffre = offre.idOffre
                        LEFT JOIN site ON offre.idSite = site.idSite
                        LEFT JOIN entreprise ON site.idEntreprise = entreprise.idEntreprise 
                        WHERE idUtilisateur = ? AND niveau = 'M1'");
                $reqOuiM1->execute(array($_SESSION['user']));
                $resultatOuiM1 = $reqOuiM1->fetch();

                $idOffreOuiM1 = $resultatOuiM1['idOffre'];
                $titreOuiM1 = $resultatOuiM1['titre'];
                $idSiteOuiM1 = $resultatOuiM1['idSite'];
                $idEntrepriseOuiM1 = $resultatOuiM1['idEntreprise'];

                // SELECT dans les offres M2 join site join entreprise si une offre M2 correspond à ces infos
                $reqOuiM2 = $bdd->prepare("
                        SELECT
                            *
                        FROM
                            offre
                        LEFT JOIN site ON offre.idSite = site.idSite
                        LEFT JOIN entreprise ON site.idEntreprise = entreprise.idEntreprise 
                        WHERE offre.titre = ? AND site.idSite = ? AND entreprise.idEntreprise = ? AND niveau = 'M2'");
                $reqOuiM2->execute(array($titreOuiM1, $idSiteOuiM1, $idEntrepriseOuiM1, $_SESSION['user']));
                $resultatOuiM2 = $reqOuiM2->fetch();

                if ($resultatOuiM2) {
                    // Si offre existe déja avec ces infos, alors on récupère son idOffre pour le mettre dans une nouvelle ligne de convention_contrat plus bas
                    $idOffreM2 = $resultatOuiM2['idOffre'];
                    $UpdateM1 = $bdd->prepare("UPDATE offre SET nbPoste = nbPoste + 1, nbPostePourvu = nbPostePourvu + 1 SET WHERE idOffre = ?");
                    $UpdateM1->execute(array($idOffreM2));
                } else {
                    // Sinon on crée une nouvelle ligne dans offre avec ces infos et on récup ensuite son idOffre par une autre requete
                    $insertNewO = $bdd->prepare("INSERT INTO offre (titre, nbPoste, nbPostePourvu, anneeO, parcours, niveau, valider, idOffreM2, idSite) VALUES (?,?,?,?,?,?,?,?,?)");
                    $insertNewO->execute(array($titreOuiM1, 1, 1, $annee, $_SESSION['parcours'], 'M2', 1, $idOffreOuiM1, $idSiteOuiM1));

                    $recupNewO = $bdd->prepare("SELECT idOffre FROM offre WHERE titre = ? AND niveau = 'M2' AND idSite = ?");
                    $recupNewO->execute(array($titreOuiM1, $idSiteOuiM1));
                    $resultatNewO = $recupNewO->fetch();

                    $idOffreM2 = $resultatNewO['idOffre'];
                }
            } else {
                // SELECT dans les offres M2 join site join entreprise si une offre M2 correspond aux infos du $_POST
                $reqNonM2 = $bdd->prepare("
                        SELECT
                            *
                        FROM
                            offre
                        LEFT JOIN site ON offre.idSite = site.idSite
                        LEFT JOIN entreprise ON site.idEntreprise = entreprise.idEntreprise 
                        WHERE offre.titre = ? AND site.nomSite = ? AND entreprise.nomEntreprise = ? AND niveau = 'M2'");
                $reqNonM2->execute(array($rsEnt, $siteEnt, $serviceEnt));
                $resultatNonM2 = $reqNonM2->fetch();

                if ($resultatNonM2) {
                    // Si offre existe déja avec ces infos, alors on récupère son idOffre pour le mettre dans une nouvelle ligne de convention_contrat plus bas
                    $idOffreM2 = $resultatNonM2['idOffre'];
                    $UpdateM1 = $bdd->prepare("UPDATE offre SET nbPoste = nbPoste + 1, nbPostePourvu = nbPostePourvu + 1 SET WHERE idOffre = ?");
                    $UpdateM1->execute(array($idOffreM2));
                } else {
                    // Sinon on crée une nouvelle ligne dans entreprise si elle existe pas déja, dans site si il existe pas deja puis dans offre avec ces infos et on récup ensuite son idOffre par une autre requete
                    // On crée la nouvelle entreprise si elle n'existe pas, sinon on récupère son IdEntreprise
                    $idE = '';

                    $recupE = $bdd->prepare('SELECT * FROM entreprise WHERE nomEntreprise LIKE ?');
                    $recupE->execute(array($rsEnt));
                    $resultatE = $recupE->fetch();

                    if ($resultatE != null) {
                        $idE = $resultatE['idEntreprise'];
                    } else {
                        $reqinsertE = $bdd->prepare('INSERT INTO entreprise (nomEntreprise) VALUES (?)');
                        $reqinsertE->execute(array($rsEnt));

                        $recupE = $bdd->prepare('SELECT idEntreprise FROM entreprise WHERE nomEntreprise LIKE ? ORDER BY idEntreprise DESC LIMIT 1');
                        $recupE->execute(array($rsEnt));
                        $resultatE = $recupE->fetch();

                        $idE = $resultatE['idEntreprise'];
                    }

                    // On crée le nouveau site si il n'existe pas, sinon on récupère son IdSite
                    $idS = '';

                    $recupS = $bdd->prepare('SELECT * FROM site WHERE nomSite LIKE ? AND ville LIKE ?');
                    $recupS->execute(array($siteEnt, $villeEnt));
                    $resultatS = $recupS->fetch();

                    if ($resultatS != null) {
                        $idS = $resultatS['idSite'];
                    } else {
                        $reqinsertS = $bdd->prepare('INSERT INTO site (nomSite, ville, pays, idEntreprise) VALUES (?,?,?,?)');
                        $reqinsertS->execute(array($siteEnt, $villeEnt, $paysEnt, $idE));

                        $recupS = $bdd->prepare('SELECT idSite FROM site WHERE nomSite LIKE ? AND ville LIKE ? ORDER BY idSite DESC LIMIT 1');
                        $recupS->execute(array($siteEnt, $villeEnt));
                        $resultatS = $recupS->fetch();

                        $idS = $resultatS['idSite'];
                    }

                    // On crée la nouvelle offre pour M2
                    $insertNewO = $bdd->prepare("INSERT INTO offre (titre, nbPoste, nbPostePourvu, anneeO, parcours, niveau, valider, idSite) VALUES (?,?,?,?,?,?,?,?)");
                    $insertNewO->execute(array($serviceEnt, 1, 1, $annee, $_SESSION['parcours'], 'M2', 1, $idS));

                    $recupNewO = $bdd->prepare("SELECT idOffre FROM offre WHERE titre = ? AND niveau = 'M2' AND idSite = ?");
                    $recupNewO->execute(array($serviceEnt, $idS));
                    $resultatNewO = $recupNewO->fetch();

                    $idOffreM2 = $resultatNewO['idOffre'];
                }

            }

            // On insère une nouvelle ligne dans convention_contrat avec la nouvelle offre
            $insertCCnew = $bdd->prepare("INSERT INTO convention_contrat (idOffre, idUtilisateur, type_contrat, statut_contrat) VALUES (?,?,?,?)");
            $insertCCnew->execute(array($idOffreM2, $_SESSION['user'], $natureCont, $statutCont));
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

            // On cherche si le MDS est dans la table maitre_de_stage
            $reqIdMDS = $bdd->prepare('SELECT idMDS FROM maitre_de_stage WHERE nomMDS = ? AND prenomMDS = ?');
            $reqIdMDS->execute(array($nomMDS,$prenomMDS));

            // On vérifie le nombre de lignes retournées par la requête
            $nombreResultats = $reqIdMDS->rowCount();

            // Si aucun maître de stage est trouvé, on l'ajoute puis on récupère son ID, sinon on a déjà son ID
            if ($nombreResultats == 0) {
                // On récupère l'idSite depuis l'offre liée a la convention de M2
                $recupIdSite = $bdd->prepare('SELECT idSite FROM offre JOIN convention_contrat USING (idOffre) WHERE idUtilisateur = ? AND niveau = "M2"');
                $recupIdSite->execute(array($_SESSION['user']));
                $resultatIdSite = $recupIdSite->fetch();
                $idSite = $resultatIdSite['idSite'];

                $upMDS = $bdd->prepare('INSERT INTO maitre_de_stage (nomMDS, prenomMDS, emailMDS, idSite) VALUES (?,?,?,?)');
                $upMDS->execute(array($nomMDS,$prenomMDS,$emailMDS,$idSite));

                // On récupère l'ID du nouveau maître de stage
                $reqIdMDS2 = $bdd->prepare('SELECT idMDS FROM maitre_de_stage WHERE nomMDS = ? AND prenomMDS = ?');
                $reqIdMDS2->execute(array($nomMDS,$prenomMDS));

                $resultat = $reqIdMDS2->fetch();
                $idMDS = $resultat['idMDS'];
            } else {
                // Si la 1ère requête a retourné un résultat, on prend l'ID
                $resultat = $reqIdMDS->fetch();
                $idMDS = $resultat['idMDS'];
            }

            // On regarde quel TA a été choisi et on récup son idTA


            // On met a jour les infos dans convention_contrat
            $updateCC2 = $bdd->prepare('UPDATE convention_contrat JOIN offre USING (idOffre) SET dateDeb = ?, dateFin = ?, gratification = ?, idTA = ?, idMDS = ? WHERE idUtilisateur = ? AND niveau = "M2"');
            $updateCC2->execute(array($debCont, $finCont, $remuneration, $idTA, $idMDS, $_SESSION['user']));
        }
    }
    header("Location: formu_stage_m2.php");
} else {
    header("Location: profil.php");
}
?>