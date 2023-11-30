<?php
include('barre_nav_admin.php');
include('fonctionality/bdd.php');
include('fonctionality/annee+promo.php');
?>

<script>
    function detailEtu(){
        var selectedValue = document.getElementById("Etudiant").value;
        window.location.href = "infos_stage2.php?value=" + selectedValue;

    }
</script>
<!-- Body de la page -->
<div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4"> <!-- div de page-->
            <h1 class="mt-4">Suivi des stages de l'année <?php echo $annee ?> </h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"> Vue générale des informations sur les stages</li>
            </ol>

            <div class="card mb-4"> <!--div de section 1 -->
                <div class="card-header"> <!--div de encadré 1 -->
                    <i class="far fa-file-pdf"></i>
                    Informations du stage des étudiants de la promo <?php echo $promo ?>
                </div> <!--fin div de encadré 1 -->

                <!-- Sélection de l'étudiant pour l'affichage -->

                <form method="POST" action="#">
                    <div class="card-body"> <!--div de tableau 1 -->
                        <select onchange="detailEtu()" name="Etudiant" id="Etudiant" required>
                            <option value="">Sélectionnez un étudiant</option>
                            <?php
                            $reponse = $bdd->prepare('SELECT idUtilisateur, nom, prenom FROM convention_contrat join utilisateur using (idUtilisateur) WHERE statut = "etudiant" and promo = ? ORDER BY nom ASC');
                            $reponse->execute(array($promo));
                            while ($donnees = $reponse->fetch()) {
                                ?>
                                <option value="<?php echo $donnees['idUtilisateur']; ?>">
                                    <?php echo $donnees['nom']; ?>
                                    <?php echo $donnees['prenom']; ?>
                                </option>
                            <?php } ?>
                        </select>
<!--                        <input type="submit" class="btn btn-warning" name="valider" value="Valider">-->
                    </div> <!--fin div de tableau 1 -->
                </form>
                <br>
            </div> <!--fin div de section 1 -->

            <!-- Affichage du nom de l'étudiant sélectionné et ses informations -->

            <?php
            $req = $bdd->prepare('select DISTINCT(email), nom, prenom, nomEntreprise, titre from utilisateur join convention_contrat using (idUtilisateur) join offre using (idOffre) join site on offre.idSite = site.idSite join entreprise using (idEntreprise) where statut = "etudiant" and  promo = ?;');
            $req->execute(array($promo));
            $resultatreq = $req->fetchAll();
            ?>

            <div id = "tableau1" class="visible">
                <table class="table table-striped" id="datatablesSimple" >
                    <thead class="thead-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Entreprise</th>
                        <th>Offre acceptée</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($resultatreq as $ligne) { ?>
                        <tr>
                            <td><?php echo $ligne['nom']; ?></td>
                            <td><?php echo $ligne['prenom']; ?></td>
                            <td><?php echo $ligne['nomEntreprise']; ?></td>
                            <td><?php echo $ligne['titre']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <!----------------------------Footer------------------------------------------->

        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>