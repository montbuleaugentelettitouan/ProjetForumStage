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
            <h1 class="mt-4">Etudiants ayant des propositions acceptées</h1>
            <form id="choixaccepte" method="post">
                <div class="card-body"> <!--div de tableau 1 -->
                    <table class="table table-striped" id="datatablesSimple">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Adresse Mail</th>
                            <th>Entreprise</th>
                            <th>Stage Accepté</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        /* recupération de la requête et affichage de toutes les données dans un tableau */
                        $req = $bdd->prepare("select DISTINCT(email), nom, prenom, nomEntreprise, titre from utilisateur join stage using (idUtilisateur) join offre_stage using (idOffre) join site on stage.idSite = site.idSite join entreprise using (idEntreprise) where  promo = ?;");
                        $req->execute(array($promo));
                        $resultat = $req->fetchAll();
                        foreach ($resultat as $ligne) { ?>
                            <tr>
                                <td><?php echo $ligne['nom']; ?></td>
                                <td><?php echo $ligne['prenom']; ?></td>
                                <td><?php echo $ligne['email']; ?></td>
                                <td><?php echo $ligne['nomEntreprise']; ?></td>
                                <td><?php echo $ligne['titre']; ?></td>
                                <!-- <td> <a href="Send_mail_etu.php"> <?php echo $ligne['email']; ?> </a> </td> -->
                            </tr>

                        <?php }

                        ?>

                        </tbody>
                    </table>
                </div> <!--fin div de tableau 1 -->
                <a href="tableau_de_bord_ADMIN.php" class="btn btn-warning" >BACK</a>
        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>