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

<?php
$selectedValue = $_GET['value'];
// faire quelque chose avec la valeur sélectionnée
?>

<script>
    function detailEtu(){
        var selectedValue = document.getElementById("Etudiant").value;
        window.location.href = "dashboardSUIVIFORUM2.php?value=" + selectedValue;

    }
</script>

<!-- Body de la page -->
<div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4">  <!-- div de page-->
            <h1 class="mt-4">Suivi Post Forum Stage - Promo  <?php echo $promo ?> </h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Vue générale du suivi du forum</li>
            </ol>

            <div class="card mb-4"> <!--div de section 1 -->
                <div class="card-header"> <!--div de encadré 1 -->
                    <i class="far fa-file-pdf"></i>
                    Suivi des entretiens passés par les étudiants de la promo <?php echo $promo ?>
                </div> <!--fin div de encadré 1 -->

                <!-- Sélection de l'étudiant pour l'affichage -->

                <form method="post" action="#">
                    <div class="card-body"><!--div de tableau 1 -->
                        <select onchange="detailEtu()" name="Etudiant" id="Etudiant" >
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
                        <input type="submit" class="btn btn-warning" name="retour" id="retour" value="Back">
                    </div><!--fin div de tableau 1 -->
                </form>
                <br>
            </div> <!--fin div de section 1 -->

            <!-- Affichage du nom de l'étudiant sélectionné et ses informations -->
            <?php
            if (isset($_POST['retour'])) {
                echo "<script>window.location.replace(\"dashboardSUIVIFORUM.php\")</script>";
            }
            ?>

            <div class="card-body"><!--div de tableau 2 -->
                <?php
                $valid = $selectedValue;

                $reponse76 = $bdd->query("SELECT * FROM utilisateur WHERE idUtilisateur='$valid'");
                $resultat76 = $reponse76->fetch();

                $reponse74 = $bdd->query("SELECT utilisateur.idUtilisateur, etatC, cr_forumM1 FROM utilisateur WHERE utilisateur.idUtilisateur = '$valid' ");
                $resultat74 = $reponse74->fetch();
                ?>

                <h2> <?php echo $resultat76['nom'] ?> <?php echo ''?> <?php echo $resultat76['prenom'] ?> </h2>

                <br>

                <div class="card" style="width: 100rem;">
                    <div class="card-body">
                        <h5 class="card-title">Etat de la recherche</h5>
                        <p class="card-text"><?php echo $resultat74['etat'] ?></p>
                        <br>
                        <h5 class="card-title">Compte rendu du Forum </h5>
                        <p class="card-text"><?php echo $resultat74['cr_forumM1'] ?></p>
                    </div>
                </div>

                <br>

                <div class="card mb-4"> <!--div de section 2 -->
                    <div class="card-header"> <!--div de encadré 2 -->
                        <i class="far fa-file-pdf"></i>
                        Affichage du détail des entretiens
                    </div> <!--div de encadré 2 -->
                </div> <!--fin div de section 2 -->
                <br>
                
                <h5 class="card-title">Entretiens passés</h5>
                <table class="table table-striped" id="datatablesSimple" >
                    <thead class="thead-dark">
                    <tr>
                        <th>Intitulé de l'offre</th>
                        <th>Entreprise</th>
                        <th>Site</th>
                        <th>Compte Rendu</th>
                        <th>Etat de la recherche</th>
                        <th>Priorité</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $etat = $bdd->prepare("SELECT etat_recherche FROM postule_m1 WHERE idUtilisateur = ?");
                    $etat->execute(array($valid));
                    $resultEtat = $etat->fetch();

                    $verif = $bdd->prepare('SELECT titre, nomSite, nomEntreprise , cr_entretien, etat_recherche, priorite FROM postule_m1 JOIN offre on postule_m1.idOffre = offre.idOffre join site on offre.idSite = site.idSite join entreprise on site.idEntreprise= entreprise.idEntreprise  WHERE postule_m1.idUtilisateur =? and entretien_passe = 1;');
                    $verif->execute(array($valid));
                    //while ($donnees = $verif->fetch())
                    foreach ($verif as $donnees){
                        ?>
                        <tr>
                            <td><?php echo $donnees['titre']; ?></td>
                            <td><?php echo $donnees['nomEntreprise']; ?></td>
                            <td><?php echo $donnees['nomSite']; ?></td>
                            <td><?php echo $donnees['cr_entretien']; ?></td>
                            <td><?php echo $donnees['etat_recherche']; ?></td>
                            <td align ="right"><?php echo $donnees['priorite']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <script>
                    window.addEventListener('DOMContentLoaded', event => {
                        // Simple-DataTables
                        // https://github.com/fiduswriter/Simple-DataTables/wiki
                        const datatablesInvisible = document.getElementById('datatablesSimple');
                        if (datatablesInvisible) {

                        }
                    });
                </script>

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