<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */
include('barre_nav_admin.php');
include('fonctionality/annee+promo.php');
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Gestion des offres</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Vue générale de toutes les offres</li>
            </ol>


            <div class="card mb-4">
                <div class="card-header">
                    <i class="far fa-file-pdf"></i>
                    Toutes les offres de l'année <?php echo $annee ?>
                </div>


                <div class="card-body">
                    <table id="datatablesSimple" class="table table-striped table-bordered">
                        <thead>

                        <tr>
                            <th>Nom de l'entreprise</th>
                            <th>Nom du site</th>
                            <th>Intitulé de l'offre</th>
                            <th>Description</th>
                            <th>Modification</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $req = "SELECT offre_stage.idOffre, titre, description, nomSite, nomEntreprise FROM offre_stage JOIN site on offre_stage.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise where annee = ? ORDER BY offre_stage.idOffre ASC";
                        $resultat = $bdd->prepare($req);
                        $resultat->execute(array($annee));
                        foreach ($resultat as $ligne) { ?>
                            <tr>
                                <td><?php echo $ligne['nomEntreprise']; ?></td>
                                <td><?php echo $ligne['nomSite']; ?></td>
                                <td><?php echo $ligne['titre']; ?></td>
                                <td><?php echo $ligne['description']; ?></td>
                                <!--<td><input type="submit" class="btn btn-primary mb-2" name="ModifOffre" value="Modifier"></td>-->
                                <!--<td><input type="button" value="Modifier" name ="Modifier" href="modif_offres.php"/></td>-->
                                <td><a href="modif_offres.php?id=<?php echo $ligne['idOffre']; ?>">Modifier</a></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <!--<input type="submit" class="btn btn-warning" name="ModifOffre" value="Ajouter">-->
                    <p> Pour ajouter une offre, <a href = ajout_offres.php>cliquez ici</a>
                    <!--<input type="button" class="btn btn-warning" name="ModifOffre" value="Ajouter">-->
                </div>
            </div>
            <br>
        </div>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>
</body>
</html>