<?php
/**
 * Formulaire de stage pour les M2 GPhy/GCell/ECMPS
 *
 * @autor : Thibault NIGGEL, Jules FAVRE
 * @date : Promo GPhy 2025 - Année 2022/2023
 *
 */
include('barre_nav_M1.php');
include('fonctionality/bdd.php');
?>

<!-- Nos variables. $espaces permet de mettre 5 espaces rapidement, sinon il faudrait placer '&nbsp;' à chaque fois pour signifier un espace en HTML. -->
<?php
$espaces3 = str_repeat('&nbsp;', 3);
$espaces5 = str_repeat('&nbsp;', 5);
$espaces8 = str_repeat('&nbsp;', 8);

// On intiialise les variables au cas où il n'y ai pas de résultat associé après la requête SQL
$emailEtuPrerempli = '';
$numEtuPrerempli = '';
$nomEtuPrerempli = '';
$prenomEtuPrerempli = '';
$parcoursPrerempli = '';
$recherchePrerempli = '';
$natureContratPrerempli = '';
$rsEntreprisePrerempli = '';
$siteEntreprisePrerempli = '';
$serviceEntreprisePrerempli = '';
$paysEntreprisePrerempli = '';
$villeEntreprisePrerempli = '';
$cpEntreprisePrerempli = '';
$contratPrerempli = '';
$debutStagePrerempli = '';
$finStagePrerempli = '';
$nomMDSPrerempli = '';
$prenomMDSPrerempli = '';
$emailMDSPrerempli = '';
$remunerationPrerempli = '';
$nomTAPrerempli = '';
$prenomTAPrerempli = '';
$emailTAPrerempli = '';

// Si l'étudiant à déjà une ligne dans la table convention_contrat avec une offre de M2, on récupère les infos autour de cette offre et de la convention associée
/* Requete pour récuperer les infos de l'étudiant dans la BDD, on se repère grâce à la variable de session "$_SESSION['user'] */
$req = $bdd->prepare("SELECT
                            utilisateur.nom,
                            utilisateur.prenom,
                            utilisateur.email,
                            utilisateur.numEtu,
                            utilisateur.parcours,
                            utilisateur.etatCM2,
                            convention_contrat.type_contrat,
                            convention_contrat.statut_contrat,
                            convention_contrat.dateDeb,
                            convention_contrat.dateFin,
                            convention_contrat.gratification,
                            offre.titre,
                            tuteur_academique.idTA,
                            tuteur_academique.nomTA,
                            tuteur_academique.prenomTA,
                            tuteur_academique.emailTA,
                            maitre_de_stage.nomMDS,
                            maitre_de_stage.prenomMDS,
                            maitre_de_stage.emailMDS,
                            site.nomSite,
                            site.pays,
                            site.ville,
                            site.code_postal,
                            entreprise.nomEntreprise
                        FROM
                            utilisateur
                        LEFT JOIN convention_contrat ON utilisateur.idUtilisateur = convention_contrat.idUtilisateur
                        LEFT JOIN tuteur_academique ON convention_contrat.idTA = tuteur_academique.idTA
                        LEFT JOIN maitre_de_stage ON convention_contrat.idMDS = maitre_de_stage.idMDS
                        LEFT JOIN offre ON convention_contrat.idOffre = offre.idOffre
                        LEFT JOIN site ON maitre_de_stage.idSite = site.idSite
                        LEFT JOIN entreprise ON site.idEntreprise = entreprise.idEntreprise 
                        WHERE utilisateur.statut='etudiant' AND utilisateur.idUtilisateur=:user AND offre.niveau='M2' ORDER BY nom");
$req->bindParam(':user', $_SESSION['user']);
$req->execute();
$resultat = $req->fetch();

// On initialise les variables
$selectedParcours1 = '';
$selectedParcours2 = '';
$selectedParcours3 = '';

$selectedRecherche1 = '';
$selectedRecherche2 = '';
$selectedRecherche3 = '';
$selectedRecherche4 = '';

$selectedNatContrat1 = '';
$selectedNatContrat2 = '';
$selectedNatContrat3 = '';

$selectedStatutContrat1 = '';
$selectedStatutContrat2 = '';

$selectedRemu1 = '';
$selectedRemu2 = '';
$selectedRemu3 = '';
$selectedRemu4 = '';
$selectedRemu5 = '';
$selectedRemu6 = '';

$selectedTA1 = '';
$selectedTA2 = '';
$selectedTA3 = '';
$selectedTA4 = '';
$selectedTA5 = '';

if ($resultat) {
    $emailEtuPrerempli = $resultat['email'];
    $numEtuPrerempli = $resultat['numEtu'];
    $nomEtuPrerempli = $resultat['nom'];
    $prenomEtuPrerempli = $resultat['prenom'];
    $parcoursPrerempli = $resultat['parcours'];
    $recherchePrerempli = $resultat['etatCM2'];
    $natureContratPrerempli = $resultat['type_contrat'];
    $rsEntreprisePrerempli = $resultat['nomEntreprise'];
    $siteEntreprisePrerempli = $resultat['nomSite'];
    $serviceEntreprisePrerempli = $resultat['titre'];
    $paysEntreprisePrerempli = $resultat['pays'];
    $villeEntreprisePrerempli = $resultat['ville'];
    $cpEntreprisePrerempli = $resultat['code_postal'];
    $contratPrerempli = $resultat['statut_contrat'];
    $debutStagePrerempli = $resultat['dateDeb'];
    $finStagePrerempli = $resultat['dateFin'];
    $nomMDSPrerempli = $resultat['nomMDS'];
    $prenomMDSPrerempli = $resultat['prenomMDS'];
    $emailMDSPrerempli = $resultat['emailMDS'];
    $remunerationPrerempli = $resultat['gratification'];
    $idTAPrerempli = $resultat['idTA'];
    $nomTAPrerempli = $resultat['nomTA'];
    $prenomTAPrerempli = $resultat['prenomTA'];
    $emailTAPrerempli = $resultat['emailTA'];

    /* Ces variables vont permettre de préremplir les choix dans les types 'radios'. On les met à jour selon ce que la base de données renvoie. */

    if ($idTAPrerempli != "") {
        if($idTAPrerempli == "1"){
            $selectedTA1 = "selected";
        }
        if($idTAPrerempli == "2"){
            $selectedTA2 = "selected";
        }
        if($idTAPrerempli == "3"){
            $selectedTA3 = "selected";
        }
        if($idTAPrerempli == "4"){
            $selectedTA4 = "selected";
        }
    } else {
        $selectedTA5 = "selected";
    }

    if ($parcoursPrerempli != "") {
        if ($parcoursPrerempli == "ECMPS") {
            $selectedParcours1 = "checked";
        }
        if ($parcoursPrerempli == "GCell") {
            $selectedParcours2 = "checked";
        }
        if ($parcoursPrerempli == "GPhy") {
            $selectedParcours3 = "checked";
        }
    }

    if ($recherchePrerempli != "") {
        if ($recherchePrerempli == "pas commence") {
            $selectedRecherche1 = "checked";
            $choix = "Recherche1";
        }
        if ($recherchePrerempli == "en recherche") {
            $selectedRecherche2 = "checked";
            $choix = "Recherche2";
        }
        if ($recherchePrerempli == "en attente") {
            $selectedRecherche3 = "checked";
            $choix = "Recherche3";
        }
        if ($recherchePrerempli == "accepte") {
            $selectedRecherche4 = "checked";
            $choix = "Recherche4";
        }
    } else {
        $choix = "";
    }

    if ($natureContratPrerempli != "") {
        if ($natureContratPrerempli == "apprentissage") {
            $selectedNatContrat1 = "checked";
        } elseif ($natureContratPrerempli == "pro") {
            $selectedNatContrat2 = "checked";
        } elseif ($natureContratPrerempli == "stage") {
            $selectedNatContrat3 = "checked";
        }
    }

    if ($contratPrerempli != "") {
        if ($contratPrerempli == "Traitement") {
            $selectedStatutContrat1 = "checked";
            $choix2 = "Statut1";
        }
        if ($contratPrerempli == "Signe") {
            $selectedStatutContrat2 = "checked";
            $choix2 = "Statut2";
        }
    } else {
        $choix2 = "";
    }

    if ($remunerationPrerempli != "") {
        if ($remunerationPrerempli == "<800") {
            $selectedRemu1 = "checked";
        }
        if ($remunerationPrerempli == "800-1000") {
            $selectedRemu2 = "checked";
        }
        if ($remunerationPrerempli == "1000-1200") {
            $selectedRemu3 = "checked";
        }
        if ($remunerationPrerempli == "1200-1400") {
            $selectedRemu4 = "checked";
        }
        if ($remunerationPrerempli == "1400-1600") {
            $selectedRemu5 = "checked";
        }
        if ($remunerationPrerempli == ">1600") {
            $selectedRemu6 = "checked";
        }
    }
} else {
    $reqUtilisateur = $bdd->prepare("
                SELECT
                    nom, prenom, email, numEtu, parcours, etatCM2
                FROM
                    utilisateur
                WHERE statut='etudiant' AND idUtilisateur=:user");
    $reqUtilisateur->bindParam(':user', $_SESSION['user']);
    $reqUtilisateur->execute();
    $resultatUtilisateur = $reqUtilisateur->fetch();

    $emailEtuPrerempli = $resultatUtilisateur['email'];
    $numEtuPrerempli = $resultatUtilisateur['numEtu'];
    $nomEtuPrerempli = $resultatUtilisateur['nom'];
    $prenomEtuPrerempli = $resultatUtilisateur['prenom'];
    $parcoursPrerempli = $resultatUtilisateur['parcours'];
    $recherchePrerempli = $resultatUtilisateur['etatCM2'];

    if ($parcoursPrerempli != "") {
        if ($parcoursPrerempli == "ECMPS") {
            $selectedParcours1 = "checked";
        }
        if ($parcoursPrerempli == "GCell") {
            $selectedParcours2 = "checked";
        }
        if ($parcoursPrerempli == "GPhy") {
            $selectedParcours3 = "checked";
        }
    }
    if ($recherchePrerempli != "") {
        if ($recherchePrerempli == "pas commence") {
            $selectedRecherche1 = "checked";
            $choix = "Recherche1";
        }
        if ($recherchePrerempli == "en recherche") {
            $selectedRecherche2 = "checked";
            $choix = "Recherche2";
        }
        if ($recherchePrerempli == "en attente") {
            $selectedRecherche3 = "checked";
            $choix = "Recherche3";
        }
        if ($recherchePrerempli == "accepte") {
            $selectedRecherche4 = "checked";
            $choix = "Recherche4";
        }
    } else {
        $choix = "";
    }
}

// Savoir si l'offre de M2 découle du stage de M1
// On prend l'idOffre du stage de M1
$M1M2Oui = "";
$M1M2Non = "";

// On regarde d'abord si l'utilsateur a une convention de M2
$searchM2 = $bdd -> prepare ('SELECT * FROM convention_contrat JOIN offre USING (idOffre) WHERE idUtilisateur = ? AND niveau = "M2"');
$searchM2 -> execute (array($_SESSION['user']));
$resultatM2 = $searchM2 -> fetch();

if ($resultatM2 != null) {
    $searchM1 = $bdd -> prepare ('SELECT * FROM convention_contrat JOIN offre USING (idOffre) WHERE idUtilisateur = ? AND niveau = "M1"');
    $searchM1 -> execute (array($_SESSION['user']));
    $resultatM1 = $searchM1 -> fetch();
    if ($resultatM1 != null) {
        $idOffreM2 = $resultatM1['idOffre'];

        // On regarde si idOffreM2 de l'offre de M2 est égal a l'idOffre de M1 ($idOffreM2)
        $searchM1M2 = $bdd->prepare('SELECT * FROM convention_contrat JOIN offre USING (idOffre) WHERE idUtilisateur = ? AND niveau = "M2" AND idOffreM2 = ?');
        $searchM1M2->execute(array($_SESSION['user'], $idOffreM2));
        $resultatM1M2 = $searchM1M2->fetch();
        if ($resultatM1M2 != null) {
            $M1M2Oui = "checked";
        } else {
            $M1M2Non = "checked";
        }
    }
}
?>

<!-- Les styles pour quelques div spéciales -->
<style>
    input[type="email"] {
        border: none;  /* Supprime toutes les bordures par défaut */
        border-bottom: 1px solid black;  /* Ajoute une bordure noire en bas */
    }
    /* Le "focus" c'est quand l'utilisateur clique sur la boîte de texte à remplir. On enlève donc aussi ces bordures */
    input[type="email"]:focus {
        outline: none;  /* Supprime l'effet de focus par défaut */
        border-bottom: 1px solid black;  /* Garde la bordure noire en focus */
    }

    input[type="text"] {
        border: none;
        border-bottom: 1px solid black;
    }

    input[type="text"]:focus {
        outline: none;
        border-bottom: 1px solid black;
    }

    /* Cacher l'apparence par défaut du bouton radio */
    input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 1px solid black;
    }

    /* Appliquer un style personnalisé lorsqu'il est coché */
    input[type="radio"]:checked {
        background-color: black;
    }

    .smaller-text {
        font-size: 90%;
    }

    /* Grise les éléments que l'on ne peut pas modifier */
    .readonly-input {
        background-color: #f0f0f0; /* Gris clair */
    }

    input[type=radio]:disabled {
        filter: grayscale(100%) opacity(0.5); /* Rend le bouton complètement gris et réduit l'opacité */
    }
</style>

<!-- Le body de la page -->
<div id="layoutSidenav_content">
    <main>
        <!-- Cette partie concerne le texte avant le début du formulaire. -->
        <div class="info">
            <div class="container-fluid px-4">
                <h1 class="mt-4">GPhy-GCell-ECMPS-M2 - Stages & Alternances <?php echo $_SESSION['promo']-1;?>/<?php echo $_SESSION['promo'];?></h1>
            </div>
            <div class="container-fluid px-4">
                Ce formulaire concerne les stages ou alternance de Master-2, pour l'année <?php echo $_SESSION['promo']-1;?>/<?php echo $_SESSION['promo'];?>
                (promos ECMPS-<?php echo $_SESSION['promo'];?>, GCell-<?php echo $_SESSION['promo'];?> et GPhy-<?php echo $_SESSION['promo'];?>).
            </div>
            <br>
            <div class="container-fluid px-4">
                Après votre première saisie, vous pourrez mettre vos saisies à jour en allant sur votre profil.
            </div>
            <br>
            <div class="container-fluid px-4">
                Vous devez faire une saisie à chacun des événements suivants:
            </div>
            <!-- &nbsp; correspond à un espace en HTML. On l'utilise pour que la liste soit plus visible. -->
            <div class="container-fluid px-4">
                <?php echo $espaces5 ?>- quand vous commencez à rechercher un stage ou une alternance M2,
                <br>
                <?php echo $espaces5 ?>- quand une entreprise vous fait une offre,
                <br>
                <?php echo $espaces5 ?>- quand vous avez accepté une offre qui vous a été faite, et que vous entamez la
                préparation du contrat,
                <br>
                <?php echo $espaces5 ?>- quand vous avez signé votre contrat,
                <br>
                <?php echo $espaces5 ?>- quand vous avez connaissance de l'identité de votre tuteur académique.
            </div>
            <br>
            <div class="container-fluid px-4">
            <span style="color: red;">* Indique une question obligatoire</span>
            </div>
            <br>
        </div>

        <div class="Formulaire">
            <div class="container-fluid px-4">
                <form method="POST" action="traitement.php" onsubmit="updateHiddenFields()">
                    <!-- Question 1 -->

                    <label for="emailEtu">1.<?php echo $espaces5 ?>Adresse e-mail <span style="color: red;">*</span></label><br><br>
                    <?php echo $espaces8 ?><input type="email" id="emailEtu" name="emailEtu" value="<?php echo $emailEtuPrerempli ?>" required readonly class="readonly-input"><br><br>

                    <!-- Question 2 -->
                    <label for="numEtu">2.<?php echo $espaces5 ?>Numéro étudiant <span style="color: red;">*</span></label><br><br>
                    <?php echo $espaces8 ?><input type="text" id="numEtu" name="numEtu" value="<?php echo $numEtuPrerempli ?>" required><br><br>

                    <!-- Question 3 -->
                    <label for="nomEtu">3.<?php echo $espaces5 ?>Nom <span style="color: red;">*</span></label><br>
                    <?php echo $espaces8 ?><span class="smaller-text">Votre nom</span><br><br>
                    <?php echo $espaces8 ?><input type="text" id="nomEtu" name="nomEtu" value="<?php echo $nomEtuPrerempli ?>" required readonly class="readonly-input"><br><br>

                    <!-- Question 4 -->
                    <label for="prenomEtu">4.<?php echo $espaces5 ?>Prénom <span style="color: red;">*</span></label><br>
                    <?php echo $espaces8 ?><span class="smaller-text">Votre prénom</span><br><br>
                    <?php echo $espaces8 ?><input type="text" id="prenomEtu" name="prenomEtu" value="<?php echo $prenomEtuPrerempli ?>" required readonly class="readonly-input"><br><br>

                    <!-- Question 5 -->
                    <label for="parcoursEtu">5.<?php echo $espaces5 ?>Parcours <span style="color: red;">*</span></label><br>
                    <?php echo $espaces8 ?><span class="smaller-text"><i>Une seule réponse possible</i></span><br><br>
                    <div>
                        <?php echo $espaces8 ?><input type="radio" id="ECMPS" name="parcoursEtu" value="ECMPS" <?php echo $selectedParcours1 ?> >
                        <label for="ECMPS">ECMPS</label>
                    </div>
                    <div>
                        <?php echo $espaces8 ?><input type="radio" id="GCell" name="parcoursEtu" value="GCell" <?php echo $selectedParcours2 ?> >
                        <label for="GCell">GCell</label>
                    </div>
                    <div>
                        <?php echo $espaces8 ?><input type="radio" id="GPhy" name="parcoursEtu" value="GPhy" <?php echo $selectedParcours3 ?> >
                        <label for="GPhy">GPhy</label>
                    </div>
                    <br>

                    <!-- Question 6 -->
                    <label for="statutEtu">6.<?php echo $espaces5 ?>Mon statut... <span style="color: red;">*</span></label><br>
                    <?php echo $espaces8 ?><span class="smaller-text"><i>Une seule réponse possible</i></span><br><br>
                    <div>
                        <?php echo $espaces8 ?><input type="radio" id="Recherche1" name="statutEtu" value="pas commence" <?php echo $selectedRecherche1 ?>>
                        <label for="Recherche1">Pas encore en recherche</label>
                    </div>
                    <div>
                        <?php echo $espaces8 ?><input type="radio" id="Recherche2" name="statutEtu" value="en recherche" <?php echo $selectedRecherche2 ?>>
                        <label for="Recherche2">En recherche, sans propositions</label>
                    </div>
                    <div>
                        <?php echo $espaces8 ?><input type="radio" id="Recherche3" name="statutEtu" value="en attente" <?php echo $selectedRecherche3 ?>>
                        <label for="Recherche3">J'ai des propositions, que j'étudie.</label>
                    </div>
                    <div>
                        <?php echo $espaces8 ?><input type="radio" id="Recherche4" name="statutEtu" value="accepte" <?php echo $selectedRecherche4 ?>>
                        <label for="Recherche4">J'ai accepté une offre <?php echo $espaces5 ?> <i>Passer à la question 7</i></label>
                    </div>
                    <br>

                    <!-- A partir de là, affichage seulement si l'étudiant à accepté une offre. -->
                    <div id="additionalFields" style="display: none;">
                        <p>Éléments de l'offre</p>
                        <span class="smaller-text"><p>À ce niveau du formulaire, votre convention d'apprentissage, de stage ou de contrat pro
                        est établie, et en cours de signature. Donc vous devez connaître l'ensemble des
                        informations demandées ici.</p></span>

                        <!-- Question 7 -->
                        <label for="OffreM1">7.<?php echo $espaces5 ?>Offre découlant du stage de M1 ? <span style="$color: red;">*</span></label><br>
                        <?php echo $espaces8 ?><span class="smaller-text"><i>Si l'offre que vous avez accepté est une continuation de votre stage de M1, cochez "oui".</i></span><br><br>
                        <div>
                            <?php echo $espaces8 ?><input type="radio" id="OuiM1" name="OffreM1" value="ouim1" <?php echo $M1M2Oui ?>>
                            <label for="OuiM1">Oui</label>
                        </div>
                        <div>
                            <?php echo $espaces8 ?><input type="radio" id="NonM1" name="OffreM1" value="nonm1" <?php echo $M1M2Non ?>>
                            <label for="NonM1">Non</label>
                        </div>
                        <br>

                        <!-- Question 8 -->
                        <label for="natureEtu">8.<?php echo $espaces5 ?>Nature <span style="color: red;">*</span></label><br>
                        <?php echo $espaces8 ?><span class="smaller-text"><i>Une seule réponse possible</i></span><br><br>
                        <div>
                            <?php echo $espaces8 ?><input type="radio" id="Apprentissage" name="natureEtu" value="apprentissage" <?php echo $selectedNatContrat1 ?>>
                            <label for="Apprentissage">Apprentissage</label>
                        </div>
                        <div>
                            <?php echo $espaces8 ?><input type="radio" id="ContratPro" name="natureEtu" value="pro" <?php echo $selectedNatContrat2 ?>>
                            <label for="ContratPro">Contrat Pro</label>
                        </div>
                        <div>
                            <?php echo $espaces8 ?><input type="radio" id="Stage" name="natureEtu" value="stage" <?php echo $selectedNatContrat3 ?>>
                            <label for="Stage">Stage</label>
                        </div>
                        <br>

                        <span style="color: red;">Si vous avez coché "oui" à la question 7, vous n'avez pas besoin de remplir les questions de 9 à 14 inclus.</span>
                        <br>
                        <br>

                        <!-- Question 9 -->
                        <label for="RSEntreprise">9.<?php echo $espaces5 ?>Entreprise <span style="color: red;">*</span></label><br>
                        <?php echo $espaces8 ?><span class="smaller-text">Raison sociale de la société</span><br><br>
                        <?php echo $espaces8 ?><input type="text" id="RSEntreprise" name="RSEntreprise" value="<?php echo $rsEntreprisePrerempli ?>"><br><br>

                        <!-- Question 10 -->
                        <label for="SiteEntreprise">10.<?php echo $espaces3 ?>Site d'entreprise</label><br>
                        <?php echo $espaces8 ?><span class="smaller-text">Si l'entreprise comporte plusieurs sites industriels, désignez celui dans lequel vous travaillerez. ATTENTION : ON NE DEMANDE PAS ICI LE SITE WEB DE L'ENTREPRISE.</span><br><br>
                        <?php echo $espaces8 ?><input type="text" id="SiteEntreprise" name="SiteEntreprise" value="<?php echo $siteEntreprisePrerempli ?>"><br><br>

                        <!-- Question 11 -->
                        <label for="ServiceEntreprise">11.<?php echo $espaces3 ?>Service</label><br>
                        <?php echo $espaces8 ?><span class="smaller-text">Si l'entreprise ou le site d'entreprise comporte plusieurs services, désignez celui dans lequel vous travaillerez.</span><br><br>
                        <?php echo $espaces8 ?><input type="text" id="ServiceEntreprise" name="ServiceEntreprise" value="<?php echo $serviceEntreprisePrerempli ?>"><br><br>

                        <!-- Question 12 -->
                        <label for="PaysEntreprise">12.<?php echo $espaces3 ?>Pays <span style="color: red;">*</span></label><br>
                        <?php echo $espaces8 ?><span class="smaller-text">Pays d'activité</span><br><br>
                        <?php echo $espaces8 ?><input type="text" id="PaysEntreprise" name="PaysEntreprise" value="<?php echo $paysEntreprisePrerempli ?>"><br><br>

                        <!-- Question 13 -->
                        <label for="VilleEntreprise">13.<?php echo $espaces3 ?>Ville <span style="color: red;">*</span></label><br>
                        <?php echo $espaces8 ?><span class="smaller-text">Ville au sein de laquelle se trouve le site d'entreprise au sein duquel vous travaillerez.</span><br><br>
                        <?php echo $espaces8 ?><input type="text" id="VilleEntreprise" name="VilleEntreprise" value="<?php echo $villeEntreprisePrerempli ?>"><br><br>

                        <!-- Question 14 -->
                        <label for="CPEntreprise">14.<?php echo $espaces3 ?>Code Postal <span style="color: red;">*</span></label><br>
                        <?php echo $espaces8 ?><span class="smaller-text">Code Postal de la ville en question, au format international (par exemple F-72300 ou B-8876)</span><br><br>
                        <?php echo $espaces8 ?><input type="text" id="CPEntreprise" name="CPEntreprise" value="<?php echo $cpEntreprisePrerempli ?>"><br><br>

                        <!-- Question 15 -->
                        <label for="StatutContrat">15.<?php echo $espaces3 ?>Statut <span style="color: red;">*</span></label><br>
                        <?php echo $espaces8 ?><span class="smaller-text">[Traitement] vous avez accepté la proposition, le contrat est en cours de rédaction, il n’est pas encore signé.
                        <br><?php echo $espaces8 ?>&nbsp;[Signé] le contrat est signé par vous et par l’entreprise, il a été remis au CFA ou à UP-Pro.</span><br><br>
                        <?php echo $espaces8 ?><span class="smaller-text"><i>Une seule réponse possible</i></span><br><br>
                        <div>
                            <?php echo $espaces8 ?><input type="radio" id="Statut1" name="StatutContrat" value="Traitement" <?php echo $selectedStatutContrat1 ?>>
                            <label for="Statut1">Traitement</label>
                        </div>
                        <div>
                            <?php echo $espaces8 ?><input type="radio" id="Statut2" name="StatutContrat" value="Signe" <?php echo $selectedStatutContrat2 ?>>
                            <label for="Statut2">Signé <?php echo $espaces5 ?> <i>Passer à la question 16</i></label>
                        </div>
                        <br>

                        <!-- A partir de là, affichage seulement si l'étudiant à signé un contrat. -->
                        <div id="additionalFields2" style="display: none;">
                            <p>Éléments opérationnels du contrat</p>
                            <!-- Question 16 -->
                            <label for="DebContrat">16.<?php echo $espaces3 ?>Début <span style="color: red;">*</span></label><br>
                            <?php echo $espaces8 ?><span class="smaller-text">Date de début du stage ou du contrat d'alternance (indiquée sur la convention ou sur le contrat)</span><br><br>
                            <?php echo $espaces8 ?><input type="text" id="DebContrat" name="DebContrat" value="<?php echo $debutStagePrerempli ?>"><br>
                            <?php echo $espaces8 ?><span class="smaller-text"><i>Exemple : 7 janvier 2019</i></span><br><br>

                            <!-- Question 17 -->
                            <label for="FinContrat">17.<?php echo $espaces3 ?>Fin <span style="color: red;">*</span></label><br>
                            <?php echo $espaces8 ?><span class="smaller-text">Date de fin du stage ou du contrat d'alternance (indiquée sur la convention ou sur le contrat)</span><br><br>
                            <?php echo $espaces8 ?><input type="text" id="FinContrat" name="FinContrat" value="<?php echo $finStagePrerempli ?>"><br>
                            <?php echo $espaces8 ?><span class="smaller-text"><i>Exemple : 7 janvier 2019</i></span><br><br>

                            <!-- Question 18 -->
                            <label for="NomMDS">18.<?php echo $espaces3 ?>Nom du MDS <span style="color: red;">*</span></label><br>
                            <?php echo $espaces8 ?><span class="smaller-text">Nom du maître de stage ou alternance</span><br><br>
                            <?php echo $espaces8 ?><input type="text" id="NomMDS" name="NomMDS" value="<?php echo $nomMDSPrerempli ?>"><br><br>

                            <!-- Question 19 -->
                            <label for="PrenomMDS">19.<?php echo $espaces3 ?>Prénom du MDS <span style="color: red;">*</span></label><br>
                            <?php echo $espaces8 ?><span class="smaller-text">Prénom du maître de stage ou alternance</span><br><br>
                            <?php echo $espaces8 ?><input type="text" id="PrenomMDS" name="PrenomMDS" value="<?php echo $prenomMDSPrerempli ?>"><br><br>

                            <!-- Question 20 -->
                            <label for="EmailMDS">20.<?php echo $espaces3 ?>EMail du MDS <span style="color: red;">*</span></label><br>
                            <?php echo $espaces8 ?><span class="smaller-text">Adresse électronique du maître de stage ou d'alternance.</span><br><br>
                            <?php echo $espaces8 ?><input type="text" id="EmailMDS" name="EmailMDS" value="<?php echo $emailMDSPrerempli ?>"><br><br>

                            <!-- Question 21 -->
                            <label for="Rémunération">21.<?php echo $espaces3 ?>Rémunération</label><br>
                            <?php echo $espaces8 ?><span class="smaller-text">Net mensuel. Cette information est utilisée uniquement à des fins statistiques, et de manière anonyme.</span><br>
                            <?php echo $espaces8 ?><span class="smaller-text"><i>Une seule réponse possible</i></span><br><br>
                            <div>
                                <?php echo $espaces8 ?><input type="radio" id="Rému1" name="Rémunération" value="<800" <?php echo $selectedRemu1 ?>>
                                <label for="Rému1">Rém. < 800 €</label>
                            </div>
                            <div>
                                <?php echo $espaces8 ?><input type="radio" id="Rému2" name="Rémunération" value="800-1000" <?php echo $selectedRemu2 ?>>
                                <label for="Rému2">800 € ≤ Rém. < 1000 € <?php echo $espaces5 ?></label>
                            </div>
                                <div>
                                <?php echo $espaces8 ?><input type="radio" id="Rému3" name="Rémunération" value="1000-1200" <?php echo $selectedRemu3 ?>>
                                <label for="Rému3">1000 € ≤ Rém. < 1200 €</label>
                            </div>
                            <div>
                                <?php echo $espaces8 ?><input type="radio" id="Rému4" name="Rémunération" value="1200-1400" <?php echo $selectedRemu4 ?>>
                                <label for="Rému4">1200 € ≤ Rém. < 1400 €</label>
                            </div>
                                <div>
                                <?php echo $espaces8 ?><input type="radio" id="Rému5" name="Rémunération" value="1400-1600" <?php echo $selectedRemu5 ?>>
                                <label for="Rému5">1400 € ≤ Rém. < 1600 €</label>
                            </div>
                            <div>
                                <?php echo $espaces8 ?><input type="radio" id="Rému6" name="Rémunération" value=">1600" <?php echo $selectedRemu6 ?>>
                                <label for="Rému6">1600 € ≤ Rém.</label>
                            </div>
                            <br>

                            <p>Tuteur académique</p>
                            <span class="smaller-text"><p>Le tuteur académique est l'enseignant de l'université inscrit sur votre convention de stage ou sur votre contrat d'alternance.</p></span>

                            <!-- Question 22 -->
                            <label for="NomTA">22.<?php echo $espaces3 ?>Nom et prénom Tuteur <span style="color: red;">*</span></label><br>
                            <?php echo $espaces8 ?><span class="smaller-text">Nom et prénom du tuteur académique</span><br><br>
                            <select  name="NomTA">
                                <option
                                    <?php echo $selectedTA1?>
                                        value = "thierry">URRUTY Thierry</option>
                                <option
                                    <?php echo $selectedTA2?>
                                        value = "patrick">GIRARD Patrick</option>
                                <option
                                    <?php echo $selectedTA3?>
                                        value = "dominique">GENIET Dominique</option>
                                <option
                                    <?php echo $selectedTA4?>
                                        value = "allan">FOUSSE Allan</option>
                                <option
                                    <?php echo $selectedTA5?>
                                        value = "vide"></option>
                            </select>
                            <br><br>

                            <label for="EmailTA"><?php echo $espaces3 ?>Adresse électronique du Tuteur (automatique)</label><br><br>
                            <?php echo $espaces8 ?><input type="email" id="EmailTA" name="EmailTA" value="<?php echo $emailTAPrerempli ?>" readonly class="readonly-input"><br><br><br>
                        </div>
                    </div>
                    <input type="submit" name="Validation" value="Envoyer">
                </form>
            </div>
        </div>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>

<!-- Un peu de Javascript pour préremplir les inputs dont on connait déjà la réponse -->
<script>
    // Script JS pour que la suite du formulaire devienne accessible si l'étudiant répond "j'ai accepté une offre" à la question 6
    let choix = "<?php echo $choix ?>";
    let choix2 = "<?php echo $choix2 ?>";

    document.addEventListener("DOMContentLoaded", function() {
        let additionalFields = document.getElementById("additionalFields");
        let additionalFields2 = document.getElementById("additionalFields2");

        // Vérifie la valeur de la variable  "choix" et affiche/masque la section du formulaire
        if (choix === "accepte") {
            additionalFields.style.display = "block";
        } else {
            additionalFields.style.display = "none";
        }

        // Écoute les changements de sélection du choix radio pour "statutEtu"
        let choixRadio = document.getElementsByName("statutEtu");
        for (let i = 0; i < choixRadio.length; i++) {
            choixRadio[i].addEventListener("click", function() {
                console.log("Selected value:", this.value);
                if (this.value === "accepte") {
                    additionalFields.style.display = "block";
                } else {
                    additionalFields.style.display = "none";
                }
            });
        }

        // Vérifie la valeur de la variable "choix2" et affiche/masque la section du formulaire
        if (choix2 === "Statut2") {
            additionalFields2.style.display = "block";
        } else {
            additionalFields2.style.display = "none";
        }

        // Écoute les changements de sélection du choix radio pour "StatutContrat"
        let choixRadio2 = document.getElementsByName("StatutContrat");
        for (let i = 0; i < choixRadio2.length; i++) {
            choixRadio2[i].addEventListener("click", function() {
                console.log("Selected value:", this.value);
                if (this.value === "Signe") {
                    additionalFields2.style.display = "block";
                } else {
                    additionalFields2.style.display = "none";
                }
            });
        }
    });
</script>