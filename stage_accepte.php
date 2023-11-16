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
<div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4"> <!-- div de page-->
            <h1 class="mt-4">Vous avez accepté une offre de stage </h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Sélectionner l'offre que vous avez accepté</li>
                </ol>

                <div class="card mb-4"> <!--div de section 1 -->
                    <div class="card-header"> <!--div de encadré 1 -->
                        <i class="far fa-file-pdf"></i>
                        Vos offres
                    </div> <!--fin div de encadré 1 -->

                    <form id="choixaccepte" method="post">
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
                                        $req = $bdd->prepare('SELECT offre_stage.idOffre, nomEntreprise, nomSite, titre, description, proposition_acceptee FROM postule JOIN offre_stage on postule.idOffre = offre_stage.idOffre JOIN site on offre_stage.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise WHERE postule.idUtilisateur=? AND postule.entretien_passe = ? AND postule.proposition_recue = ?');
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

                        <!--Début de la zone Autre -->

                        <br>
                        <div class="form-check">
                            <label><h4> Autre : </h4></label>
                            <?php
                                $accepte = 'accepte';

                                    $nomE = '';
                                    $nomO = '';
                                    $nomS = '';
                                    $nomV = '';
                                    $nomD = '';
                                
                                $autre = false;
                            
                                //$count5=$req5->rowCount();// a supp ?

                                if ($autre == true) {

                                    echo '<input class="form-check-input" type="radio" name="inlineRadioOptions" id="new" value="new" checked="checked" required>';
                                } else {
                                    echo '<input class="form-check-input" type="radio" name="inlineRadioOptions" id="new" value="new" required>';
                                }
                            ?>
                        </div>

                        <!-- zone nom entreprise si autre-->

                        <br>
                        <div class="form-group">
                            <label><h5> Nom de l'entreprise :</h5></label>
                            <input type="text" class="form-control" id="nomEnt" name="nomentreprise"  = <?php echo $nomE?> >
                        </div>

                        <!-- zone nom site si autre-->

                        <br>
                        <div class="form-group">
                            <label><h5> Nom du site :</h5></label>
                            <input type="text" class="form-control" id="nomSite" name="nomsite"  = <?php echo $nomS?> >
                        </div>

                        <!-- zone ville du site si autre-->

                        <br>
                        <div class="form-group">
                            <label><h5> Ville du site :</h5></label>
                            <input type="text" class="form-control" id="Ville" name="Ville"  = <?php echo $nomV?> >
                        </div>

                        <!-- zone intitulé poste (nomOffre) si autre-->

                        <br>
                        <div class="form-group">
                            <label><h5> Intitulé du poste :</h5></label>
                            <input type="text" class="form-control" id="nomPoste" name="nomposte"  = <?php echo $nomO?> >
                        </div>

                        <!-- zone intitulé poste (description) si autre-->

                        <br>
                        <div class="form-group">
                            <label><h5> Description du poste :</h5></label>
                            <input type="text" class="form-control" id="nomDesc" name="nomdescription"  = <?php echo $nomD?>>
                        </div>

                        <br>
                        <input type="submit" class="btn btn-warning" name="Valideraccepte" value="Valider">
                        <!--<input type="button" onclick="window.location.href = 'suivi_stage.php';" class="btn btn-primary" name="stage" value="Informations sur le stage">-->
                    </form>
                    <?php
                        include('fonctionality/insert_bdd_stage_accepte.php');
                    ?>
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