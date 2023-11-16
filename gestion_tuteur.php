<?php
/**
 * Fonctionnalité de gestion des tuteurs
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
            <h1 class="mt-4">Gestion des tuteurs académiques</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"></li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="far fa-file-pdf"></i>
                    Liste des tuteurs académiques pour la promo <?php echo $parcours; echo " "; echo $promo ?>
                </div>

                <!-- Tableau affichage tous les étudiants et leurs choix -->
                <!-- Certaines parties du tableau sont en noir pour mieux différencier les groupes d'informations -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-striped table-bordered table-sm">
                            <thead>
                            <tr>
                                <th>Nom Prénom</th>
                                <th>Contact Tuteur Académique</th>
                                <th>Modification</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php

                            /* Recupération de la requête et affichage de toutes les données dans un tableau */
                            $req = $bdd->prepare("SELECT DISTINCT nom_tuteur_academique, prenom_tuteur_academique, email_tuteur_academique, num_tuteur_academique
                                FROM stage
                                WHERE nom_tuteur_academique IS NOT NULL AND nom_tuteur_academique NOT LIKE '' AND annee_stage= ? ORDER BY nom_tuteur_academique");
                            $req->execute(array($annee));
                            $resultat = $req->fetchAll();

                            /* Ces deux variables permettent de mettre la dernière ligne du tableau en gras. Cela ne fonctionne pas parfaitement */
                            $i = 0;
                            $totalLigne = count($resultat);

                            foreach ($resultat as $ligne) {
                                $i++;
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $ligne['nom_tuteur_academique']; echo " "; echo $ligne['prenom_tuteur_academique']; ?>
                                    </td>

                                    <td>
                                        <?php if ($ligne['nom_tuteur_academique'] != '') {
                                            if ($ligne['email_tuteur_academique'] != Null) { ?>
                                                &nbsp;
                                                Email :
                                                <a href="Send_mail_etu.php?mail=<?php echo $ligne['email_tuteur_academique']; ?>"><img src="assets/img/mail.png" alt="Email"></a>
                                            <?php }
                                            if ($ligne['num_tuteur_academique'] != Null) { ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Téléphone :
                                                <div class="popup" id="icon">
                                                    <img src="assets/img/Tel.png" alt="Icône">
                                                    <span class="popup-content"> Tel : <?php echo $ligne['num_tuteur_academique']; ?></span>
                                                </div>
                                            <?php }
                                        } ?>
                                    </td>

                                    <td>
                                        <a href="modif_tuteur.php?id=<?php echo $ligne['idTA']; ?>">Modifier</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <p> Pour ajouter un tuteur, <a href = ajout_tuteur.php>cliquez ici</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>