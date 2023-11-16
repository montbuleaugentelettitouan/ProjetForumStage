<?php
//copie-protection de dashboardPersonnel.php (non modifiée)
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */
// ajout des fonctioinalitées php
include('barre_nav_M1.php');
include('fonctionality/bdd.php');
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!-- Affichage des paramètres de session de l'utilisateur (voir fonction login pour pkus de détails-->
            <h1 class="mt-4">Tableau de bord de <?php echo $_SESSION['nom']; ?> <?php echo $_SESSION['prenom']; ?></h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Vue de vos choix</li>
            </ol>

            <form id="tableperso" method="post">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="far fa-file-pdf"></i>
                        Mes offres
                    </div>
                    <div class="card-body">

                        <!-- <form id="datatablesSimple"> -->

                        <!-- Table d'affichage des offres -->
                        <table class="table table-bordered">
                            <thead class="thead-dark">

                            <tr>
                                <th>Id</th>
                                <th>Nom</th>
                                <th>Entreprise</th>
                                <th>Description</th>
                                <th>Priorité</th>
                                <th>Priorité enregistré</th>
                                <th>Compte-rendu de l'entretien</th>
                            </tr>
                            </thead>


                            <tbody>
                            <?php
                            // requète pour sélectionner les offres de la table OFFRES inscrites dans la table MES CHOIX (jointure)
                            // OPn ajoute une autre jointure pour limliter la sélection des offres ou l'ID utilisateur correspond à MES CHOIX
                            $req = $bdd->prepare('select idOf, nomOf, entreprise, description, priorite, CR_entretien FROM choix_offre JOIN offres_stages ON choix_offre.idOffre = offres_stages.idOf WHERE choix_offre.idUtilisateur=?');
                            $req->execute(array($_SESSION['user']));
                            $resultat = $req->fetchAll();

                            // on compte les lignes pour valider lors du développement
                            $count = $req->rowcount();

                            foreach ($resultat as $ligne) { ?>
                                <tr>
                                    <!-- on affiche chaques information fetch dans chaques lignes du tableau -->
                                    <td><?php echo $ligne['idOf']; ?></td>
                                    <td><?php echo $ligne['nomOf']; ?></td>
                                    <td><?php echo $ligne['entreprise']; ?></td>
                                    <td><?php echo $ligne['description']; ?></td>
                                    <td>
                                        <select class="form-select modif" aria-label="Default select example"
                                                name="offre<?php echo $ligne['idOf']; ?>">
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
                                    <td><?php echo $ligne['priorite']; ?></td>
                                    <td>
                                        <textarea name="textAreaEntretien" id="textAreaEntretien" class="form-control"
                                        rows="3" required > </textarea></td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                        <br>
                    </div>
                </div>
                <input type="submit" class="btn btn-warning" name="Valider" value="Valider">
                <!--<input type="button" onclick="window.location.href = 'CR_entretien.php';" class="btn btn-primary" name="entretiens" value="Compte-rendu des entretiens" >-->
                <!--<input type="button" class="btn btn-primary" name="entretiens" value="Compte-rendu des entretiens" > -->
            </form>
            <br>

            <?php
            if (isset($_POST['Valider'])) {
                for ($i = 0; $i < 1000; $i++) {
                    if (isset($_POST['offre' . $i]) && $_POST['offre' . $i] !== '') {
                        //echo 'Vous avez mis l offre '.$i. ' en priorite : '.$_POST['offre'.$i];
                        //echo '<br/>';
                        
                        $id = $_SESSION['user'];
                        $prio = $_POST['offre' . $i];

                        $req4 = $bdd->prepare('Select * from choix_offre where idUtilisateur=? AND idOffre =?');
                        $req4->execute(array($id, $i));
                        $req4_reponse = $req4->fetch();
                        $count = $req4->rowcount();

                        if ($count !== 0) {
                            $req3 = $bdd->prepare('UPDATE choix_offre SET priorite= ? WHERE idUtilisateur=? AND idOffre=?');
                            $req3->execute(array($prio, $id, $i));
                            $update = $req3->fetch();
                        } else {
                            $req2 = "insert into choix_offre (idUtilisateur, idOffre, priorite) values('$id','$i','$prio')";
                            $result = $bdd->query($req2);
                            $result = $result->fetch();
                        }
                        

                    } elseif ($_POST['offre' . $i] == '') {
                        $id = $_SESSION['user'];
                        $prio = Null;
                        
                        $req8 = $bdd->prepare('Select * from choix_offre where idUtilisateur=? AND idOffre =?');
                        $req8->execute(array($id, $i));
                        $req8_reponse = $req8->fetchAll();
                        $count8 = $req8->rowcount();

                        if ($count8 !== 0) {
                            $req7 = $bdd->prepare('DELETE FROM choix_offre WHERE idUtilisateur=? AND idOffre=?');
                            $req7->execute(array($id, $i));
                            $update = $req7->fetchAll();
                        }

                    }

                    $id = $_SESSION['user'];

                    if (isset($_POST['textAreaEntretien'.$i])) {
                        $entretien = $_POST['textAreaEntretien'.$i];

                        $reque = $bdd->prepare('UPDATE choix_offre SET CR_entretien=? WHERE idUtilisateur=? and idOffre=?');
                        $reque->execute(array($entretien,$id,$i));
                        $update = $reque->fetchAll();
                    }
                
                    /*$entretien = $_POST['textAreaEntretien'];

                    $reque = $bdd->exec('UPDATE choix_offre SET CR_entretien=? WHERE idUtilisateur=? AND idOffre=?)');
                    $reque->execute(array($entretien, $id, $i));
                    $update = $reque->fetchAll();*/

                }

                //echo "<script>window.location.replace(\"dashboardM1.php\")</script>";
            }
            ?>

        </div>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>
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