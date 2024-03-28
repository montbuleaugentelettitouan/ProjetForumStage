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

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Gestion des entreprises</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Vue générale des entreprises et leurs sites</li>
            </ol>


            <div class="card mb-4">
                <div class="card-header">

                    Entreprises et sites
                </div>


                <div class="card-body">
                    <table id="datatablesSimple" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nom de l'entreprise</th>
                                <th>Nom du site</th>
                                <th>Ville</th>
                                <th>Pays</th>
                                <th>Modification</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $req = $bdd->query("SELECT entreprise.idEntreprise, nomEntreprise, idSite, nomSite, ville, pays, contactRH FROM entreprise LEFT JOIN site on entreprise.idEntreprise = site.idEntreprise");
                        //$resultat = $bdd->query($req);
                        $resultat = $req->fetchAll();
                        foreach ($resultat as $ligne) { ?>
                            <tr>
                                <td><?php echo $ligne['nomEntreprise']; ?></td>
                                <td><?php echo $ligne['nomSite']; ?></td>
                                <td><?php echo $ligne['ville']; ?></td>
                                <td><?php echo $ligne['pays']; ?></td>
                                <td><a href="modif_entreprise.php?id=<?php echo $ligne['idSite']; ?>">Modifier</a></td>
                            </tr>
                        <?php } ?>
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