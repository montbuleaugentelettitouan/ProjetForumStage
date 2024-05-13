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
            <h1 class="mt-4">Informations du contrat</h1>

            <!-- Affichage du nom de l'étudiant sélectionné et ses informations -->
            <div class="card mb-4"> <!--div de section 2 -->
                <div class="card-header"> <!--div de encadré 2 -->
                    Affichage du détail du contrat de l'étudiant
                </div> <!--fin div de encadré 2 -->
            </div> <!--fin div de section 2 -->
            <div class="card-body"> <!--div de tableau 2 -->
                <?php
                $valid = $selectedValue;

                $user = $bdd->query("SELECT * FROM utilisateur WHERE idUtilisateur='$valid'");
                $resultuser = $user->fetch();
                ?>
                <h2> <?php echo $resultuser['nom'] ?> <?php echo ''?> <?php echo $resultuser['prenom'] ?> </h2>
                <br>
                <div>

                    <a href="gestion_etudiants_alternance.php">
                        <input type="submit" class="btn btn-warning" name="retour" id="retour" value="Tableau des étudiants M2">
                    </a>
                </div>
                <br>
                <!-- Affichage du rappel de l'offre acceptée-->
                <h5 class="card-title">Rappel du stage</h5>
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
                    $verif = $bdd->prepare("SELECT titre, nomSite, nomEntreprise FROM convention_contrat JOIN offre USING (idOffre) JOIN site on offre.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise WHERE convention_contrat.idUtilisateur =? AND offre.niveau = 'M2'");
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
                    $req = $bdd->prepare("
                    SELECT s.adresse_postale, s.pays, s.ville, s.code_postal, cc.type_contrat, cc.dateDeb, cc.dateFin, o.anneeO, o.secteur, o.presentiel, cc.gratification, cc.format_gratification, mds.nomMDS, mds.prenomMDS, mds.numMDS, mds.emailMDS, ta.nomTA, ta.prenomTA, ta.numTA, ta.emailTA
                        FROM offre o
                        LEFT JOIN convention_contrat cc ON o.idOffre = cc.idOffre
                        LEFT JOIN tuteur_academique ta ON cc.idTA = ta.idTA
                        LEFT JOIN maitre_de_stage mds ON cc.idMDS = mds.idMDS
                        LEFT JOIN site s ON mds.idSite = s.idSite
                        WHERE cc.idUtilisateur = ? AND o.niveau = 'M2'");
                    $req->execute(array($valid));
                    $resultat = $req->fetch();

                    // initialisation des résultats à nul s'il n'y a pas de résultat retourné, comme ça pas d'erreur d'affichage
                    $nomMDS = !empty($resultat['nomMDS']) ? $resultat['nomMDS'] : NULL ;
                    $prenomMDS = !empty($resultat['prenomMDS']) ? $resultat['prenomMDS'] : NULL ;
                    $numMDS = !empty($resultat['numMDS']) ? $resultat['numMDS'] : NULL ;
                    $mailMDS = !empty($resultat['emailMDS']) ? $resultat['emailMDS'] : NULL ;
                    $pays = !empty($resultat['pays']) ? $resultat['pays'] : NULL ;
                    $ville = !empty($resultat['ville']) ? $resultat['ville'] : NULL ;
                    $contrat = !empty($resultat['type_contrat']) ? $resultat['type_contrat'] : NULL ;
                    $deb = !empty($resultat['dateDeb']) ? $resultat['dateDeb'] : NULL ;
                    $fin = !empty($resultat['dateFin']) ? $resultat['dateFin'] : NULL ;
                    $year = !empty($resultat['anneeO']) ? $resultat['anneeO'] : NULL ;
                    $code_postal = !empty($resultat['code_postal']) ? $resultat['code_postal'] : NULL ;
                    $adresse_postale = !empty($resultat['adresse_postale']) ? $resultat['adresse_postale'] : NULL ;
                    $presentiel = !empty($resultat['presentiel']) ? $resultat['presentiel'] : NULL ;
                    $secteur = !empty($resultat['secteur']) ? $resultat['secteur'] : NULL ;
                    $nomTA = !empty($resultat['nomTA']) ? $resultat['nomTA'] : NULL ;
                    $prenomTA = !empty($resultat['prenomTA']) ? $resultat['prenomTA'] : NULL ;
                    $numTA = !empty($resultat['numTA']) ? $resultat['numTA'] : NULL ;
                    $mailTA = !empty($resultat['emailTA']) ? $resultat['emailTA'] : NULL ;
                    $gratif = !empty($resultat['gratification']) ? $resultat['gratification'] : NULL ;
                    $formatGratif = !empty($resultat['format_gratification']) ? $resultat['format_gratification'] : NULL ;
                    ?>

                    <!-- affichage du tableau des informations de stage de l'étudiant -->
                    <tr>
                        <th>Maître de stage</th>
                        <td><?php echo $nomMDS; ?> <?php echo " "?><?php echo $prenomMDS; ?></td>
                    </tr>
                    <tr>
                        <th>Téléphone du maître de stage</th>
                        <td><?php echo $numMDS; ?></td>
                    </tr>
                    <tr>
                        <th>Mail du maître de stage</th>
                        <td><?php echo $mailMDS; ?></td>
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
                        <th>Adresse Postale</th>
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
                        <td> <?php echo $prenomTA; ?> <?php echo " "?> <?php echo $nomTA; ?></td>
                    </tr>
                    <tr>
                        <th>Numéro du tuteur académique</th>
                        <td><?php echo $numTA; ?></td>
                    </tr>
                    <tr>
                        <th>Mail du tuteur académique</th>
                        <td><?php echo $mailTA; ?></td>
                    </tr>
                    <tr>
                        <th>Gratification </th>
                        <td> <?php echo $gratif; ?> <?php echo " "?> <?php echo $formatGratif; ?>
                        </td>
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

