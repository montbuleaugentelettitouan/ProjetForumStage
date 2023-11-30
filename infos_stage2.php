<?php
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
        window.location.href = "infos_stage2.php?value=" + selectedValue;

    }
</script>

<!-- Body de la page -->
<div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4"> <!-- div de page-->
            <h1 class="mt-4">Suivi du stage</h1>
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
                        <select onchange="detailEtu()" name="Etudiant" id="Etudiant" >
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
                        <input type="submit" class="btn btn-warning" name="retour" value="Back">
                    </div> <!--fin div de tableau 1 -->
                </form>
                <br>
            </div> <!--fin div de section 1 -->

            <?php
            if (isset($_POST['retour'])) {
                echo "<script>window.location.replace(\"infos_stage.php\")</script>";
            }
            ?>

            <!-- Affichage du nom de l'étudiant sélectionné et ses informations -->

            <div class="card mb-4"> <!--div de section 2 -->
                <div class="card-header"> <!--div de encadré 2 -->
                    <i class="far fa-file-pdf"></i>
                    Affichage du détail du stage de l'étudiant
                </div> <!--fin div de encadré 2 -->
            </div> <!--fin div de section 2 -->

            <div class="card-body"> <!--div de tableau 2 -->
                <?php
                $valid = $selectedValue;

                $user = $bdd->query("SELECT * FROM utilisateur WHERE idUtilisateur='$valid'");
                $resultuser = $user->fetch();
                ?>
                <h2> <?php echo $resultuser['nom'] ?> <?php echo ''?> <?php echo $resultuser['prenom'] ?> </h2>

                <!-- Affichage du rappel de l'offre acceptée-->

                <h5 class="card-title">Rappel de l'offre acceptée</h5>
                <table class="table table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Intitulé de l'offre</th>
                        <th>Entreprise</th>
                        <th>Site</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $verif = $bdd->prepare('SELECT titre, nomSite, nomEntreprise FROM convention_contrat JOIN offre ON (idOffre) JOIN site on offre.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise WHERE convention_contrat.idUtilisateur =?');
                    $verif->execute(array($valid));
                    $result = $verif->fetch();

                    $titre = !empty($result['titre']) ? $result['titre'] : NULL ;
                    $ent = !empty($result['nomEntreprise']) ? $result['nomEntreprise'] : NULL ;
                    $site = !empty($result['nomSite']) ? $result['nomSite'] : NULL ;
                    ?>
                    <tr>
                        <td><?php echo $titre; ?></td>
                        <td><?php echo $ent; ?></td>
                        <td><?php echo $site; ?></td>
                    </tr>
                    </tbody>
                </table>

            </div> <!--fin div de tableau 2 -->

            <!-- Affichage des informations du stage-->

            <div class="card-body"> <!-- div de tableau 3-->
                <h5 class="card-title">Informations du stage</h5>
                <table class="table table-striped" >
                    <?php
					// récupération de toutes les informations a affiché dans le dernier tableau
                    $req = $bddd->prepare("SELECT nomMDS, prenomMDS,adresse_postale, pays, ville_stage, numTuteur, emailTuteur, type_contrat, dateDeb, dateFin, annee_stage, code_postal, secteur, presentiel, nom_tuteur_academique, prenom_tuteur_academique, gratification, format_gratification FROM stage JOIN site on stage.idSite = site.idSite WHERE stage.idUtilisateur = ?");
                    $req->execute(array($valid));
                    $resultat = $req->fetch();

					// initialisation des résultats à nul s'il n'y a pas de résultat retourné, comme ça pas d'erreur d'affichage
                    $nomT = !empty($resultat['nomTuteur']) ? $resultat['nomTuteur'] : NULL ;
                    $prenomT = !empty($resultat['prenomTuteur']) ? $resultat['prenomTuteur'] : NULL ;
                    $numT = !empty($resultat['numTuteur']) ? $resultat['numTuteur'] : NULL ;
                    $mailT = !empty($resultat['emailTuteur']) ? $resultat['emailTuteur'] : NULL ;
                    $pays = !empty($resultat['pays']) ? $resultat['pays'] : NULL ;
                    $ville = !empty($resultat['ville_stage']) ? $resultat['ville_stage'] : NULL ;
                    $contrat = !empty($resultat['type_contrat']) ? $resultat['type_contrat'] : NULL ;
                    $deb = !empty($resultat['dateDeb']) ? $resultat['dateDeb'] : NULL ;
                    $fin = !empty($resultat['dateFin']) ? $resultat['dateFin'] : NULL ;
                    $year = !empty($resultat['annee_stage']) ? $resultat['annee_stage'] : NULL ;
                    $code_postal = !empty($resultat['code_postal']) ? $resultat['code_postal'] : NULL ;
					$adresse_postale = !empty($resultat['adresse_postale']) ? $resultat['adresse_postale'] : NULL ;
                    $presentiel = !empty($resultat['presentiel']) ? $resultat['presentiel'] : NULL ;
                    $secteur = !empty($resultat['secteur']) ? $resultat['secteur'] : NULL ;
                    $nomtuteuraca = !empty($resultat['nom_tuteur_academique']) ? $resultat['nom_tuteur_academique'] : NULL ;
                    $prenomtuteuraca = !empty($resultat['prenom_tuteur_academique']) ? $resultat['prenom_tuteur_academique'] : NULL ;
					$gratif = !empty($resultat['gratification']) ? $resultat['gratification'] : NULL ;
                    $formatGratif = !empty($resultat['format_gratification']) ? $resultat['format_gratification'] : NULL ;
                    ?>
					
					<!-- affichage du tableau des informations de stage de l'étudiant -->
                    <tr>
                        <th>Tuteur professionnel</th>
                        <td><?php echo $nomT; ?> <?php echo " "?><?php echo $prenomT; ?></td>
                    </tr>
                    <tr>
                        <th>Numéro du tuteur professionnel</th>
                        <td><?php echo $numT; ?></td>
                    </tr>
                    <tr>
                        <th>Mail du tuteur professionnel</th>
                        <td><?php echo $mailT; ?></td>
                    </tr>
                    <tr>
                        <th>Pays</th>
                        <td><?php echo $pays; ?></td>
                    </tr>
                    <tr>
                        <th>Ville</th>
                        <td><?php echo $ville; ?></td>
                    </tr>
                    <tr>
                        <th>Code Postal</th>
                        <td><?php echo $code_postal; ?></td>
                    </tr>
					 <tr>
                        <th>Adresse Postal</th>
                        <td><?php echo $adresse_postale; ?></td>
                    </tr>
                    <tr>
                        <th>Type de contrat</th>
                        <td><?php echo $contrat; ?></td>
                    </tr>
                    <tr>
                        <th>Stage en : </th>
                        <td><?php echo $presentiel; ?></td>
                    </tr>
                    <tr>
                        <th>Secteur d'activité</th>
                        <td><?php echo $secteur; ?></td>
                    </tr>
                    <tr>
                        <th>Date de début</th>
                        <td><?php echo $deb; ?></td>
                    </tr>
                    <tr>
                        <th>Date de fin</th>
                        <td><?php echo $fin; ?></td>
                    </tr>
                    <tr>
                        <th>Année</th>
                        <td><?php echo $year; ?></td>
                    </tr>
                    <tr>
                        <th>Tuteur académique </th>
                        <td> <?php echo $prenomtuteuraca; ?> <?php echo " "?> <?php echo $nomtuteuraca; ?></td>
                    </tr>
					<tr>
                        <th>Gratification </th>
                        <td> <?php echo $gratif; ?> <?php echo " "?> <?php echo $formatGratif; ?></td>
                    </tr>
                </table>
            </div> <!--fin div de tableau 3 -->


            <!----------------------------Footer------------------------------------------->

        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>
