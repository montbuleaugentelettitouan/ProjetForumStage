<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */
/**
 * Modifications
 *
 * @autor : Tom ROBIN, Nathan GODART, Damien CALOIN et Axel ITEY
 * @date : Promo GPhy 2023 - Année 2022 : 2023
 *
 */

include('barre_nav_M1.php');
include('fonctionality/bdd.php');
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction pour gérer la visibilité du formulaire
        function toggleAutreFormVisibility() {
            var autreRadio = document.getElementById('new');
            var autreForm = document.getElementById('autreForm');
            var autreFormFields = autreForm.querySelectorAll('input[type="text"], input[type="email"]');

            // Affiche ou masque le formulaire en fonction de la sélection de l'utilisateur
            autreForm.style.display = autreRadio.checked ? 'block' : 'none';

            // Ajoute ou supprime l'attribut required des champs du formulaire autre en fonction de la sélection
            autreFormFields.forEach(function(field) {
                field.required = autreRadio.checked;
            });
        }

        // Ajoutez un écouteur d'événements pour le changement de la sélection radio "autre"
        var autreRadio = document.getElementById('new');
        autreRadio.addEventListener('change', toggleAutreFormVisibility);

        // Ajoutez des écouteurs d'événements pour les autres options radio
        var autreRadioOptions = document.querySelectorAll('input[name="inlineRadioOptions"]');
        autreRadioOptions.forEach(function(radio) {
            radio.addEventListener('change', toggleAutreFormVisibility);
        });

        // Appelez la fonction une fois au chargement de la page pour initialiser la visibilité
        toggleAutreFormVisibility();
    });
</script>
<div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4"> <!-- div de page-->
            <h1 class="mt-4">Acceptation d'une proposition</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Sélectionner l'offre que vous acceptez</li>
            </ol>

            <?php
            $id = $_SESSION['user'];
            $req = $bdd->prepare('SELECT etatC FROM utilisateur WHERE idUtilisateur = ?');
            $req->execute(array($id));
            $resultReq = $req->fetch();
            if ($resultReq['etatC'] == 'accepte') {

                ?>
                <div class="card mb-4"> <!--div de section 1 -->
                    <div class="card-header"> <!--div de encadré 1 -->
                        Stage accepté
                    </div> <!--fin div de encadré 1 -->
                    <div class="card-body"> <!--div de tableau 1 -->
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                            <tr>
                                <th>Entreprise</th>
                                <th>Site</th>
                                <th>Intitulé de l'offre</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $req = $bdd->prepare('SELECT offre.idOffre, entreprise.nomEntreprise, site.nomSite, offre.titre, offre.description FROM convention_contrat JOIN offre USING (idOffre) JOIN site on offre.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise WHERE convention_contrat.idUtilisateur=?');
                            $req->execute(array($id));
                            $resultat = $req->fetchAll();
                            foreach ($resultat as $ligne) {
                                ?>
                                <tr>
                                    <td><?php echo $ligne['nomEntreprise']; ?></td>
                                    <td><?php echo $ligne['nomSite']; ?></td>
                                    <td><?php echo $ligne['titre']; ?></td>
                                    <td><?php echo $ligne['description']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>


            <div class="card mb-4"> <!--div de section 1 -->
                <div class="card-header"> <!--div de encadré 1 -->
                    Vos propositions
                </div> <!--fin div de encadré 1 -->

                <form id="choixaccepte" method="post" action="fonctionality/insert_bdd_stage_accepte.php">
                    <div class="card-body"> <!--div de tableau 1 -->
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                            <tr>
                                <th>Entreprise</th>
                                <th>Site</th>
                                <th>Intitulé de l'offre</th>
                                <th>Description</th>
                                <th>Stage accepté</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            // requête pour afficher les informations des offres que l'id Utilisateur à sélectionné
                            $req = $bdd->prepare('SELECT offre.idOffre, nomEntreprise, nomSite, titre, description, proposition_acceptee FROM postule_m1 JOIN offre on postule_m1.idOffre = offre.idOffre JOIN site on offre.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise WHERE postule_m1.idUtilisateur=? AND postule_m1.entretien_passe = ? AND postule_m1.proposition_recue = ?');
                            $req->execute(array($_SESSION['user'],1,1));
                            $resultat = $req->fetchAll();

                            // on compte les lignes pour valider lors du développement
                            $count = $req->rowcount();

                            //si l'état de la table suivi_forum à la valeur "accepte" alors le boolean accepte de table choix_offre prend la valeur 1
                            foreach ($resultat as $ligne) {
                                if($ligne['proposition_acceptee'] == 1){
                                    $accepter = true;
                                }
                                elseif($ligne['proposition_acceptee'] != 1){
                                    $accepter = false;
                                }
                                ?>
                                <tr>
                                    <td><?php echo $ligne['nomEntreprise']; ?></td>
                                    <td><?php echo $ligne['nomSite']; ?></td>
                                    <td><?php echo $ligne['titre']; ?></td>
                                    <td><?php echo $ligne['description']; ?></td>
                                    <td>
                                        <div class="form-check">
                                            <?php echo '<input class="form-check-input" type="radio" name="inlineRadioOptions" id="' . $ligne['idOffre'] . '" value="' . $ligne['idOffre'] . '"';
                                            if ($accepter == true) {
                                                echo ' checked="checked" required >';
                                            }else{
                                                echo ' required >';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div> <!--fin div de tableau 1 -->

                    <!-- Formulaire 'autre' -->

                    <br>
                    <div class="form-check">
                        <label><h4> En cas de stage hors Forum Stage </h4></label>
                        <?php
                        $accepte = 'accepte';

                        $nomE = '';
                        $nomO = '';
                        $nomS = '';
                        $nomV = '';
                        $nomP = '';
                        $nomD = '';
                        $nomSec='';
                        $nomC='';

                        $autre = false;

                        //$count5=$req5->rowCount();// a supp ?

                        if ($autre == true) {
                            echo '<input class="form-check-input" type="radio" name="inlineRadioOptions" id="new" value="new" checked="checked" required>';
                        } else {
                            echo '<input class="form-check-input" type="radio" name="inlineRadioOptions" id="new" value="new" required>';
                        }
                        ?>
                    </div>

                    <div id="autreForm">
                        <!-- zone nom entreprise si autre-->

                        <!-- zone nom entreprise si autre-->
                        <br>
                        <div class="form-group">
                            <label><h5> Nom de l'entreprise :</h5></label>
                            <input type="text" class="form-control" id="nomEnt" name="nomentreprise" value="<?php echo $nomE ?>">
                        </div>

                        <!-- zone nom site si autre-->
                        <br>
                        <div class="form-group">
                            <label><h5> Nom du site :</h5></label>
                            <input type="text" class="form-control" id="nomSite" name="nomsite" value="<?php echo $nomS ?>">
                        </div>

                        <!-- zone ville du site si autre-->
                        <br>
                        <div class="form-group">
                            <label><h5> Ville du site :</h5></label>
                            <input type="text" class="form-control" id="Ville" name="Ville" value="<?php echo $nomV ?>">
                        </div>

                        <br>
                        <div class="form-group">
                            <label><h5> Pays du site :</h5></label>
                            <input type="text" class="form-control" id="Pays" name="Pays" value="<?php echo $nomP ?>">
                        </div>

                        <!-- zone intitulé poste (nomOffre) si autre-->
                        <br>
                        <div class="form-group">
                            <label><h5> Secteur d'activité :</h5></label>
                            <input type="text" class="form-control" id="Secteur" name="Secteur" value="<?php echo $nomSec ?>">
                        </div>

                        <br>
                        <div class="form-group">
                            <label><h5> Intitulé du poste :</h5></label>
                            <input type="text" class="form-control" id="nomPoste" name="nomposte" value="<?php echo $nomO ?>">
                        </div>

                        <!-- zone intitulé poste (description) si autre-->
                        <br>
                        <div class="form-group">
                            <label><h5> Description du poste :</h5></label>
                            <input type="text" class="form-control" id="nomDesc" name="nomdescription" value="<?php echo $nomD ?>">
                        </div>

                        <br>
                        <div class="form-group">
                            <label><h5> Mail de Contact :</h5></label>
                            <input type="email" class="form-control" id="contact" name="contact" value="<?php echo $nomC ?>">
                        </div>

                        <br>

                    </div>
                    <!--<input type="button" onclick="window.location.href = 'suivi_stage.php';" class="btn btn-primary" name="stage" value="Informations sur le stage">-->
                    <div class="card mb-4">
                        <br>
                        <input type="submit" class="btn btn-warning" name="Valideraccepte" value="Valider">
                    </div>
                </form>

            </div> <!-- fin div de section 1 -->


            <!----------------------------Footer------------------------------------------->

        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>