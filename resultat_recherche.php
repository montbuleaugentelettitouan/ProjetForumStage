<?php
/**
 * Resultat de la recherche rapide pour les administrateurs
 *
 * @autor : Thibault NIGGEL, Jules FAVRE
 * @date : Promo GPhy 2025 - Année 2022/2023
 *
 */
include('barre_nav_admin.php');
include('fonctionality/bdd.php');

if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
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

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Résultat de la recherche pour <?php echo $searchQuery ?> :</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"></li>
            </ol>

            <div class="card mb-4">
                <!-- Tableau des résultats de la rechercher -->
                <div class="card-body">
                    <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-striped table-bordered table-sm">
                        <thead>
                        <tr>
                            <th style="border-left : 2px solid black; border-top : 2px solid black;">Nom Prénom</th>
                            <th style="border-top : 2px solid black;">Contact Étudiant</th>
                            <th style="border-top : 2px solid black;">Etat de la recherche</th>
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
                        /* Cette requête permet de récupérer toutes les infos relatives à un étudiant suivant ce qu'on tape dans la
                        barre de recherche (représenté par le ?). Cette recherche va regarder si '?' est contenu dans le nom ou prenom
                        de l'étudiant et si il y a des doublons (même nom et prénom), la recherche prendra celui qui à la promo la plus
                        élevée.
                        */
                        /*bddd(placeholder pour retrouver cette requete, bddd = Requete SQL malfonctionnelle)*/
                        $req = $bdd->prepare("
                        SELECT DISTINCT u.idUtilisateur, u.nom, u.prenom, u.email, u.numTel, e.nomEntreprise, u.etatC, mds.nomMDS, mds.prenomMDS, mds.emailMDS, mds.numMDS, ta.nomTA, ta.prenomTA, ta.emailTA, ta.numTA 
                        FROM utilisateur u
                        LEFT JOIN convention_contrat cc ON u.idUtilisateur = cc.idUtilisateur
                        LEFT JOIN tuteur_academique ta ON cc.idTA = ta.idTA
                        LEFT JOIN maitre_de_stage mds ON cc.idMDS = mds.idMDS
                        LEFT JOIN site s ON mds.idSite = s.idSite
                        LEFT JOIN entreprise e ON s.idEntreprise = e.idEntreprise 
                        WHERE u.statut='etudiant' AND (
                            u.nom LIKE CONCAT('%', ?, '%') OR
                            u.prenom LIKE CONCAT('%', ?, '%') OR
                            u.nom IN (
                                SELECT nom
                                FROM utilisateur
                                WHERE nom = u.nom AND prenom <> u.prenom
                                GROUP BY nom
                                HAVING COUNT(DISTINCT prenom) > 1
                            )
                        )
                        AND (
                            u.promo = (SELECT MAX(promo) FROM utilisateur WHERE nom = u.nom AND prenom = u.prenom) OR
                            NOT EXISTS (SELECT 1 FROM utilisateur WHERE nom = u.nom AND prenom = u.prenom AND promo > u.promo)
                        )
                        AND promo = ? AND parcours = ?
                        ORDER BY u.nom;");

                        $req->execute(array($searchQuery, $searchQuery, $promo, $parcours));
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
                                        <span class="popup-content"> Tel : <?php echo $ligne['numtel']; ?></span>
                                    </div>
                                </td>

                                <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                    <?php echo $ligne['etatC']; ?>
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

<?php } ?>