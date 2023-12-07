<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */

include('barre_nav_admin.php');
include('fonctionality/bdd.php');
include('fonctionality/annee+promo.php');
?>

<?php
$id = $_GET['id'];
/* Requete pour récuperer les infos de l'étudiant dans la BDD, on se repère grâce à la variable de session "$_SESSION['user'] */
$req = $bdd->prepare("SELECT * FROM offre JOIN site USING (idSite) JOIN entreprise USING (idEntreprise) WHERE idOffre = ?");
$req->execute(array($id));
$resultat = $req->fetch();

$nomEntreprisePrerempli = $resultat['nomEntreprise'];
$nomSitePrerempli = $resultat['nomSite'];
$titrePrerempli = $resultat['titre'];
$villePrerempli = $resultat['ville'];
$paysPrerempli = $resultat['pays'];
$descriptionPrerempli = $resultat['description'];
$secteurPrerempli = $resultat['secteur'];
$nbPostePrerempli = $resultat['nbPoste'];
$mailContactPrerempli = $resultat['mailContact'];
$representantPrerempli = $resultat['representant'];
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
            <h1 class="mt-4">Vérification de l'offre de stage </h1>
            <br>

            <!----------------------------Section entreprise------------------------------------------->
            <center>
                <div id="confirmationMessage" style="display: none; font-size: 20px; color: red;">
                    <b>L'offre a été modifiée avec succès !</b>
                </div>
                <br>
            </center>
            <div class="card mb-4">      <!--div de section 1 -->
                <div class="card-header">
                    <center>
                        <b>Offre numéro : <?php echo $id; ?>
                            <br>
                            Entreprise : <?php echo $nomEntreprisePrerempli; ?>
                            <br>
                            Titre : <?php echo $titrePrerempli; ?>
                        </b>
                    </center>
                </div>
                <br>

                <p><b><span style="color: red;">*</span></b> : Saisie obligatoire</p>

                <!-- Tableau ajout du stage -->

                <form method ="post" enctype="multipart/form-data" action="Formulaire_entreprise.php?success=true">
                    <div class="card-body"><!--div de tableau 1 -->
                        <div class="form-group">
                            <label><h5> Nom de l'entreprise <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez le nom de l'entreprise" id="Entreprise" name="Entreprise" value="<?php echo $nomEntreprisePrerempli; ?>" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Nom du site :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez le site de l'entreprise où se déroulera le stage" id="ChoiceSite" name="ChoiceSite" value="<?php echo $nomSitePrerempli; ?>">
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Intitulé du poste <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez l'intitulé du poste proposé" id="titre" name="titre" value="<?php echo $titrePrerempli; ?>" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Ville <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez la ville où se déroulera le stage" id="Ville" name="Ville" value="<?php echo $villePrerempli; ?>" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Pays :</h5></label>
                            <input type="text" class="form-control" id="Pays" name="Pays" value="<?php echo $paysPrerempli; ?>">
                        </div>
                        <br>
                        <div>
                            <label>
                                <h5> Missions du stage:</h5>
                            </label>
                            <textarea class="form-control" placeholder="Entrez les missions à effectuer lors du stage" id="Missions" name="Missions" rows="4" style="width: 100%;"><?php echo $descriptionPrerempli; ?></textarea>
                            <small>(facultatif)</small>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Profil recherché :</h5></label>
                            <input type="text" class="form-control" placeholder="Chef de projet, développeur, validation, data management, consultant fonctionnel..." id="profil" name="profil" value="<?php echo $secteurPrerempli; ?>">
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Nombre de stages proposés <b><span style="color: red;">*</span></b> :</h5></label>
                            <input type="number" class="form-control" placeholder="Entrez le nombre de stages que vous proposez" id="nbPoste" name="nbPoste" value="<?php echo $nbPostePrerempli; ?>" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Mail de la personne à contacter <b><span style="color: red;">*</span></b>:</h5></label>
                            <input type="email" class="form-control" placeholder="Entrez l'adresse e-mail de la personne à contacter" id="mailContact" name="mailContact" value="<?php echo $mailContactPrerempli; ?>" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Si connu, représentant au forum stage:</h5></label>
                            <input type="text" class="form-control" placeholder="Entrez le nom et prénom de la personne physique présente au forum stage" id="Representant" name="Representant" value="<?php echo $representantPrerempli; ?>">
                        </div>
                        <div class="card mb-4">      <!--div de section 1 -->
                            <br>
                                <input type="submit" class="btn btn-warning" name="ValidModifStage" value="Valider">
                            <br>
                        </div><!--fin div de tableau 1 -->
                    </div>
                </form>
                <?php
                if (isset($_POST['ValidModifStage'])) {
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