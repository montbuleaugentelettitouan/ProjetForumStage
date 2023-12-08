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
include('fonctionality/annee+promo.php');
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
                <center>
                    <div id="confirmationMessage" style="display: none; font-size: 20px; color: red;">
                        <b>Votre formulaire a été envoyé avec succès !</b>
                    </div>
                <br>
                </center>
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

                <form method ="post" enctype="multipart/form-data" action="Formulaire_entreprise.php?success=true">
                    <div class="card-body"><!--div de tableau 1 -->
                        <div class="form-group">
                            <label><h5> Nom de l'entreprise <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez le nom de l'entreprise" id="Entreprise" name="Entreprise" value="<?php echo isset($_POST['Entreprise']) ? htmlspecialchars($_POST['Entreprise']) : ''; ?>" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Nom du site :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez le site de l'entreprise où se déroulera le stage" id="ChoiceSite" name="ChoiceSite" value="<?php echo isset($_POST['ChocieSite']) ? htmlspecialchars($_POST['ChoiceSite']) : ''; ?>">
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Intitulé du poste <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez l'intitulé du poste proposé" id="titre" name="titre" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Ville <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez la ville où se déroulera le stage" id="Ville" name="Ville" value="<?php echo isset($_POST['Ville']) ? htmlspecialchars($_POST['Ville']) : ''; ?>" required>
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
                            <input type="text" class="form-control" placeholder="Chef de projet, développeur, validation, data management, consultant fonctionnel..." id="profil" name="profil">
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Nombre de stages proposés <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="number" class="form-control" placeholder="Entrez le nombre de stages que vous proposez" id="nbPoste" name="nbPoste" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Mail de la personne à contacter <b><span style="color: red;">*</span></b>:</h5></label>
                            <input type="email" class="form-control" placeholder="Entrez l'adresse e-mail de la personne à contacter" id="mailContact" name="mailContact" value="<?php echo isset($_POST['mailContact']) ? htmlspecialchars($_POST['mailContact']) : ''; ?>" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Si connu, représentant au forum stage:</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez le nom et prénom de la personne physique présente au forum stage" id="Representant" name="Representant" value="<?php echo isset($_POST['Representant']) ? htmlspecialchars($_POST['Representant']) : ''; ?>">
                        </div>
                        <br>
                        <div>
                            <label><h5> Mail pour envoi de la confirmation <b><span style="color: red;">*</span></b>:</h5></label>
                            <input type="email" class="form-control" placeholder="Entrez votre adresse e-mail pour l'envoi d'un mail de confirmation" id="mailConfirmation" name="mailConfirmation" value="<?php echo isset($_POST['mailConfirmation']) ? htmlspecialchars($_POST['mailConfirmation']) : ''; ?>" required>
                        </div>
                        <br>
                        <div class="card mb-4">      <!--div de section 1 -->
                            <div class="card-header">
                                <div class="form-group">
                                    <!--<i class="far fa-file-pdf"></i> -->
                                    <label for="pdfFile">Déposer un fichier PDF <b><span style="color: red;">*</span></b> :</label>
                                    <input type="file" class="form-control-file" id="pdfFile" name="pdfFile" accept=".pdf" required>
                                </div>
                            </div>
                            <br>
                            <input type="submit" class="btn btn-warning" name="ValidAjoutStage" value="Ajouter">
                            <br>
                        </div><!--fin div de tableau 1 -->
                    </div>
                </form>
                <?php
                if (isset($_POST['ValidAjoutStage'])) {
                    $nomEntreprise = $_POST['Entreprise'];
                    $nomEntreprise= strtoupper($nomEntreprise);

                    $nomSite = $_POST['ChoiceSite'];
                    if ($nomSite == '') {
                        $nomSite = $nomEntreprise;
                    } else {
                        $nomSite = strtoupper($nomSite);
                    }

                    $titre = $_POST['titre'];
                    $titre= ucfirst($titre);

                    $ville = $_POST['Ville'];
                    $ville = ucfirst($ville);

                    $pays = $_POST['Pays'];
                    $pays= strtoupper($pays);

                    $description = $_POST['Missions'];
                    $description = ucfirst($description);

                    $profil = $_POST['profil'];
                    $profil = ucfirst($profil);

                    $nbPoste = $_POST['nbPoste'];

                    $mailContact = $_POST['mailContact'];

                    $representant = $_POST['Representant'];
                    $representant= strtoupper($representant);

                    $destinataire = $_POST['mailConfirmation'];
                    $sujet = "[Forum stage 2024] Confirmation de proposition d'offre de stage";
                    $message = "Votre proposition de stage $titre a bien été prise en compte.";
                    $message = "Bonjour,

                   Nous vous remercions d'avoir soumis votre proposition de stage concernant $titre.
                    
                   Voici les informations que vous avez fournies : 
                   
                   Nom de l'entreprise : $nomEntreprise
                   Nom du site : $nomSite
                   Intitulé du poste : $titre
                   Ville : $ville
                   Pays : $pays
                   Missions du stage : $description
                   Profil recherché : $profil
                   Nombre de stages proposés : $nbPoste
                   Mail de la personne à contacter : $mailContact
                   Représentant au forum stage: $representant
                    
                   Cordialement,
                   L'équipe Forum Stage.

                   (Ceci est un message automatique)";

                    // On rajoute les infos dans la BDD : Entreprise, puis site et enfin l'offre
                    // Entreprise :
                    $idE = '';

                    $recupE = $bdd -> prepare ('SELECT * FROM entreprise WHERE nomEntreprise LIKE ?');
                    $recupE -> execute (array($nomEntreprise));
                    $resultatE = $recupE -> fetch();

                    if ($resultatE != null) {
                        $idE = $resultatE['idEntreprise'];
                    } else {
                        $reqinsertE = $bdd -> prepare ('INSERT INTO entreprise (nomEntreprise) VALUES (?)');
                        $reqinsertE -> execute (array($nomEntreprise));

                        $recupE = $bdd -> prepare ('SELECT idEntreprise FROM entreprise WHERE nomEntreprise LIKE ? ORDER BY idEntreprise DESC');
                        $recupE -> execute (array($nomEntreprise));
                        $resultatE = $recupE -> fetch();

                        $idE = $resultatE['idEntreprise'];
                    }

                    // Site :
                    $idS = '';

                    $recupS = $bdd -> prepare ('SELECT * FROM site WHERE nomSite LIKE ? AND ville LIKE ?');
                    $recupS -> execute (array($nomSite, $ville));
                    $resultatS = $recupS -> fetch();

                    if ($resultatS != null) {
                        $idS = $resultatS['idSite'];
                    } else {
                        $reqinsertS = $bdd -> prepare ('INSERT INTO site (nomSite, ville, pays, idEntreprise) VALUES (?,?,?,?)');
                        $reqinsertS -> execute (array($nomSite, $ville, $pays, $idE));

                        $recupS = $bdd -> prepare ('SELECT idSite FROM site WHERE nomSite LIKE ? AND ville LIKE ? ORDER BY idSite DESC');
                        $recupS -> execute (array($nomSite, $ville));
                        $resultatS = $recupS -> fetch();

                        $idS = $resultatS['idSite'];
                    }

                    $reqinsertS = $bdd -> prepare ('INSERT INTO offre (titre, description, nbPoste, nbPostePourvu, anneeO, secteur, parcours, niveau, mailContact, representant, idSite) values (?,?,?,?,?,?,?,?,?,?,?) ');
                    $reqinsertS -> execute (array($titre, $description, $nbPoste, 0, $annee, $profil, 'GPhy', 'M1', $mailContact, $representant, $idS));
                    $resultatS = $reqinsertS -> fetch();

                    //On récupère son id

                    $recupE = $bdd->prepare('SELECT * FROM offre WHERE titre = ? ORDER BY idOffre DESC');
                    $recupE->execute(array($titre));
                    $idOffre = $recupE->fetchColumn();

                    // Check if a file has been uploaded
                    if (isset($_FILES['pdfFile'])) {

                        // Get the details of the uploaded file
                        $file_name = $_FILES['pdfFile']['name'];
                        $file_tmp = $_FILES['pdfFile']['tmp_name'];
                        $file_size = $_FILES['pdfFile']['size'];
                        $file_error = $_FILES['pdfFile']['error'];

                        // Check if there were any errors uploading the file
                        $error = "";
                        if ($file_error === 0) {

                            // Make sure the uploaded file is a PDF
                            $file_ext = explode('.', $file_name);
                            $file_ext = strtolower(end($file_ext));

                            if ($file_ext === 'pdf') {

                                // Generate a unique file name
                                // Id Offre _ Nom entreprise _ intitulé
                                // Génération du nom de fichier unique en remplaçant les barres obliques
                                $unique_file_name = $idOffre . '_' . str_replace('/', '_', $nomEntreprise) . '_' . str_replace('/', '_', $titre) . '_' . str_replace('/', '_', $ville) . '.' .$file_ext;

                                // Define the destination path for the uploaded file
                                $destination_path = 'uploads/' . $unique_file_name;

                                // Move the uploaded file to the destination path
                                if (move_uploaded_file($file_tmp, $destination_path)) {
                                    echo "";
                                } else {
                                    $error = "Il y a eu une erreur lors du déplacement du fichier.";
                                }

                            } else {
                                $error = "Seuls les fichiers PDF sont acceptés.";
                            }

                        } else {
                            $error = "Il y a eu une erreur lors du téléchargement du fichier.";
                        }

                    } else {
                        $error = "Aucun fichier n'a été téléchargé.";
                    }
                    if ($error != ""){
                        mail("thibaultniggel@gmail.com", "Bug PDF", $error, "From:forumStageGphy@univ-poitiers.fr");
                    }
                    // Fonction mail : composition : mail(destinataire, l'objet du mail, le message du mail, l'adresse qui envoie le mail) (c'est le serveur qui s'occupe du reste)
                    mail($destinataire, $sujet, $message, "From:forumStageGphy@univ-poitiers.fr");
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
<script>
    // Récupérer le paramètre GET de l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');

    // Vérifier si le paramètre success est présent et égal à true
    if (success === 'true') {
        // Afficher la div de confirmation
        const confirmationDiv = document.getElementById('confirmationMessage');
        if (confirmationDiv) {
            confirmationDiv.style.display = 'block';
        }
    }
</script>