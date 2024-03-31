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
            <h1 class="mt-4">Recueil des offres de l'année <?php echo $annee ?></h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Vue générale de toutes les offres</li>
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


                <div class="card-body">
                    <table id="datatablesSimple" class="table table-striped table-bordered">
                        <thead>

                        <tr>
                            <th>Nom de l'entreprise</th>
                            <th>Nom du site</th>
                            <th>Ville</th>
                            <th>Intitulé de l'offre</th>
                            <th>Description</th>
                            <th>Postes</th>
                            <th>Représentant</th>
                            <th>Mail à contacter</th>
                            <th>PDF</th>
                            <th>Modification</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $req = "SELECT offre.idOffre, titre, description, nomSite, nomEntreprise, ville, representant, mailContact, valider, nbPoste FROM offre JOIN site on offre.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise where anneeO = ? AND valider = 1 ORDER BY offre.idOffre ASC";
                        $resultat = $bdd->prepare($req);
                        $resultat->execute(array($annee));
                        foreach ($resultat as $ligne) { ?>
                            <tr>
                                <td><?php echo $ligne['nomEntreprise']; ?></td>
                                <td><?php echo $ligne['nomSite']; ?></td>
                                <td><?php echo $ligne['ville']; ?></td>
                                <td><?php echo $ligne['titre']; ?></td>
                                <td><?php echo $ligne['description']; ?></td>
                                <td><?php echo $ligne['nbPoste']; ?></td>
                                <td><?php echo $ligne['representant']; ?></td>
                                <td><?php echo $ligne['mailContact']; ?></td>
                                <td><a href="telechargement_pdf.php?id=<?php echo $ligne['idOffre']; ?>" class="btn btn-primary">Télécharger le PDF</a></td>
                                <!--<td><input type="submit" class="btn btn-primary mb-2" name="ModifOffre" value="Modifier"></td>-->
                                <!--<td><input type="button" value="Modifier" name ="Modifier" href="modif_offres.php"/></td>-->
                                <td><a href="modif_valid_offres.php?id=<?php echo $ligne['idOffre']; ?>"class="btn btn-warning">Modifier</a></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <!--<input type="submit" class="btn btn-warning" name="ModifOffre" value="Ajouter">-->
                    <!--<input type="button" class="btn btn-warning" name="ModifOffre" value="Ajouter">-->
                </div>
            </div>
            <button onclick="topFunction()" id="scrollTopBtn" class="btn btn-secondary" title="Revenir en haut de la page">Haut de la page</button>

            <script>
                // Fonction pour revenir au haut de la page
                function topFunction() {
                    document.body.scrollTop = 0; // Pour les navigateurs Chrome, Safari et Opera
                    document.documentElement.scrollTop = 0; // Pour les navigateurs Firefox, IE et Edge
                }
            </script>
        </div>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>
</body>
</html>