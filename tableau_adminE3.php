<?php
/**
 *
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
            <h1 class="mt-4">Entreprises n'ayant pas pourvues de stages</h1>
            <form id="choixaccepte" method="post">
                <div class="card-body"> <!--div de tableau 1 -->
                    <table class="table table-bordered table-striped " id="datatablesSimple">
                        <thead class="thead-dark">
                        <tr>
                            <th>Nom des entreprises concernées</th>
                            <th>Nombre total de postes</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        /* recupération de la requête et affichage de toutes les données dans un tableau */
                        $req = $bdd->prepare("select * from ( select sum(nbPostePourvu) as pourvu , sum(nbPoste) as nbPoste, nomEntreprise, idEntreprise from offre join site using (idSite) join entreprise using (idEntreprise) where anneeO = ? group by idEntreprise) as T where T.pourvu = 0;");
                        /*$req = $bdd->prepare("
                            SELECT nbPoste, nomEntreprise 
                            FROM (
                                SELECT DISTINCT(o.idSite) AS idSite, e.nomEntreprise, COUNT(o.idSite) AS nbPoste
                                FROM offre o
                                JOIN site s ON o.idSite = s.idSite
                                JOIN entreprise e ON s.idEntreprise = e.idEntreprise
                                WHERE o.idSite NOT IN (
                                    SELECT DISTINCT o.idSite
                                    FROM offre o
                                    INNER JOIN convention_contrat c ON o.idOffre = c.idOffre
                                )
                                AND anneeO = ?
                                GROUP BY o.idSite, e.nomEntreprise
                            ) AS subquery;");*/
                        $req->execute(array($annee));
                        $resultat = $req->fetchAll();
                        foreach ($resultat as $ligne) { ?>
                            <tr>
                                <td><?php echo $ligne['nomEntreprise']; ?></td>
                                <td><?php echo $ligne['nbPoste']; ?></td>
                            </tr>
                        <?php }

                        ?>

                        </tbody>
                    </table>
                </div> <!--fin div de tableau 1 -->
                <a href="tableau_de_bord_ADMIN_entreprise.php" class="btn btn-warning" >BACK</a>
        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>