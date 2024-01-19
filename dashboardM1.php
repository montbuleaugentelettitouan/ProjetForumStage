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
include('fonctionality/annee+promo.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4"> <!-- div de page-->

        <!--Récupération du nom et du prenom grâce à la page de connexion utilisateur et affichage du nom et du prénom de la personne connectée -->

        <h1 class="mt-4">Offres <?php echo $annee; ?></h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Vue générale de toutes les offres</li>
        </ol>

            <div class="card mb-4"> <!--div de section 1 -->
                <div class="card-header"> <!--div de encadré 1 -->
                    
                </div> <!-- fin div de encadré 1 -->

                <form id="dataglobal" method="post">
                    <div class="card-body"> <!--div de tableau 1 -->
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Entreprise</th>
                                    <th>Site</th>
                                    <th>Intitulé de l'offre</th>
                                    <th>Description</th>
                                    <th>PDF</th>
                                    <th>Priorité</th>
                                </tr>
                            </thead>
                            <tbody>

                            <div class="form-group">
                                <p><b>Veuillez ne pas mettre la même priorité sur 2 offres distinctes ! </b></p>
                            </div>

                            <!--Execution d'une requête permettant d'afficher les différentes offres inscrites dans la base de données en fonction d'un utilisateur-->

                                <?php
                                    $req = $bdd->prepare('SELECT offre.idOffre, nomEntreprise, nomSite, titre, description, priorite FROM offre JOIN site on offre.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise LEFT JOIN postule_m1 on offre.idOffre = postule_m1.idOffre AND postule_m1.idUtilisateur = ? where offre.anneeO = ? AND offre.valider = 1 ORDER BY entreprise.nomEntreprise ASC');
                                    $req->execute(array($_SESSION['user'], $annee));
                                    //$resultat = $bdd->query($req);
                                    $resultat = $req->fetchAll();

                                    foreach ($resultat as $ligne) {
                                ?>
                                <tr>
                                    <td><?php echo $ligne['nomEntreprise']; ?></td>
                                    <td><?php echo $ligne['nomSite']; ?></td>
                                    <td><?php echo $ligne['titre']; ?></td>
                                    <td><?php echo $ligne['description']; ?></td>
                                    <td><a href="telechargement_pdf.php?id=<?php echo $ligne['idOffre']; ?>" class="btn btn-primary">Télécharger le PDF</a></td>
                                    <td>
                                        <select class="form-select modif" aria-label="Default select example"
                                                name="offre<?php echo $ligne['idOffre']; ?>">
                                            <option selected></option>                                        
                                            <?php
                                            //Ajout de la condition pour que la priorité aille de 1 à 15, avec affichage de la priorité selectionnée
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
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div> <!--fin div de tableau 1 -->
                    <div class="card mb-4">
                    <input type="submit" class="btn btn-warning" name="Valider" value="Valider mes choix">
                    </div>
                </form>
                <?php
                    if (isset($_POST['Valider'])) {

                        for ($i = 0;$i <1000;$i++) {
                            // si il y a quelque chose dans le champ offre et priorité
                            if (isset($_POST['offre'.$i]) && $_POST['offre'.$i] !== '') {
                                //on récupère l'id et la session utilisateur
                                $id = $_SESSION['user'];
                                $prio = $_POST['offre'.$i];

                                //requête pour pour selectionner et compter toutes les lignes presentes dans la table choix_offre qui correspondent à l'utilisateur en question
                                $req4 = $bdd->prepare('SELECT * FROM postule_m1 WHERE idUtilisateur=? AND idOffre =?');
                                $req4->execute(array($id, $i));
                                $req4_reponse=$req4->fetchAll();
                                $count = $req4->rowcount();

                                //Si une ligne correspond deja on va juste modifier l'enregistrement dans la bdd
                                if ($count!==0){
                                    $req3 = $bdd->prepare('UPDATE postule_m1 SET priorite= ? WHERE idUtilisateur=? AND idOffre=?');
                                    $req3->execute(array($prio, $id, $i));
                                    $update = $req3->fetchAll();
                                }
    
                                //Sinon on ajoute une nouvelle information dans la table choix_offre
                                else{
                                    $req2 = "INSERT INTO postule_m1 (idUtilisateur, idOffre, priorite) VALUES ('$id','$i','$prio')";
                                    $result = $bdd->query($req2);
                                    $result = $result->fetchAll();
                                }

                                // Sélection d'une offre mais pas de priorité

                            }
                            elseif ($_POST['offre'.$i] == '') {
                                $id = $_SESSION['user'];
                                $prio = Null;
    
                                //Recherche de l'utilisateur et de l'offre dans la table
                                $req8 = $bdd->prepare('SELECT * FROM postule_m1 WHERE idUtilisateur=? AND idOffre =?');
                                $req8->execute(array($id, $i));
                                $req8_reponse=$req8->fetchAll();
                                $count8 = $req8->rowcount();
    
                                // Si elle est présente sans priorité, ça se supprime de la table
                                if ($count8!==0){
                                    $req7 = $bdd->prepare('DELETE FROM postule_m1 WHERE idUtilisateur=? AND idOffre=?');
                                    $req7->execute(array($id, $i));
                                    $update = $req7->fetchAll();
                                }
                            }
                        
                        }

                        echo "<script>window.location.replace(\"dashboardM1.php\")</script>";
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
</body>
</html>