<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */
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
        width: 200px;
        background-color: #f9f9f9;
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
</style>

<!-- Body de la page -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Gestion des étudiants</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"></li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">

                    Liste des étudiants de la promo <?php echo $parcours; echo " "; echo $promo ?>
                </div>

                <!-- Tableau affichage tous les étudiants et leurs choix -->
                <!-- Certaines parties du tableau sont en noir pour mieux différencier les groupes d'informations -->
                <div class="card-body">
                    <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-striped table-bordered table-sm">
                        <thead>
                        <tr>
                            <th style="border-left : 2px solid black; border-top : 2px solid black;">Nom Prénom</th>
                            <th style="border-top : 2px solid black;">Contact Étudiant</th>
                            <th style="border-top : 2px solid black;">État de la recherche</th>
                            <th style="border-top : 2px solid black;">Stage et Convention</th>
                            <th style="border-top : 2px solid black;">Nom Entreprise</th>
                            <th style="border-left : 2px solid black; border-top : 2px solid black;">Nom Prénom Tuteur Stage</th>
                            <th style="border-top : 2px solid black;">Contact Tuteur Stage</th>
                            <th style="border-left : 2px solid black; border-top : 2px solid black;">Nom Prénom Tuteur Académique</th>
                            <th style="border-right : 2px solid black; border-top : 2px solid black;">Contact Tuteur Académique</th>
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
                            tuteur_academique.nomTA,
                            tuteur_academique.prenomTA,
                            tuteur_academique.emailTA,
                            tuteur_academique.numTA,
                            maitre_de_stage.nomMDS,
                            maitre_de_stage.prenomMDS,
                            maitre_de_stage.emailMDS,
                            maitre_de_stage.numMDS,
                            entreprise.nomEntreprise
                        FROM
                            utilisateur
                        LEFT JOIN convention_contrat ON utilisateur.idUtilisateur = convention_contrat.idUtilisateur
                        LEFT JOIN tuteur_academique ON convention_contrat.idTA = tuteur_academique.idTA
                        LEFT JOIN maitre_de_stage ON convention_contrat.idMDS = maitre_de_stage.idMDS
                        LEFT JOIN site ON maitre_de_stage.idSite = site.idSite
                        LEFT JOIN entreprise ON site.idEntreprise = entreprise.idEntreprise 
                        WHERE statut='etudiant' AND promo = ? AND parcours = ? ORDER BY nom");

                        $req->execute(array($promo, $parcours));
                        $resultat = $req->fetchAll();

                        /* Ces deux variables permettent de mettre la dernière ligne du tableau en gras */
                        $i = 0;
                        $totalLigne = count($resultat);

                        foreach ($resultat as $ligne) {
                            $i++;
                            ?>

                            <tr>
                                <td style="border-left : 2px solid black; <?php if ($i == $totalLigne) { echo 'border-bottom : 2px solid black;"'; } ?>">
                                    <a href="dashboardSUIVIFORUM2.php?value=<?php echo $ligne['idUtilisateur'];?>"><?php echo $ligne['nom']; echo " "; echo $ligne['prenom'];?></a>
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
                                    <?php echo $ligne['etatC']; ?>
                                </td>

                                <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                <center><a href="informations_convention.php?value=<?php echo $ligne['idUtilisateur']; ?>" class="btn btn-secondary" style="width: 75%;">Voir</a></center>
                                </td>
                                <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                    <?php echo $ligne['nomEntreprise']; ?>
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

                                <td style="border-left : 2px solid black; <?php if ($i == $totalLigne) { echo 'border-bottom : 2px solid black;"'; } ?>">
                                    <?php echo $ligne['nomTA']; echo " "; echo $ligne['prenomTA']; ?>
                                </td>

                                <td style="border-right : 2px solid black; <?php if ($i == $totalLigne) { echo 'border-bottom : 2px solid black;'; } ?>">
                                    <?php if ($ligne['nomTA'] != '') {
                                        if ($ligne['emailTA'] != Null) { ?>
                                            <a href="Send_mail_etu.php?mail=<?php echo $ligne['emailTA']; ?>"><img src="assets/img/mail.png" alt="Email"></a>
                                        <?php }
                                        if ($ligne['numTA'] != Null) { ?>
                                        <div class="popup" id="icon">
                                            <img src="assets/img/Tel.png" alt="Icône">
                                            <span class="popup-content"> Tel : <?php echo $ligne['numTA']; ?></span>
                                        </div>

                                        <?php }
                                    } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>
<script src="script.js"></script>