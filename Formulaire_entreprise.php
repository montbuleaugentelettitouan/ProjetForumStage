<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */

include('barre_nav_entreprise.php');
include('fonctionality/bdd.php');
?>
<style>
    p {
        padding-left : 20px;
    }
</style>

<body>
<div id="layoutSidenav_content"> <!-- body de la page -->
    <main>
        <div class="container-fluid px-4"> <!-- div de page -->
            <h1 class="mt-4">Proposition de stage </h1>
            <br>

            <!----------------------------Section entreprise------------------------------------------->
            <div class="card mb-4">      <!--div de section 1 -->
                <div class="card-header">
                    <center>
                        <b>Merci de renseigner dans le formulaire ci-après les informations essentielles concernant le stage que vous proposez.
                            <br>
                            Merci de déposer un PDF de cette proposition de stage qui sera mis à disposition des étudiants.</b>
                    </center>
                </div>
                <br>

                <p><b><span style="color: red;">*</span></b> : Saisie obligatoire</p>

                <!-- Tableau ajout du stage -->

                <form method ="post">
                    <div class="card-body"><!--div de tableau 1 -->
                        <div class="form-group">
                            <label><h5> Nom de l'entreprise <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez le nom de l'entreprise" id="Entreprise" name="Entreprise" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Nom du site :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez le site de l'entreprise où se déroulera le stage" id="ChoiceSite" name="ChoiceSite">
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Intitulé du poste <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez l'intitulé du poste proposé" id="titre" name="titre" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Ville <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez la ville où se déroulera le stage" id="Ville" name="Ville" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Pays :</h5></label>
                            <input type="text" class="form-control" id="Pays" name="Pays" value="FRANCE">
                        </div>
                        <br>
                        <div>
                            <label>
                                <h5> Missions du stage:</h5>
                            </label>
                            <textarea class="form-control" placeholder="Entrez les missions à effectuer lors du stage" id="Missions" name="Missions" rows="4" style="width: 100%;"></textarea>
                            <small>(facultatif)</small>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Profil recherché :</h5></label>
                            <input type="text" class="form-control" placeholder="..., ..., ..., ..." id="mailContact" name="mailContact">
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Nombre de stages proposés <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="number" class="form-control" placeholder="Entrez le nombre de stages que vous proposez" id="nbPoste" name="nbPoste" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Mail de la personne à contacter <b><span style="color: red;">*</span></b>:</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez l'adresse e-mail de la personne à contacter" id="mailContact" name="mailContact" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Si connu, représentant au forum stage:</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez le nom et prénom de la personne physique présente au forum stage" id="Representant" name="Representant">
                        </div>
                        <br>
                        <div>
                            <label><h5> Mail pour envoi de la confirmation <b><span style="color: red;">*</span></b>:</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez votre adresse e-mail pour l'envoi d'un mail de confirmation" id="mailConfirmation" name="mailConfirmation" required>
                        </div>
                        <br>
                        <div class="card mb-4">      <!--div de section 1 -->
                            <div class="card-header">

                                <form method="post" enctype="multipart/form-data">


                                    <div class="form-group">
                                        <i class="far fa-file-pdf"></i>
                                        <label for="pdfFile">Déposer un fichier PDF <b><span style="color: red;">*</span></b> :</label>
                                        <input type="file" class="form-control-file" id="pdfFile" name="pdfFile" accept=".pdf" required>
                                    </div>
                                </form>
                            </div>
                            <br>
                            <input type="submit" class="btn btn-warning" name="ValidAjoutStage" value="Ajouter">
                            <br>
                        </div><!--fin div de tableau 1 -->
                </form>
                <?php
                if (isset($_POST['ValidAjoutStage'])) {
                    $nomEntreprise = $_POST['Entreprise'];
                    $nomEntreprise= strtoupper($nomEntreprise);

                    $nomSite = $_POST['ChoiceSite'];
                    $nomSite= strtoupper($nomSite);

                    $titre = $_POST['titre'];
                    $titre= ucfirst($titre);

                    $ville = $_POST['Ville'];
                    $ville = ucfirst($ville);

                    $pays = $_POST['Pays'];
                    $pays= strtoupper($pays);

                    $description = $_POST['Missions'];
                    $description = ucfirst($description);

                    $nbPoste = $_POST['nbPoste'];

                    $mailContact = $_POST['mailContact'];

                    $representant = $_POST['Representant'];
                    $representant= strtoupper($representant);

                    $destinataire = $_POST['mailConfirmation'];
                    $sujet = "[Forum stage 2024] Confirmation de proposition d'offre de stage";
                    $message = "Votre proposition de stage $titre a bien été prise en compte.";

                    $pdf = $_FILES['pdfFile'];

                    //test des variables
                    /*echo $nomEntreprise;
                    echo $nomSite;
                    echo $ville;
                    echo $pays;
                    echo $description;
                    echo $competences;
                    echo $nbPoste;
                    echo $mailContact;
                    echo $Representant;*/

                    //On rajoute l'offre dans la BDD

                    $reqinsertS = $bdd -> prepare ('INSERT INTO offre (titre, description, nbPoste, mailContact, representant) values (?,?,?,?,?) ');
                    $reqinsertS -> execute (array($titre, $description, $nbPoste, $mailContact, $representant));
                    $resultatS = $reqinsertS -> fetch();

                    //On récupère son id

                    $recupE = $bdd->prepare('SELECT * FROM offre WHERE description = ?');
                    $recupE->execute(array($description));
                    $resultRecup = $recupE->fetch();

                    $idOffre = !empty($resultRecup['idOffre']) ? $resultRecup['idOffre'] : NULL ;

                    /* A REFAIRE

                    //on rajoute dans la bdd et on récupère l'id

                    $reqinsertE = $bdd -> prepare ('INSERT INTO entreprise (nomEntreprise) VALUES (?)');
                    $reqinsertE -> execute (array($nomEntreprise));
                    $resultatE = $reqinsertE-> fetch();

                    //on récupère l'identifiant du stage qui vient d'être ajouté

                    $recupE = $bdd->prepare('SELECT * FROM entreprise WHERE nomEntreprise = ?');
                    $recupE->execute(array($nomEntreprise));
                    $resultRecup = $recupE->fetch();

                    $idEnt = !empty($resultRecup['idEntreprise']) ? $resultRecup['idEntreprise'] : NULL ;
                    //on vérifie si le site existe déjà
                    $verifS = $bdd ->prepare('SELECT * from site WHERE nomSite =? and ville = ? and idEntreprise = ? ');
                    $verifS->execute(array($Site,$ville,$idEnt));
                    $resultatSite = $verifS ->fetch();
                    $countSite = $verifS->rowcount();
                    */

                    // Fonction mail : composition : mail(destinataire, l'objet du mail, le message du mail, l'adresse qui envoie le mail) (cest le serveur qui s'occupe du reste)
                    echo $destinataire;
                    mail($destinataire, $sujet, $message, "From:forumStageGphy@univ-poitiers.fr");

                    echo "Votre offre de stage a bien été prise en compte.";
                }
                ?>
            </div><!--fin div de section 1 -->
            <!----------------------------Footer------------------------------------------->
        </div><!--fin div de page -->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!--fin body de la page -->
</body>
</html>