<?php
/**
 *
 * @autor:  Thibault NIGGEL
 * @date : Promo Gphy 2025 - Année 2023 - 2024
 *
 */
include('barre_nav_admin.php');
include('fonctionality/bdd.php');
include('fonctionality/annee+promo.php');
?>
<script>
    function validerAvecDelai() {
        // Ajoutez un délai de 0.5 secondes pour s'assurer que la requête ait le temps de s'effectuer
        setTimeout(function() {
            // Rechargez la page actuelle après le délai
            location.reload();
        }, 500); //
    }
</script>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Validation des offres</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Vue générale de toutes les offres proposées par les entreprises</li>
            </ol>


            <div class="card mb-4">
                <div class="card-header">
                    Toutes les offres pour l'année <?php echo $annee ?>
                </div>


                <div class="card-body">
                    <table id="datatablesSimple" class="table table-striped table-bordered">
                        <thead>

                        <tr>
                            <th>Nom de l'entreprise</th>
                            <th>Nom du site</th>
                            <th>Ville</th>
                            <th>Intitulé de l'offre</th>
                            <th>Description</th>
                            <th>Représentant</th>
                            <th>Mail à contacter</th>
                            <th>PDF</th>
                            <th>Modification</th>
                            <th>Validation</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $req = "SELECT offre.idOffre, titre, description, nomSite, nomEntreprise, ville, representant, mailContact, valider FROM offre JOIN site on offre.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise where anneeO = ? AND valider = 0 ORDER BY offre.idOffre ASC";
                        $resultat = $bdd->prepare($req);
                        $resultat->execute(array($annee));
                        foreach ($resultat as $ligne) { ?>
                            <tr>
                                <td><?php echo $ligne['nomEntreprise']; ?></td>
                                <td><?php echo $ligne['nomSite']; ?></td>
                                <td><?php echo $ligne['ville']; ?></td>
                                <td><?php echo $ligne['titre']; ?></td>
                                <td><?php echo $ligne['description']; ?></td>
                                <td><?php echo $ligne['representant']; ?></td>
                                <td><?php echo $ligne['mailContact']; ?></td>
                                <td><a href="telechargement_pdf.php?id=<?php echo $ligne['idOffre']; ?>" class="btn btn-primary">Télécharger le PDF</a></td>
                                <!--<td><input type="submit" class="btn btn-primary mb-2" name="ModifOffre" value="Modifier"></td>-->
                                <!--<td><input type="button" value="Modifier" name ="Modifier" href="modif_offres.php"/></td>-->
                                <td><a href="modif_valid_offres.php?id=<?php echo $ligne['idOffre']; ?>"class="btn btn-warning">Modifier</a></td>
                                <td>
                                    <form method="post" action="validation_offres.php" onsubmit="validerAvecDelai()">
                                        <input type="hidden" name="ValidOffre" value="<?php echo $ligne['idOffre']; ?>">
                                        <input type="submit" class="btn btn-warning" name="submit" value="Valider">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php
                        if (isset($_POST['ValidOffre'])) {
                            $idOffre = $_POST['ValidOffre'];

                            // Mettez à jour la base de données pour marquer l'offre comme validée
                            $req = "UPDATE offre SET valider = 1 WHERE idOffre = ?";
                            $resultat = $bdd->prepare($req);
                            $resultat->execute(array($idOffre));

                        } else {
                            // Gérer le cas où l'ID n'est pas spécifié ou n'est pas valide
                            echo "ID d'offre non valide.";
                        }
                        ?>

                        </tbody>
                    </table>
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