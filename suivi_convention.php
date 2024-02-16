<?php
/**
 * Formulaire de saisie des information complémentaire sur le stage 
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


            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Veuillez remplir les informations concernant la convention de stage.</li>
            </ol>


            <?php
            //requete pour récupérer les données déjà saisie et les afficher dans le formulaire
                $reqTut = $bdd->prepare('SELECT nomTA, prenomTA, idConvention, gratification, format_gratification FROM convention_contrat LEFT JOIN tuteur_academique USING (idTA) WHERE idUtilisateur = ? LIMIT 1');
                $reqTut->execute(array($_SESSION['user']));
                $resultat = $reqTut->fetch();

                $countr1 = $reqTut->rowcount();

                //initialisation des variables pour la récupération des valeurs
                //$nomTuteur = "";
                //$prenomTuteur = "";
                $gratification = "";
                $format = "";

                $stageid = "";

                $selectedformat1 ="";
                $selectedformat2 ="";
                $selectedformat3 ="";
                $selectedformat4 ="";
                $selectedformat5 ="";


                //s'il y a des données déjà renseignées
                if($countr1 != 0){

                    //$nomTuteur = $resultat['nomTA'];
                    //$prenomTuteur = $resultat['prenomTA'];
                    $stageid = $resultat['idConvention'];
                    $gratification = $resultat['gratification'];
                    $format = $resultat['format_gratification'];

                    //on récupère ensuites les valeurs déjà renseigner pour pouvoir les préremplir automatiquement dans le formulaire
                    //on appelera ensuite ces variables dans les input du formulaire pour ajouter l'attribut selected au bon endroit
                    if($format != ""){
                        if($format == "horairebrut"){
                            $selectedformat1 = "selected";
                        }
                        if($format == "horairenet"){
                            $selectedformat2 = "selected";
                        }
                        if($format == "mensuelbrut"){
                            $selectedformat3 = "selected";
                        }
                        if($format == "mensuelnet"){
                            $selectedformat4 = "selected";
                        }
                        if($format == "smc"){
                            $selectedformat5 = "selected";
                        }
                    }

                }

                //récupération du dernier état 
                $reqEtat = $bdd->prepare('SELECT etat_convention FROM convention_contrat WHERE idConvention = ? order by idConvention DESC LIMIT 1');
                $reqEtat->execute(array($stageid));
                $resultat = $reqEtat->fetchAll();

                $countr2 = $reqEtat->rowcount();

                $etat_convention = "";

                $selectedetat1 ="";
                $selectedetat2 ="";
                $selectedetat3 ="";
                $selectedetat4 ="";

                //si il y a déjà des informations de saisies on récupère la valeur et on initialise la variable pour pré-selectionner la valeur

                if($countr2 != 0){

                    foreach ($resultat as $ligne ){
                        $etat_convention = $ligne['etat_convention'];
                        
                    }

                    if($etat_convention != ""){
                        if($etat_convention == "preconventionEnvoyee"){
                            $selectedetat1 = "selected";
                        }
                        if($etat_convention == "preconventionRecue"){
                            $selectedetat2 = "selected";
                        }
                        if($etat_convention == "conventionEditee"){
                            $selectedetat3 = "selected";
                        }
                        if($etat_convention == "conventionEnvoyee"){
                            $selectedetat4 = "selected";
                        }
                    }


                }


            ?>

                <div class="card mb-4"> <!--div de section 1 -->
                <p>* : Saisie obligatoire<?php echo $countr1?></p>
                    <form id="formulaireinscription" method="post">
                        <div class="card-body"> <!--div de tableau 1 -->
                            <label for="Etat_convention"><b>État de la convention de stage * : </b></label>
                                <select  name="Etat_convention">
                                    <option 
                                    <?php echo $selectedetat1?>
                                    value = "preconventionEnvoyee">Pré-convention envoyée</option>
                                    <option 
                                    <?php echo $selectedetat2?>
                                    value = "preconventionRecue">Pré-convention reçue</option>
                                    <option 
                                    <?php echo $selectedetat3?>
                                    value = "conventionEditee">Convention éditée</option>
                                    <option 
                                    <?php echo $selectedetat4?>
                                    value = "conventionEnvoyee">Convention envoyée</option>                                           
                                </select>
                                <!--<br>
						        <input type="text" id="Type_contrat" name="Type_contrat" required>
						        <br>-->
                            <label for="gratification"><b>Gratification (si aucune gratification mettre 0): </b></label>
                                <br>
                                <input type="text" id="gratification" name="gratification" value = "<?php echo $gratification ?>">
                                <select  name="format_gratification">
                                    <option 
                                    <?php echo $selectedformat1?>
                                    value = "horairebrut">Taux horaire EUR Brut</option>
                                    <option 
                                    <?php echo $selectedformat2?>
                                    value = "horairenet">Taux horaire EUR Net</option>
                                    <option 
                                    <?php echo $selectedformat3?>
                                    value = "mensuelbrut">Taux mensuel EUR Brut</option>
                                    <option 
                                    <?php echo $selectedformat4?>
                                    value = "mensuelnet">Taux mensuel EUR Net</option>
                                    <option 
                                    <?php echo $selectedformat5?>
                                    value = "smc">% du SMC</option>                                             
                                </select>
                            <!--
						        <br>
                                <label for="nomtuteur"><b>Nom du Tuteur Académique : </b></label>
                                <br>
                                <input type="text" id="nomtuteur" name="nomtuteur" value = "<?php echo $nomTuteur ?>">
                                <label for="prenomtuteur"><b>Prénom du Tuteur Académique : </b></label>
                                <br>
                                <input type="text" id="prenomtuteur" name="prenomtuteur" value = "<?php echo $prenomTuteur ?>">
                            -->

                                <br>                        </div> <!--fin div de tableau 1 -->
                        <br>
                        <input type="submit" class="btn btn-warning" name="Validersuivi" value="Valider">
                    </form>
                    <?php
                        if (isset($_POST['Validersuivi'])) {
                            //données du formulaire sous forme de variables
                            $id=$_SESSION['user'];
                            $etat = $_POST['Etat_convention'];
                            $gratif = $_POST['gratification'];
                            $format_gratif = $_POST['format_gratification'];
                            $dateactuelle = date("Y-m-d");

                            /*
                            $nomTut = $_POST['nomtuteur'];
                            $nomTut=strtoupper($nomTut);
                            $prenomTut = $_POST['prenomtuteur'];
                            $prenomTut=strtoupper($prenomTut);
                            */

                            $upStage = $bdd->prepare('UPDATE convention_contrat SET gratification = ?, format_gratification = ?, etat_convention = ?, date = ? WHERE idUtilisateur = ?');
                            $upStage->execute(array($gratif, $format_gratif, $etat, $dateactuelle, $id));

                            /*
                            //récupération de l'id stage 
                            $req = $bdd->prepare('SELECT idConvention FROM convention_contrat WHERE idUtilisateur = ? LIMIT 1');
                            $req->execute(array($id));
                            $resultat = $req->fetch();

                            $idStage = "";
                            $idStage = $resultat['idStage'];

                            //insertion d'une ligne dans la table convention pour historiser
                            $upConv = $bdd->prepare('INSERT into convention_contrat (idOffre, etat_convention, date) VALUES (?,?,?)');
                            $upConv->execute(array($idStage,$etat, $dateactuelle));
                            */
                    

                            //on actualise automatiquement la page pour ré-afficher les nouvelles données 
                            echo "<script>window.location.replace(\"suivi_convention.php\")</script>";
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