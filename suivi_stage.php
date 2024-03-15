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
 include('fonctionality/annee+promo.php');
 ?>
 <style>
    p {
    padding-left : 20px;
    }

    form {
        margin-left: 30px;
    }

    label {
        width:70%;
        display: inline-block;
        text-align:left;
        margin-bottom: 10px;
        margin-top: 10px;
    }

    select {
        width:70%;
        display: inline-block;
        text-align:left;
        margin-bottom: 10px;
        margin-top: 10px;
    }

    input {
        border-radius: 5px;
        margin-bottom: 10px;
        padding:  10px;
        width:  70%;
        padding-left:  20px;
        border: 0.1px solid grey;
    }
 
    input[type=submit] {
        border: 0.5px ;
        border-radius: 5px;
        width: 80px;
        -webkit-appearance: none;
        margin-top: 20px;
        margin-bottom: 20px;
    }
 </style>
 <div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4"> <!-- div de page-->

            <!--Récupération du nom et du prenom grâce à la page de connexion utilisateur et affichage des informations -->
            <h1 class="mt-4">Suivi du stage de <?php echo $_SESSION['nom']; ?> <?php echo $_SESSION['prenom']; ?></h1>
            <h4 class="mt-4">Vous avez accepté le stage suivant : </h4>
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
                            // requête pour afficher les informations de l'offre que l'Utilisateur à acceptée
                            $req = $bdd->prepare('SELECT nomEntreprise, nomSite, titre, description FROM convention_contrat JOIN offre USING (idOffre) JOIN site USING (idSite) JOIN entreprise USING (idEntreprise) WHERE convention_contrat.idUtilisateur = ?');
                            $req->execute(array($_SESSION['user']));
                            $resultat = $req->fetchAll();

                            // on compte les lignes pour valider lors du développement
                            $count = $req->rowcount();

                            foreach ($resultat as $ligne) {
                        ?>
                        <tr>

                            <td><?php echo $ligne['nomEntreprise']; ?></td>
                            <td><?php echo $ligne['nomSite']; ?></td>
                            <td><?php echo $ligne['titre']; ?></td>
                            <td><?php echo $ligne['description']; ?></td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
            </div> <!--fin div de tableau 1 -->

            <?php
                // requête pour afficher les informations de l'offre que l'Utilisateur à acceptée
                $req = $bdd->prepare('SELECT nomEntreprise, site.idSite, nomSite, titre, nomMDS, prenomMDS, numMDS, emailMDS, type_contrat, description, ville, pays, presentiel, code_postal, secteur, dateDeb, dateFin, adresse_postale, offre.idOffre FROM convention_contrat LEFT JOIN offre on convention_contrat.idOffre = offre.idOffre LEFT JOIN site on offre.idSite = site.idSite LEFT JOIN entreprise on site.idEntreprise = entreprise.idEntreprise LEFT JOIN maitre_de_stage ON site.idSite = maitre_de_stage.idSite WHERE convention_contrat.idUtilisateur = ? LIMIT 1');
                $req->execute(array($_SESSION['user']));
                $resultat = $req->fetchAll();

                $countr = $req->rowcount();

                //on initialise toutes les variables dans le cas où il n'y aucune données déja renseignées
                $nomMDS = "";
                $prenomMDS = "";
                $numMDS = "";
                $emailMDS = "";
                //$nomTA = "";
                //$prenomTA = "";
                $typeContrat = "";
                $ville = "";
                $pays = "";
                $selectedContratstage = "";
                $selectedContratapp = "";
                $selectedContratpro = "";
                $nomEntreprise = "";
                $nomSite ="";
                $selectedPres = "";
                $selectedDist = "";
                $distPres = "";
                $code_postal="";
                $secteur = "";
                $adressePostale = "";
                //récupération de la date actuelle au cas où aucune date n'est rentré en base 
                $DateDeb = date('Y-m-d');
                $DateFin = date('Y-m-d');

                $idOffre = "";
                $idSite = "";

                if($countr != 0){

                    //récupération des valeurs lignes par lignes
                    foreach ($resultat as $ligne ){
                    $nomMDS = $ligne['nomMDS'];
                    $prenomMDS = $ligne['prenomMDS'];
                    $numMDS = $ligne['numMDS'];
                    $emailMDS = $ligne['emailMDS'];
                    //$nomTA = $ligne['nomTA'];
                    //$prenomTA = $ligne['prenomTA'];
                    $typeContrat = $ligne['type_contrat'];
                    $nomEntreprise = $ligne['nomEntreprise'];
                    $nomSite = $ligne['nomSite'];
                    $ville = $ligne['ville'];
                    $pays = $ligne['pays'];
                    $distPres =  $ligne['presentiel'];
                    $code_postal= $ligne['code_postal'];
                    $secteur =  $ligne['secteur'];
                    $DateDeb = $ligne['dateDeb'];
                    $DateFin = $ligne['dateFin'];
                    $adressePostale = $ligne['adresse_postale'];

                    $idOffre = $ligne['idOffre'];
                    $idSite = $ligne['idSite'];

                    }

                    //on récupère ensuites les valeurs déjà renseigner pour pouvoir les préremplir automatiquement dans le formulaire
                    //on appelera ensuite ces variables dans les input du formulaire pour ajouter l'attribut selected au bon endroit 
                    if($nomEntreprise != ""){
                        $selectedEntreprise = "selected";
                    }
                    if($nomSite != ""){
                        $selectedSite = "selected";
                    }
                    if($typeContrat != ""){
                        if($typeContrat == "stage"){
                            $selectedContratstage = "selected";
                        }
                        if($typeContrat == "apprentissage"){
                            $selectedContratapp = "selected";
                        }
                        if($typeContrat == "pro"){
                            $selectedContratpro = "selected";
                        }
                    }
                    if($distPres != ""){
                        if($distPres == "presentiel"){
                            $selectedPres = "selected";
                        }
                        if($distPres == "distanciel"){
                            $selectedDist = "selected";
                        }
                    }
                }

            ?>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Veuillez remplir les informations concernant le stage.</li>
            </ol>

                <div class="card mb-4"> <!--div de section 1 -->
                    <br>
                <p><b><span style="color: red;">*</span></b> : Saisie obligatoire</p>
                    <form id="formulaireinscription" method="post">
                        <div class="card-body"> <!--div de tableau 1 -->
                            <label for="NomMDS"><b>Nom du maître de stage <b><span style="color: red;">*</span></b> : </b></label>
                                <br>
						        <input type="text" id="NomMDS" name="NomMDS" value= "<?php echo($nomMDS)?>" required>
						        <br>
                            <label for="PrenomMDS"><b>Prénom du maître de stage <b><span style="color: red;">*</span></b> : </b></label>
						        <br>
						        <input type="text" id="PrenomMDS" name="PrenomMDS" value= "<?php echo($prenomMDS)?>" required>
						        <br>
                            <label for="NumMDS"><b>Numéro de téléphone du maître de stage : </b></label>
                                <br>
						        <!--<input type="tel" id="NumMDS" name="NumMDS" pattern="[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}|[0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}|[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{2}">-->
                                <input type="text" id="NumMDS" name="NumMDS" value= "<?php echo($numMDS)?>" required>
                                <br>
                            <label for="emailMDS"><b>Adresse mail du maître de stage <b><span style="color: red;">*</span></b> : </b></label>
                                <br>
						        <input type="email" id="emailMDS" name="emailMDS" value= "<?php echo($emailMDS)?>" required>
						        <br>
                            <label for="TuteurA"><b>Tuteur académique<b><span style="color: red;">*</span></b> : </b></label>
                            <select name="TuteurA">
                                <?php
                                // Exécuter la requête pour récupérer les tuteurs académiques
                                $req_tuteurs = $bdd->prepare('SELECT nomTA, prenomTA FROM tuteur_academique');
                                $req_tuteurs->execute();
                                $result_tuteurs = $req_tuteurs->fetchAll();

                                // Afficher les options de la liste déroulante
                                if ($req_tuteurs->rowCount() > 0) {
                                    foreach ($result_tuteurs as $row) {
                                        // Utiliser les valeurs de nom et prénom pour chaque tuteur académique
                                        echo "<option value='" . $row["nomTA"] . " " . $row["prenomTA"] . "'>" . $row["nomTA"] . " " . $row["prenomTA"] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Aucun tuteur académique trouvé</option>";
                                }
                                ?>
                            </select>
                            <label for="Postal"><b>Code Postal <b><span style="color: red;">*</span></b> : </b></label>
                                <br>
                                <input type="text" id="Postal" name="Postal" value = "<?php echo $code_postal ?>"required>
                                <br>
                            <label for="Adresse_postale"><b>Adresse postale <b><span style="color: red;">*</span></b> (adresse correspondant au lieu de stage) : </b></label>
						        <br>
						        <input type="text" id="Adresse_postale" name="Adresse_postale" value = "<?php echo $adressePostale ?>" required>
						        <br>
                            <label for="dist_pres"><b>Distanciel ou Présentiel <b><span style="color: red;">*</span></b> : </b></label>
                            <select  name="dist_pres">
                                <option
                                    <?php echo $selectedPres?>
                                        value = "presentiel">Présentiel</option>
                                <option
                                    <?php echo $selectedDist?>
                                        value = "distanciel">Distanciel</option>
                            </select>
                            <label for="DateDeb"><b>Date de début de stage <b><span style="color: red;">*</span></b> : </b></label>
						        <br>
						        <input type="date" id="DateDeb" name="DateDeb" min= "2020-31-12" max= "2050-31-12" value = "<?php echo date('Y-m-d', strtotime($DateDeb)); ?>" required>
						        <br>
                            <label for="DateFin"><b>Date de fin de stage <b><span style="color: red;">*</span></b> : </b></label>
                                <br>
						        <input type="date" id="DateFin" name="DateFin" min= "2020-31-12" max= "2050-31-12" value = "<?php echo $DateFin ?>" required>
						        <br>
                        </div> <!--fin div de tableau 1 -->
                        <br>
                        <input type="submit" class="btn btn-warning" name="Validersuivi" value="Valider">
                    </form>
                    <?php
                        if (isset($_POST['Validersuivi'])) {
                            //données du formulaire sous forme de variables
                            $id=$_SESSION['user'];
                    
                            $nomMS = $_POST['NomMDS'];
                            $nomMS=strtoupper($nomMS);
                    
                            $prenomMS = $_POST['PrenomMDS'];
                            $prenomMS=strtoupper($prenomMS);
                    
                            $numMS = $_POST['NumMDS'];
                            $mailMS = $_POST['emailMDS'];

                            $NPTA = $_POST['TuteurA'];
                            $NomVerif = "";
                            // Explode le nom/prénom pour avoir le nom, requete pour trouver l'id, ajouter id à convention

                            $code_postal = $_POST['Postal'];

                            $distPres = $_POST['dist_pres'];
                            $distPres=strtolower($distPres);
                    
                            $DateDeb = $_POST['DateDeb'];
                            $DateFin = $_POST['DateFin'];

                            $adressePostale = $_POST['Adresse_postale'];

                            $verifStage = $bdd->prepare('SELECT * FROM convention_contrat WHERE idUtilisateur = ?');
                            $verifStage->execute(array($id));
                            $resultVerif = $verifStage->fetch();
                            $count = $verifStage->rowcount();
                    
                            if ($count !=0) {
                                if($DateDeb < $DateFin){

                                    $idMDS = "";

                                    // On regarde d'abord si un maître de stage avec le nom et prénom rentrés existe déjà
                                    $reqIdMDS = $bdd->prepare('SELECT idMDS FROM maitre_de_stage WHERE nomMDS = ? AND prenomMDS = ?');
                                    $reqIdMDS->execute(array($nomMS,$prenomMS));

                                    // On vérifie le nombre de lignes retournées par la requête
                                    $nombreResultats = $reqIdMDS->rowCount();

                                    // Si aucun maître de stage est trouvé, on l'ajoute puis on récupère son ID, sinon on a déjà son ID
                                    if ($nombreResultats == 0) {
                                        $upMDS = $bdd->prepare('INSERT INTO maitre_de_stage (nomMDS, prenomMDS, numMDS, emailMDS, idSite) VALUES (?,?,?,?,?)');
                                        $upMDS->execute(array($nomMS,$prenomMS,$numMS,$mailMS,$idSite));

                                        // On récupère l'ID du nouveau maître de stage
                                        $reqIdMDS2 = $bdd->prepare('SELECT idMDS FROM maitre_de_stage WHERE nomMDS = ? AND prenomMDS = ?');
                                        $reqIdMDS2->execute(array($nomMS,$prenomMS));

                                        $resultat = $reqIdMDS2->fetch();
                                        $idMDS = $resultat['idMDS'];
                                    } else {
                                        // Si la 1ère requête a retourné un résultat, on prend l'ID
                                        $resultat = $reqIdMDS->fetch();
                                        $idMDS = $resultat['idMDS'];
                                    }

                                    // On met à jour les informations de la table 'offre'
                                    $upOffre = $bdd->prepare('UPDATE offre SET presentiel = ? WHERE idOffre = ?');
                                    $upOffre->execute(array($distPres, $idOffre));

                                    // On met à jour les informations de la table 'site'
                                    $upSite = $bdd->prepare('UPDATE site SET adresse_postale = ?, code_postal = ? WHERE idSite = ?');
                                    $upSite->execute(array($adressePostale, $code_postal, $idSite));

                                    // On met à jour les informations de la table 'convention_contrat'
                                    $upCC = $bdd->prepare('UPDATE convention_contrat SET dateDeb = ?, dateFin = ?, idMDS = ? WHERE idUtilisateur = ?');
                                    $upCC->execute(array($DateDeb,$DateFin, $idMDS, $id));
                                }
                                else { echo "Les dates saisies sont incohérentes.";}
                            }
                            else{
                                echo "Veuillez accepter un stage sur la page précédente avant de remplir ces informations.";
                            }

                            //on actualise automatiquement la page pour ré-afficher les nouvelles données
                            echo "<script>window.location.replace(\"suivi_stage.php\")</script>";
                        }
                      //include('fonctionality/insert_bdd_suivi_stage.php');
                    ?> 
                </div> <!--fin div de section 1 -->

<!----------------------------Footer------------------------------------------->

        </div> <!-- fin div de page-->
    </main>
    <?php
        include('fonctionality/footer.php');
    ?>
</div> <!-- fin body de page-->