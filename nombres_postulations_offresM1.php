<?php
include('barre_nav_M1.php');
include('fonctionality/annee+promo.php');
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Statistiques</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Récapitulatif du nombre de postulants par offre en <?php echo $annee ?></li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">

                    Tableau récapitulatif des postes proposés au Forum <?php echo $annee ?>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre d'entreprises</th>
                                <th>Nombre de site</th>
                                <th>Nombre d'offres disponibles</th>
                                <th>Nombre de postes disponibles au total</th>
                                <th>Nombre d'étudiants</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $nbPostes = $bdd->prepare('SELECT sum(nbPoste) AS somme FROM offre where anneeO = ?');
                            $nbPostes->execute(array($annee));
                            while ($donneesPoste=$nbPostes->fetch())
                            {

                            $nbEnt = $bdd->prepare("select COUNT(distinct idEntreprise) from entreprise join site using (IdEntreprise) join offre using (idSite) where anneeO = ?;");
                            $nbEnt->execute(array($annee));
                            while ($donneesEnt=$nbEnt->fetch())
                            {

                            $nbSite = $bdd->prepare('SELECT COUNT(distinct idSite) FROM site join offre using (idSite) where anneeO = ?;');
                            $nbSite->execute(array($annee));
                            while ($donneesSite=$nbSite->fetch())
                            {

                            $nbOf = $bdd->prepare('SELECT COUNT(*) FROM offre where anneeO = ?');
                            $nbOf->execute(array($annee));
                            while ($donneesOffre=$nbOf->fetch())
                            {

                            $nbEtu = $bdd->prepare('SELECT COUNT(*) FROM utilisateur WHERE statut = "etudiant" and promo = ?');
                            $nbEtu->execute(array($promo));
                            while ($donneesEtu=$nbEtu->fetch())
                            {
                            ?>
                            <tr>
                                <td align ="right"><?php echo $donneesEnt['COUNT(distinct idEntreprise)']; ?></td>
                                <td align ="right"><?php echo $donneesSite['COUNT(distinct idSite)']; ?></td>
                                <td align ="right"><?php echo $donneesOffre['COUNT(*)']; ?></td>
                                <td align ="right"><?php echo $donneesPoste['somme']; ?></td>
                                <td align ="right"><?php echo $donneesEtu['COUNT(*)']; ?></td>
                            </tr>
                        <?php } } } } } ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="card mb-4">
                <div class="card-header">

                    Suivi des postes
                </div>

                <div class="card-body">
                    <table id="datatablesSimple" class="table table-striped table-bordered" >
                        <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Site</th>
                            <th>Intitulé de l'offre</th>
                            <th>Nombre de postulants</th>
                            <th>Nombre de postes disponibles</th>
                            <th>Nombre de stages acceptés</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $req = "SELECT idOffre, titre, nomSite, nomEntreprise, nbPoste FROM offre JOIN site on offre.idSite = site.idSite JOIN entreprise on site.idEntreprise=entreprise.idEntreprise where anneeO = ?";
                        $resultat = $bdd->prepare($req);
                        $resultat->execute(array($annee));
                        $resultat = $resultat->fetchAll();
                        foreach ($resultat as $ligne) {
                            $requete = $bdd->prepare('SELECT COUNT(*) AS NbPostulants FROM postule_m1 WHERE idOffre=?');
                            $requete->execute(array($ligne['idOffre']));
                            $resultatnbpostulant = $requete->fetch();
                            $requete->closeCursor();

                            $accepte = $bdd->prepare ("SELECT COUNT(idUtilisateur) AS NbAccepte FROM convention_contrat WHERE idOffre = ? ");
                            $accepte->execute(array($ligne['idOffre']));
                            $resultatnbaccepte = $accepte->fetch();
                            $accepte->closeCursor();
                            ?>
                            <tr>
                                <td><?php echo $ligne['nomEntreprise']; ?></td>
                                <td><?php echo $ligne['nomSite']; ?></td>
                                <td><?php echo $ligne['titre']; ?></td>
                                <td align ="right"><?php echo $resultatnbpostulant['NbPostulants']; ?></td>
                                <td align ="right"><?php echo $ligne['nbPoste']; ?></td>
                                <td align ="right"><?php echo $resultatnbaccepte['NbAccepte']; ?></td>
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