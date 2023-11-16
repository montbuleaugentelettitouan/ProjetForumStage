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
include('fonctionality/annee+promo.php');
?>

<!-- Body de la page -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Choix des étudiants</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Vue générale des choix des étudiants de la promo <?php echo $promo?></li>
            </ol>


            <div class="card mb-4">
                <div class="card-header">
                    <i class="far fa-file-pdf"></i>
                    Analyse M1
                </div>

                <!-- Tableau affichage tous les étudiants et leurs choix -->
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Intitulé de l'offre</th>
                            <th>Entreprise</th>
                            <th>Priorité</th>
                        </tr>
                        </thead>


                        <tbody>
                        <?php
                        $val = 'null';
                        /* recupération de la requête et affichage de toutes les données dans un tableau */
                        $req = $bdd->prepare("SELECT nom, prenom, titre, nomSite, nomEntreprise, priorite FROM utilisateur
                        LEFT JOIN postule on utilisateur.idUtilisateur = postule.idutilisateur
                        LEFT JOIN offre_stage ON postule.idOffre = offre_stage.idOffre
                        LEFT JOIN site on offre_stage.idSite = site.idSite
                        LEFT JOIN entreprise on site.idEntreprise = entreprise.idEntreprise
                        WHERE postule.priorite != ? and promo = ?");
                        $req->execute(array($val, $promo));
                        $resultat = $req->fetchAll();
                        foreach ($resultat as $ligne) { ?>
                            <tr>
                                <td><?php echo $ligne['nom']; ?></td>
                                <td><?php echo $ligne['prenom']; ?></td>
                                <td><?php echo $ligne['titre']; ?></td>
                                <td><?php echo $ligne['nomEntreprise']; ?></td>
                                <td align ="right"><?php echo $ligne['priorite']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Affichage des noms des étudiants n'ayant réalisés aucun choix -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="far fa-file-pdf"></i>
                    Qui n'a pas postulé ?
                </div>


                <div class="card-body">
                    <table  class="table table-bordered">
                        <thead class="thead-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Adresse mail</th>
                        </tr>
                        </thead>


                        <tbody>
                        <?php

                        /* récupération de l'ensemble des utilisateurs */
                        $req = $bdd->prepare("SELECT idUtilisateur, statut, nom, prenom, email
                        FROM utilisateur where promo = ?");
                        $req->execute(array($promo));
                        $resultat = $req->fetchAll();


                        foreach ($resultat as $ligne) {

                            $relou = $ligne['idUtilisateur'];
                            /* Pour chaque utilisateur on cherche l'id correspondant dans la table choix_offre */
                            $reqVal = $bdd->prepare("SELECT idUtilisateur
                            FROM postule
                            WHERE idUtilisateur = ? ");
                            $reqVal->execute(array($relou));
                            $resultatVal = $reqVal->fetchAll();

                            /* Si aucun resultat renvoyé alors on affiche; On exclu les administrateurs */
                            if (empty($resultatVal) && $ligne['statut'] != "administrateur") {
                                ?>
                                <tr>
                                    <td><?php echo $ligne['nom']; ?></td>
                                    <td><?php echo $ligne['prenom']; ?></td>
                                    <td><?php echo $ligne['email']; ?></td>
                                </tr>


                            <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>
</body>
</html>