<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */
// ajout des fonctionnalités php
include('barre_nav_M1.php');
include('fonctionality/bdd.php');
?>
<div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4"> <!-- div de page-->

        <!-- Affichage des paramètres de session de l'utilisateur -->

        <h1 class="mt-4">Ordre de priorité de <?php echo $_SESSION['nom']; ?> <?php echo $_SESSION['prenom']; ?></h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Vue de mes choix</li>
            </ol>

            <div class="card mb-4"> <!--div de section 1 -->
                <div class="card-header"> <!--div de encadré 1 -->

                </div> <!--fin div de encadré 1 -->

                <!----Affichage des offres sélectionnées------>

                <form id="tableperso" method="post">
                    <div class="card-body"> <!--div de tableau 1 -->
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Priorité enregistrée</th>
                                    <th>Entreprise</th>
                                    <th>Site</th>
                                    <th>Intitulé de l'offre</th>
                                    <th>Description</th>
                                    <th>Priorité</th>
                                    <th>Compte-rendu de l'entretien</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    //$user = "hpotter";
                                    $req = $bdd->prepare('SELECT offre.idOffre, nomEntreprise, nomSite, titre, description, priorite, cr_entretien FROM entreprise JOIN site on entreprise.idEntreprise = site.idEntreprise JOIN offre ON site.idSite = offre.idSite JOIN postule_m1 on offre.idOffre = postule_m1.idOffre WHERE postule_m1.idUtilisateur=? ORDER BY priorite ASC');
                                    $req->execute(array($_SESSION['user']));
                                    //$req->execute(array($user));
                                    $resultat = $req->fetchAll();


                                    //$ent = !empty($resultat['nomEntreprise']) ? $resultat['nomEntreprise'] : NULL ;
                                    //$site = !empty($resultat['nomSite']) ? $resultat['nomSite'] : NULL ;
                                    //$titre = !empty($resultat['titre']) ? $resultat['titre'] : NULL ;
                                    //$des = !empty($resultat['description']) ? $resultat['description'] : NULL ;
                                    
                                    foreach ($resultat as $ligne) { ?>

                                <tr>
                                    <td><?php echo $ligne['priorite']; ?></td>
                                    <td><?php echo $ligne['nomEntreprise']; ?></td>
                                    <td><?php echo $ligne['nomSite']; ?></td>
                                    <td><?php echo $ligne['titre']; ?></td>
                                    <td><?php echo $ligne['description']; ?></td>
                                    <td>
                                        <select class="form-select modif" aria-label="Default select example"
                                                name="offre<?php echo $ligne['idOffre']; ?>">
                                            <option selected></option>
                                            <?php
                                            for ($i = 1; $i <= 15; $i++) {
                                                echo '<option value="' . $i . '"';
                                                if ($i == $ligne['priorite']) {
                                                    echo ' selected';
                                                }
                                                echo '>' . $i . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><?php echo $ligne['cr_entretien']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div> <!--fin div de tableau 1 -->
                    <input type="submit" class="btn btn-warning" name="Valider" value="Valider (si modification des priorités)">
                    <br>
                </form>
                <?php
                if (isset($_POST['Valider'])) {
                    for ($i = 0; $i < 1000; $i++) {
                        if (isset($_POST['offre' . $i]) && $_POST['offre' . $i] !== '') {
                            //echo 'Vous avez mis l offre '.$i. ' en priorite : '.$_POST['offre'.$i];
                            //echo '<br/>';
                            $id = $_SESSION['user'];
                            $prio = $_POST['offre' . $i];

                            $req4 = $bdd->prepare('SELECT * FROM postule_m1 WHERE idUtilisateur=? AND idOffre =?');
                            $req4->execute(array($id, $i));
                            $req4_reponse = $req4->fetch();
                            $count = $req4->rowcount();

                            if ($count !== 0) {
                                $req3 = $bdd->prepare('UPDATE postule_m1 SET priorite= ? WHERE idUtilisateur=? AND idOffre=?');
                                $req3->execute(array($prio, $id, $i));
                                $update = $req3->fetch();
                            } else {
                                $req2 = "INSERT INTO postule_m1 (idUtilisateur, idOffre, priorite) VALUES ('$id','$i','$prio')";
                                $result = $bdd->query($req2);
                                $result = $result->fetch();
                            }
                        } elseif ((!isset($_POST['offre' . $i])) || (isset($_POST['offre' . $i]) && $_POST['offre' . $i] == '')) {
                            $id = $_SESSION['user'];
                            $prio = Null;

                            $req8 = $bdd->prepare('SELECT * FROM postule_m1 WHERE idUtilisateur=? AND idOffre =?');
                            $req8->execute(array($id, $i));
                            $req8_reponse = $req8->fetchAll();
                            $count8 = $req8->rowcount();

                            if ($count8 !== 0) {
                                $req7 = $bdd->prepare('DELETE FROM postule_m1 WHERE idUtilisateur=? AND idOffre=?');
                                $req7->execute(array($id, $i));
                                $update = $req7->fetchAll();
                            }
                        }
                        $id = $_SESSION['user'];

                        //$count = $req->rowcount();

                        if (isset($_POST['textAreaEntretien' . $i])) {
                            $entretien = $_POST['textAreaEntretien' . $i];

                            $reque = $bdd->prepare('UPDATE postule_m1 SET cr_entretien=? WHERE idUtilisateur=? and idOffre=?');
                            $reque->execute(array($entretien, $id, $i));
                            $update = $reque->fetchAll();
                        }
                    }
                    echo "<script>window.location.replace(\"dashboardPersonnel.php\")</script>";
                }
                ?>

            </div> <!--fin div de section 1 -->

<!----------------------------Footer------------------------------------------->

        </div> <!-- fin div de page-->
    </main>
    <?php
        include('fonctionality/footer.php');
    ?>
</div> <!-- fin body de page-->
<script>
    $(function () {
        $(".modif").change(function () {
            let item = this;
            //console.log($(this).children("option:selected").val());
            let v = $(this).children("option:selected").val();
            let deja = $('.modif option:selected[value=' + v + ']');
            //console.log('nombre:'+deja.length);
            if (deja.length > 1) {
                $(".modif").each(function () {
                    if (this !== item) {
                        let x = parseInt($(this).children("option:selected").val());
                        if (x >= v) {
                            let z = x + 1;
                            //console.log('a changer' + x + 'par' + z );
                            $(this).children('option:selected').prop('selected', false);
                            $(this).children('option[value="' + z + '"]').prop('selected', true);
                        }
                    }
                });
            }
        });
    });
</script>