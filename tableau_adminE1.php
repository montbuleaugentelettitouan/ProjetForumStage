<?php
/**
 * @autor:  Tom ROBIN, Axel ITEY, Nathan GODART, Damien CALOIN
 * @date : Promo Gphy 2023 - Année 2022 - 2023
 *
 */
include('barre_nav_admin.php');
include('fonctionality/bdd.php');
include('fonctionality/annee+promo.php');
?>
<div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4"> <!-- div de page-->
            <h1 class="mt-4"> Entreprises ayant pourvues des stages </h1>
            <form id="choixaccepte" method="post">
                <div class="card-body"> <!--div de tableau 1 -->
                    <table class="table table-bordered table-striped " id="datatablesSimple">
                        <thead class="thead-dark">
                        <tr>
                            <th>Nom des entreprises concernées</th>
                            <th>Nombre de stage pourvu</th>
                            <th>Nombre total de postes</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        /* recupération de la requête et affichage de toutes les données dans un tableau */
                        $req = $bdd->prepare("select * from ( select sum(nbPostePourvu) as pourvu , sum(NbPoste) as NbPoste, nomEntreprise, idEntreprise from offre join site using (idSite) join entreprise using (idEntreprise) where anneeO = ? group by idEntreprise) as T where T.pourvu != 0;");
                        $req->execute(array($annee));
                        $resultat = $req->fetchAll();
                        foreach ($resultat as $ligne) { ?>
                            <tr>
                                <td><a href="tableau_adminE2.php?id=<?php echo $ligne['idEntreprise'];?>"><?php echo $ligne['nomEntreprise']; ?></a></td>
                                <td><?php echo $ligne['pourvu']; ?></td>
                                <td><?php echo $ligne['NbPoste']; ?></td>
                            </tr>
                        <?php }

                        ?>

                        </tbody>
                    </table>
                </div> <!--fin div de tableau 1 -->
                <a href="tableau_de_bord_ADMIN_entreprise.php" class="btn btn-secondary" >Retour</a>
        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>