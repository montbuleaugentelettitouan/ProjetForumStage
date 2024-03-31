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
            <h1 class="mt-4">Étudiants ayant accepté une proposition</h1>
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
                        $req = $bdd->prepare("SELECT DISTINCT(email), nom, prenom, idUtilisateur, nomEntreprise, titre FROM utilisateur JOIN convention_contrat USING (idUtilisateur) JOIN offre USING (idOffre) JOIN site USING (idSite) JOIN entreprise USING (idEntreprise) WHERE promo = ?;");
                        $req->execute(array($promo));
                        $resultat = $req->fetchAll();
                        foreach ($resultat as $ligne) { ?>
                            <tr>
                                <td><a href="informations_convention.php?value=<?php echo $ligne['idUtilisateur'];?>"><?php echo $ligne['nom']; ?></a></td>
                                <!-- le prénom -->
                                <td><a href="informations_convention.php?value=<?php echo $ligne['idUtilisateur'];?>"><?php echo $ligne['prenom']; ?></a></td>
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
                <a href="tableau_de_bord_ADMIN.php" class="btn btn-secondary" >Retour</a>
        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>