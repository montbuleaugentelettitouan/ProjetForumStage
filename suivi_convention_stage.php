<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX, Nathan Godart
 * @date : Promo GPhy 2022- 2023 - Année 2021 : 2022 : 2023
 *
 */
include('barre_nav_admin.php');
include('fonctionality/bdd.php');
include('fonctionality/annee+promo.php');
?>

<!-- Body de la page -->
<div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4">  <!-- div de page-->
            <h1 class="mt-4">Suivi des conventions de stage - Promo <?php echo $promo ?> </h1>
                <div class="card mb-4"> <!--div de section 1 -->
                    <div class="card-header"> <!--div de encadré 1 -->
                        <i class="far fa-file-pdf"></i>
                        Suivi de l'avancé des conventions de stage des étudiants de la promo <?php echo $promo ?>
                    </div> <!--fin div de encadré 1 -->

                    <!-- Sélection de l'étudiant pour l'affichage -->
                    
                    <form method="post" action="#">
                        <div class="card-body"><!--div de tableau 1 -->
                            <select name="Etudiant" id="Etudiant" required>
                                <option value="">Sélectionnez un étudiant</option>
                                <?php
                                $reponse = $bdd->prepare ('SELECT idUtilisateur, nom, prenom FROM utilisateur WHERE statut = "etudiant" and promo = ? ORDER BY nom ASC');
                                $reponse->execute(array($promo));
                                    while ($donnees = $reponse->fetch()) {
                                        ?>
                                    <option value="<?php echo $donnees['idUtilisateur']; ?>">
                                        <?php echo $donnees['nom']; ?>
                                        <?php echo $donnees['prenom']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <input type="submit" class="btn btn-warning" name="valider" value="Valider">
                        </div><!--fin div de tableau 1 -->
                    </form>
                    <br>
                </div> <!--fin div de section 1 -->

                <!-- Affichage du nom de l'étudiant sélectionné et ses informations -->

                <div class="card mb-4"> <!--div de section 2 -->
                    <div class="card-header"> <!--div de encadré 2 -->
                        <i class="far fa-file-pdf"></i>
                        Affichage du détail de la convention
                    </div> <!--div de encadré 2 -->
                </div> <!--fin div de section 2 -->

                <div class="card-body"><!--div de tableau 2 -->
                    <?php
                        if (isset($_POST['valider'])) {
                            $valid = $_POST['Etudiant'];

                            $reponse76 = $bdd->query("SELECT * FROM utilisateur WHERE idUtilisateur='$valid'");
                            $resultat76 = $reponse76->fetch();

                            $reponse74 = $bdd->query("SELECT utilisateur.idUtilisateur, etat FROM utilisateur WHERE utilisateur.idUtilisateur = '$valid' ");
                            $resultat74 = $reponse74->fetch();
                    ?>
                    <h2> <?php echo $resultat76['nom'] ?> <?php echo ''?> <?php echo $resultat76['prenom'] ?> </h2>

                    <br>

                    <div class="card" style="width: 100rem;">
                        <div class="card-body">
                            <h5 class="card-title">Etat de la convention</h5> 
                           <!-- NOTE : ajouter une colonne avec "etat_conv" -> "signé" "en cours de redaction" ou "pas de stage", puis faire afficher ca en dessous -->
                            <p class="card-text"><?php echo $resultat74['etat'] ?></p>
                        </div>
                    </div>

                    <br>

                    <h5 class="card-title">Information de la convention</h5>
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>/</th>
                                <th>/</th>
                                <th>/</th>
                                <th>/</th>
                                <th>/</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                $etat = $bdd->prepare("SELECT etat_recherche FROM postule WHERE idUtilisateur = ?");
                              
                                $etat->execute(array($valid));
                                $resultEtat = $etat->fetch();

                                $verif = $bdd->prepare('SELECT titre, nomSite, nomEntreprise , cr_entretien, etat_recherche FROM postule JOIN offre_stage on postule.idOffre = offre_stage.idOffre join site on offre_stage.idSite = site.idSite join entreprise on site.idEntreprise= entreprise.idEntreprise  WHERE postule.idUtilisateur =? and entretien_passe = 1;');
                              
                                $verif->execute(array($valid));
                            while ($donnees = $verif->fetch()) {
                            ?>
                            <tr>
                                <td><?php echo $donnees['titre']; ?></td>
                                <td><?php echo $donnees['nomEntreprise']; ?></td>
                                <td><?php echo $donnees['nomSite']; ?></td>
                                <td><?php echo $donnees['cr_entretien']; ?></td>
                                <td><?php echo $donnees['etat_recherche']; ?></td>
                            </tr>
                        </tbody>
                        <?php } ?>
                    </table>

                    <?php } ?>
                </div><!--fin div de tableau 2 -->

      <!----------------------------Footer------------------------------------------->

        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>