<?php
include('barre_nav_admin.php');
include('fonctionality/bdd.php');
?>
<style>
    /* Style pour la pop-up */
    .popup {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .popup .popup-content {
        visibility: hidden;
        width: max-content;
        background-color: #f8f9fa;
        color: #000;
        text-align: center;
        border-radius: 5px;
        padding: 10px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -100px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .popup:hover .popup-content {
        visibility: visible;
        opacity: 1;
    }
    /* Style pour la colonne rétractée */
    .toggleable-column.collapsed {
        display: none;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Body de la page -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Suivi des étudiants post-M1</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"></li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <button onclick="bottomFunction()" id="scrollBottomBtn" class="btn btn-secondary" title="Aller en bas de la page">Bas de la page</button>
                    <script>
                        // Fonction pour aller en bas de la page
                        function bottomFunction() {
                            window.scrollTo(0, document.body.scrollHeight); // Fait défiler vers le bas de la page
                        }
                    </script>
                </div>
                <!-- Tableau affichage tous les étudiants et leurs choix -->
                <!-- Certaines parties du tableau sont en noir pour mieux différencier les groupes d'informations -->
                <div class="card-body">
                    <a href="export_excel.php">
                        <button type="button" class="btn btn-outline-success"><img src="assets/img/excel.png" alt="Icône"> Exportation Excel</button></a>

                    <div class="table-responsive">
                        <br>
                        <table id="datatablesSimple" class="table table-striped table-bordered table-sm">
                            <thead>
                            <tr>
                                <th style="border-left : 2px solid black; border-top : 2px solid black;">Nom Prénom</th>
                                <th style="border-top : 2px solid black;">
                                    <div class="popup" id="icon">
                                        <img src="assets/img/communication.png" alt="Icône">
                                        <span class="popup-content" style="position: absolute; top: -55px; left: 60px;"> Contacts étudiants</span>
                                    </div>
                                </th>
                                <th style="border-top : 2px solid black; max-width: 200px;">Parcours</th>
                                <th style="border-top : 2px solid black; max-width: 200px;">Promo</th>
                                <th style="border-top : 2px solid black; max-width: 200px;">État de la recherche</th>
                                <th style="border-top : 2px solid black; max-width: 200px;">Statut Contrat</th>
                                <th style="border-top : 2px solid black; max-width: 200px;">Nature</th>
                                <th style="border-top : 2px solid black; max-width: 200px;">Entreprise</th>
                                <th style="border-top : 2px solid black; max-width: 200px;">Site</th>
                                <th style="border-top : 2px solid black; max-width: 200px;">Service</th>
                                <th style="border-top : 2px solid black; max-width: 200px;">Ville</th>
                                <th style="border-left : 2px solid black; border-top : 2px solid black;">MDS</th>
                                <th style="border-top : 2px solid black;">
                                    <div class="popup" id="icon">
                                        <img src="assets/img/communication.png" alt="Icône">
                                        <span class="popup-content"> Contact MDS</span>
                                    </div>
                                </th>
                                <th style="border-left : 2px solid black;border-right : 2px solid black; border-top : 2px solid black;">Tuteur académique</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php

                            /* Recupération de la requête et affichage de toutes les données dans un tableau */
                            $req = $bdd->prepare("SELECT
                            utilisateur.idUtilisateur,
                            utilisateur.nom,
                            utilisateur.prenom,
                            utilisateur.email,
                            utilisateur.numTel,
                            utilisateur.etatC,
                            utilisateur.parcours,
                            utilisateur.typeAnnee,
                            utilisateur.etatCM2,
                            tuteur_academique.nomTA,
                            tuteur_academique.prenomTA,
                            tuteur_academique.emailTA,
                            tuteur_academique.numTA,
                            maitre_de_stage.nomMDS,
                            maitre_de_stage.prenomMDS,
                            maitre_de_stage.emailMDS,
                            maitre_de_stage.numMDS,
                            entreprise.nomEntreprise,
                            ville,
                            site.nomSite,
                            convention_contrat.idConvention,
                            convention_contrat.type_contrat,
                            convention_contrat.statut_contrat
                        FROM
                            utilisateur
                        LEFT JOIN convention_contrat ON utilisateur.idUtilisateur = convention_contrat.idUtilisateur
                        LEFT JOIN offre ON convention_contrat.idOffre = offre.idOffre
                        LEFT JOIN tuteur_academique ON convention_contrat.idTA = tuteur_academique.idTA
                        LEFT JOIN maitre_de_stage ON convention_contrat.idMDS = maitre_de_stage.idMDS
                        LEFT JOIN site ON maitre_de_stage.idSite = site.idSite
                        LEFT JOIN entreprise ON site.idEntreprise = entreprise.idEntreprise
                        WHERE statut='etudiant' AND promo = ? AND utilisateur.parcours = ? AND (utilisateur.typeAnnee = 'M2' OR (utilisateur.typeAnnee = 'M1' AND convention_contrat.type_contrat = 'anticipe')) AND offre.niveau = 'M2' ORDER BY nom");
                            $req->execute(array($promo, $parcours));
                            $resultat = $req->fetchAll();

                            /* Ces deux variables permettent de mettre la dernière ligne du tableau en gras */
                            $i = 0;
                            $totalLigne = count($resultat);

                            foreach ($resultat as $ligne) {
                                $i++;
                                $req2 = $bdd->prepare("SELECT etat_convention, date FROM convention_contrat WHERE idConvention = ? order by idConvention DESC LIMIT 1");
                                $req2->execute(array($ligne['idConvention']));
                                $resultat2 = $req2->fetch();
                                $rowcount = $req2->rowCount();
                                $val = "";
                                $date = "";
                                if ($rowcount != 0){
                                    if ($resultat2['etat_convention']== "preconventionEnvoyee"){
                                        $val = "Préconvention envoyée";}
                                    elseif ($resultat2['etat_convention']== "preconventionRecue"){
                                        $val= "Préconvention reçue"; }
                                    elseif ($resultat2['etat_convention']== "conventionEditee"){
                                        $val= "Convention éditée"; }
                                    elseif ($resultat2['etat_convention']== "conventionEnvoyee"){
                                        $val= "Convention envoyée"; }
                                    else {
                                        $val= "Pas de convention"; }
                                    $date =$resultat2['date'];
                                }
                                if ($ligne['type_contrat'] == 'stage') {
                                    $typecontrat = 'Stage';
                                }
                                elseif ($ligne['type_contrat'] == 'apprentissage') {
                                    $typecontrat = 'Apprentissage';
                                }
                                elseif ($ligne['type_contrat'] == 'pro') {
                                    $typecontrat = 'Contrat Pro';
                                }
                                elseif ($ligne['type_contrat'] == 'anticipe') {
                                    $typecontrat = 'Apprentissage anticipé';
                                }

                                if ($ligne['etatCM2'] == 'accepte') {
                                    $statut_recherche = 'Accepté';
                                }
                                elseif ($ligne['etatCM2'] == 'pas commence') {
                                    $statut_recherche = 'Pas recherche';
                                }
                                elseif ($ligne['etatCM2'] == 'en recherche') {
                                    $statut_recherche = 'En recherche';
                                }
                                elseif ($ligne['etatCM2'] == 'en attente') {
                                    $statut_recherche = 'En attente';
                                }

                                if ($ligne['statut_contrat'] == 'Signe') {
                                    $statut_contrat = 'Signé';
                                }
                                elseif ($ligne['statut_contrat'] == 'Traitement') {
                                    $statut_contrat = 'En traitement';
                                }
                                ?>

                                <tr>
                                    <td style="border-left : 2px solid black; <?php if ($i == $totalLigne) { echo 'border-bottom : 2px solid black;"'; } ?>">
                                        <a href="informations_contrat.php?value=<?php echo $ligne['idUtilisateur']; ?>"><?php echo $ligne['nom']; echo " "; echo $ligne['prenom'];?></a>
                                    </td>

                                    <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                        <a href="Send_mail_etu.php?mail=<?php echo $ligne['email']; ?>"><img src="assets/img/mail.png" alt="Email"></a>
                                        <div class="popup" id="icon">
                                            <?php if ($ligne['numTel'] == Null) {
                                                ?><?php
                                            } else {
                                                ?> <img src="assets/img/Tel.png" alt="Icône"> <?php
                                            } ?>
                                            <span class="popup-content"> Tel : <?php echo $ligne['numTel']; ?></span>
                                        </div>
                                    </td>
                                    <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                        <span><?php echo " "?><?php echo $ligne['parcours'];?></span>
                                    </td>
                                    <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                        <span><?php echo " "?><?php echo $promo;?></span>
                                    </td>
                                    <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                        <span><?php echo $statut_recherche;?></span>
                                    </td>
                                    <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                        <span><?php echo $statut_contrat?></span>
                                    </td>
                                    <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                        <span><?php echo $typecontrat?></span>
                                    </td>
                                    <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                        <?php echo $ligne['nomEntreprise']; ?>
                                    </td>
                                    <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                        <?php echo $ligne['nomSite']; ?>
                                    </td>
                                    <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                        <span>?</span>
                                    </td>
                                    <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                        <?php echo $ligne['ville']; ?>
                                    </td>
                                    <td style="border-left : 2px solid black; <?php if ($i == $totalLigne) { echo 'border-bottom : 2px solid black;"'; } ?>">
                                        <?php echo $ligne['nomMDS']; echo " "; echo $ligne['prenomMDS']; ?>
                                    </td>

                                    <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                        <?php if ($ligne['nomMDS'] != '') {
                                            if ($ligne['emailMDS'] != Null) { ?>
                                                <a href="Send_mail_etu.php?mail=<?php echo $ligne['emailMDS']; ?>"><img src="assets/img/mail.png" alt="Email"></a>
                                            <?php }
                                            if ($ligne['numMDS'] != Null) { ?>
                                                <div class="popup" id="icon">
                                                    <img src="assets/img/Tel.png" alt="Icône">
                                                    <span class="popup-content"> Tel : <?php echo $ligne['numMDS']; ?></span>
                                                </div>
                                            <?php }
                                        } ?>
                                    </td>

                                    <td style="border-right : 2px solid black;border-left : 2px solid black; <?php if ($i == $totalLigne) { echo 'border-bottom : 2px solid black;"'; } ?>">
                                        <?php echo $ligne['nomTA']; echo " "; echo $ligne['prenomTA']; ?>
                                    </td>
                                </tr>

                            <?php } ?>
                            </tbody>
                        </table>

                    </div>
                    <br>
                    <button onclick="topFunction()" class="btn btn-secondary" title="Revenir en haut de la page">Haut de la page</button>

                    <script>
                        // Fonction pour revenir au haut de la page
                        function topFunction() {
                            document.body.scrollTop = 0; // Pour les navigateurs Chrome, Safari et Opera
                            document.documentElement.scrollTop = 0; // Pour les navigateurs Firefox, IE et Edge
                        }
                    </script>
                </div>
            </div>
        </div>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>
<script src="script.js"></script>
