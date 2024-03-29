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

            <?php
            $idE = $_GET['id'];
            $reqNom = $bdd->prepare("select nomEntreprise from entreprise where idEntreprise = ? ;");
            $reqNom->execute(array($idE));
            $resultatNom = $reqNom->fetch();

            /* recupération de la requête et affichage de toutes les données dans un tableau */
            $req = $bdd->prepare("select DISTINCT(email), nom, idUtilisateur, prenom, nomEntreprise, titre from utilisateur join convention_contrat using (idUtilisateur) join offre using (idOffre) join site on offre.idSite = site.idSite join entreprise using (idEntreprise) where statut = 'etudiant' and  promo = ? and idEntreprise = ?;");
            $req->execute(array($promo, $idE));
            $resultat = $req->fetchAll();
            ?>
            <h1 class="mt-4"> Stage(s) pourvu(s) par <?php echo $resultatNom['nomEntreprise'];?></h1>
            <br>
            <div>
                <a href="gestion_entreprise.php">
                    <input type="submit" class="btn btn-warning" name="retour2" id="retour2" value="Tableau de gestion des entreprises">
                </a>
            </div>
            <form id="choixaccepte" method="post">
                <div class="card-body"> <!--div de tableau 1 -->
                    <br>
                    <table class="table table-bordered table-striped " id="datatablesSimple">
                        <thead class="thead-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Offre acceptée</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        foreach ($resultat as $ligne) { ?>
                            <tr>
                                <td><a href="informations_convention.php?value=<?php echo $ligne['idUtilisateur'];?>"><?php echo $ligne['nom']; ?></a></td>
                                <td><a href="informations_convention.php?value=<?php echo $ligne['idUtilisateur'];?>"><?php echo $ligne['prenom']; ?></a></td>
                                <td><?php echo $ligne['titre']; ?></td>
                            </tr>
                        <?php }

                        ?>

                        </tbody>
                    </table>
                </div> <!--fin div de tableau 1 -->

                <?php
                /* recupération de la requête et affichage de toutes les données dans un tableau */
                $req = $bdd->prepare(" select * from offre join site using (idSite) join entreprise using (idEntreprise) where idEntreprise = ? and anneeO = ? and NbPoste>nbPostePourvu  ;");
                $req->execute(array($idE, $annee));
                $resultat = $req->fetchAll();

                $reqN = $bdd->prepare("select count(idOffre) as Nombre from (select * from offre join site using (idSite) join entreprise using (idEntreprise) where idEntreprise = ? and anneeO = ? and NbPoste>nbPostePourvu) as T;");
                $reqN->execute(array($idE, $annee));
                $resultat2 = $reqN->fetch();

				// S'il existe des stages non pourvu par l'entreprise alors le deuxieme tableau est affiché
                if( $resultat2["Nombre"] != 0) {?>

                <div class="card-body"> <!--div de tableau 2 -->
                    <h1 class="mt-4"> Stage(s) restant(s) pour  <?php echo $resultatNom['nomEntreprise'];?> </h1>
                    <br>
                    <table class="table table-bordered table-striped " id="datatablesSimple">
                        <thead class="thead-dark">
                        <tr>
                            <th>Titre de l'offre</th>
                            <th>Nombre de poste restant(s)</th>
                            <th>Nombre de poste pourvu(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($resultat as $ligne) {
                                $restant = $ligne["nbPoste"]-$ligne["nbPostePourvu"];
                            ?>
                            <tr>
                                <td><?php echo $ligne['titre']; ?></td>
                                <td><?php echo $restant; ?></td>
                                <td><?php echo $ligne['nbPostePourvu']; ?></td>
                            </tr>
                        <?php }

                        ?>

                        </tbody>
                    </table>
                </div> <!--fin div de tableau 2 -->
                <?php }

                else{?>
                    <h3 class="mt-4"> <?php echo $resultatNom['nomEntreprise'];?> a pourvu tous ses stages.  </h3>
                    <br><br>
                <?php } ?>
        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>