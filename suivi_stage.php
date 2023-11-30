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
                            $req = $bdd->prepare('SELECT nomEntreprise, nomSite, titre, description FROM convention_contrat JOIN offre on (idOffre) JOIN site on (idSite) JOIN entreprise on (idEntreprise) WHERE convention_contrat.idUtilisateur = ?');
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
                $req = $bdd->prepare('SELECT nomEntreprise, nomSite, titre, nomMDS, prenomMDS, numMDS, emailMDS, type_contrat, description, ville, pays, presentiel, code_postal, secteur, dateDeb, dateFin, adresse_postale  FROM convention_contrat JOIN offre on convention_contrat.idOffre = offre.idOffre JOIN site on offre.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise JOIN maitre_de_stage ON site.idMDS = maitre_de_stage.idMDS WHERE convention_contrat.idUtilisateur = ? LIMIT 1');
                $req->execute(array($_SESSION['user']));
                $resultat = $req->fetchAll();

                $countr = $req->rowcount();

                //on initialise toutes les variables dans le cas où il n'y aucune données déja renseignées
                $nomTuteur = "";
                $prenomTuteur = "";
                $numTuteur = "";
                $emailTuteur = "";
                $typeContrat = "";
                $ville = "";
                $pays = "";
                $selectedContratstage = "";
                $selectedContratapp = "";
                $selectedContratpro = "";
                $nomEntreprise = "";
                $nomSite ="";
                //$ville_stage = "";
                $selectedPres = "";
                $selectedDist = "";
                $distPres = "";
                $code_postal="";
                $secteur = "";
                $adressePostale = "";
                //récupération de la date actuelle au cas où aucune date n'est rentré en base 
                $DateDeb = date('Y-m-d');
                $DateFin = date('Y-m-d');
                

                if($countr != 0){

                    //récupération des valeurs lignes par lignes
                    foreach ($resultat as $ligne ){
                    $nomTuteur = $ligne['nomTuteur'];
                    $prenomTuteur = $ligne['prenomTuteur'];
                    $numTuteur = $ligne['numTuteur'];
                    $emailTuteur = $ligne['emailTuteur'];
                    $typeContrat = $ligne['type_contrat'];
                    $nomEntreprise = $ligne['nomEntreprise'];
                    $nomSite = $ligne['nomSite'];
                    $ville = $ligne['ville'];
                    $pays = $ligne['pays'];
                    //$ville_stage =  $ligne['ville_stage'];
                    $distPres =  $ligne['presentiel'];
                    $code_postal= $ligne['code_postal'];
                    $secteur =  $ligne['secteur'];
                    $DateDeb = $ligne['dateDeb'];
                    $DateFin = $ligne['dateFin'];
                    $adressePostale = $ligne['adresse_postale'];

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
                <p>* : Saisie obligatoire</p>
                    <form id="formulaireinscription" method="post">
                        <div class="card-body"> <!--div de tableau 1 -->
                            <label for="NomTuteur"><b>Nom du tuteur* : </b></label>
                                <br>
						        <input type="text" id="NomTuteur" name="NomTuteur" value= "<?php echo($nomTuteur)?>" required>
						        <br>
                            <label for="PrenomTuteur"><b>Prénom du tuteur* : </b></label>
						        <br>
						        <input type="text" id="PrenomTuteur" name="PrenomTuteur" value= "<?php echo($prenomTuteur)?>" required>
						        <br>
                            <label for="NumTuteur"><b>Numéro de téléphone du tuteur : </b></label>
                                <br>
						        <!--<input type="tel" id="NumTuteur" name="NumTuteur" pattern="[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}|[0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}|[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{2}">-->
                                <input type="text" id="NumTuteur" name="NumTuteur" value= "<?php echo($numTuteur)?>">
                                <br>
                            <label for="emailTuteur"><b>Adresse mail du tuteur* : </b></label>
                                <br>
						        <input type="email" id="emailTuteur" name="emailTuteur" value= "<?php echo($emailTuteur)?>" required>
						        <br>
                            <label for="nomEntreprise"><b>Nom de l'entreprise* : </b></label>
						        <br>
                                <select name="nomEntreprise"  id="nomEntreprise" required>
                                <option value="">Sélectionnez une entreprise</option>
                                    <?php
                                        $SelectEnt = $bdd->query('SELECT * FROM entreprise ORDER BY nomEntreprise ASC');
                                        while ($donnees = $SelectEnt->fetch()) {
                                    ?>
                                    <option 
                                    <?php if($donnees['nomEntreprise'] == $nomEntreprise){ echo $selectedEntreprise ;}?>
                                    value="<?php echo $donnees['idEntreprise']; ?>">
                                        <?php echo $donnees['nomEntreprise']; ?>
                                    </option>
                                    <?php }; ?>
                                </select>
                                <br>
                            <label for="nomSite"><b>Nom du site* : </b></label>
						        <br>
                                <select name="nomSite"  id="nomSite" required>
                                <option value="">Sélectionnez un site</option>
                                    <?php
                                        $SelectSite = $bdd->query('SELECT nomSite FROM site ORDER BY nomSite ASC');
                                        while ($donnees = $SelectSite->fetch()) {
                                    ?>
                                    <option
                                    <?php if($donnees['nomSite'] == $nomSite){ echo $selectedSite ;}?>
                                    value="<?php echo $donnees['nomSite']; ?>">
                                        <?php echo $donnees['nomSite']; ?>
                                    </option>
                                    <?php }; ?>
                                </select>
                                <br>
                            <label for="Ville"><b>Ville* : </b></label>
						        <br>
						        <input type="text" id="Ville" name="Ville" value = "<?php echo $ville_stage ?>" required>
						        <br>
                            <label for="Postal"><b>Code Postal* : </b></label>
                                <br>
                                <input type="text" id="Postal" name="Postal" value = "<?php echo $code_postal ?>"required>
                                <br>
                            <label for="Pays"><b>Pays* : </b></label>
						        <br>
						        <input type="text" id="Pays" name="Pays" value = "<?php echo $pays ?>" required>
						        <br>
                            <label for="Adresse_postale"><b>Adresse postale * (adresse correspondant au lieu de stage) : </b></label>
						        <br>
						        <input type="text" id="Adresse_postale" name="Adresse_postale" value = "<?php echo $adressePostale ?>" required>
						        <br>
                            <label for="dist_pres"><b>Distanciel ou Présentiel* : </b></label>
                            <select  name="dist_pres">
                                <option
                                    <?php echo $selectedPres?>
                                        value = "presentiel">Présentiel</option>
                                <option
                                    <?php echo $selectedDist?>
                                        value = "distanciel">Distanciel</option>
                            </select>
                            <label for="Type_contrat"><b>Type de contrat (stage, alternance ...)* : </b></label>
                                <select  name="Type_contrat">
                                    <option 
                                    <?php echo $selectedContratstage?>
                                    value = "stage">Stage</option>
                                    <option 
                                    <?php echo $selectedContratapp?>
                                    value = "apprentissage">Apprentissage</option>
                                    <option 
                                    <?php echo $selectedContratpro?>
                                    value = "pro">Pro</option>                                        
                                </select>
                            <label for="Secteur"><b>Secteur d'activité : </b></label>
                                <br>
                                <input type="text" id="secteur" name="secteur" value = "<?php echo $secteur ?>">
                                <br>
                            <label for="DateDeb"><b>Date de début de stage* : </b></label>
						        <br>
						        <input type="date" id="DateDeb" name="DateDeb" min= "2020-31-12" max= "2050-31-12" value = "<?php echo date('Y-m-d', strtotime($DateDeb)); ?>" required>
						        <br>
                            <label for="DateFin"><b>Date de fin de stage* : </b></label>
                                <br>
						        <input type="date" id="DateFin" name="DateFin" min= "2020-31-12" max= "2050-31-12" value = "<?php echo $DateFin ?>" required>
						        <br>
                            <label for="annee_stage"><b>Année du stage* : </b></label>
						        <br>
						        <input type="number" id="annee_stage" name="annee_stage" min= "2020" max= "2090" value= "<?php echo $annee; ?>" required>
						        <br>
                        </div> <!--fin div de tableau 1 -->
                        <br>
                        <input type="submit" class="btn btn-warning" name="Validersuivi" value="Valider">
                    </form>
                    <?php
                        if (isset($_POST['Validersuivi'])) {
                            //données du formulaire sous forme de variables
                            $id=$_SESSION['user'];
                    
                            $nomTut = $_POST['NomTuteur'];
                            $nomTut=strtoupper($nomTut);
                    
                            $prenomTut = $_POST['PrenomTuteur'];
                            $prenomTut=strtoupper($prenomTut);
                    
                            $numTut = $_POST['NumTuteur'];
                            $mailTut = $_POST['emailTuteur'];
                    
                            $ville = $_POST['Ville'];
                            $ville=ucfirst($ville);

                            $code_postal = $_POST['Postal'];

                            $pays = $_POST['Pays'];
                            $pays=strtoupper($pays);
                    
                            $type = $_POST['Type_contrat'];
                            $type=strtolower($type);

                            $distPres = $_POST['dist_pres'];
                            $distPres=strtolower($distPres);

                            $secteur = $_POST['secteur'];
                            $secteur=ucfirst($secteur);
                    
                            $DateDeb = $_POST['DateDeb'];
                            $DateFin = $_POST['DateFin'];
                            $annee = $_POST['annee_stage'];

                            $adressePostale = $_POST['Adresse_postale'];

                            $verifStage = $bdd->prepare('SELECT * FROM convention_contrat WHERE idUtilisateur = ?');
                            $verifStage->execute(array($id));
                            $resultVerif = $verifStage->fetch();
                            $count = $verifStage->rowcount();
                    
                            if ($count !=0) {
                                if($DateDeb < $DateFin){
                                    //A REFAIRE §!!!§§§§§D§§D§D§1§1§1§!!!
                                    $upStage = $bddd->prepare('UPDATE maitre_de_stage SET nomMDS = ?, prenomMDS = ?, numMDS = ?, emailMDS = ?, type_contrat = ?, dateDeb = ?, dateFin = ?, annee_stage = ?, ville_stage = ? , code_postal = ?, presentiel = ? , secteur = ? , adresse_postale = ?  WHERE idUtilisateur = ?');
                                    //$upStage = $bdd->prepare('UPDATE stage SET numTuteur = ? WHERE idUtilisateur = ?');
                                    $upStage->execute(array($nomTut,$prenomTut,$numTut,$mailTut,$type,$DateDeb,$DateFin,$annee,$ville, $code_postal, $distPres, $secteur, $adressePostale, $id));
                                    //$upStage->execute(array($numTut,$id));
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