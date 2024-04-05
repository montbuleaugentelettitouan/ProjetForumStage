<!DOCTYPE html>
<?php
include('barre_nav_M1.php');
include('fonctionality/bdd.php');
$req = $bdd->prepare('SELECT * FROM utilisateur WHERE idUtilisateur=?');
$req->execute(array($_SESSION['user']));
$resultat = $req->fetchAll();
foreach ($resultat as $ligne) {
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <style>
        p {
            padding-left : 20px;
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
            width:  50%; /* Adjust width for side-by-side display */
            padding-left:  20px;
            border: 0.1px solid grey;
            display: inline-block;
        }

        input[type=submit] {
            border: 0.5px ;
            border-radius: 5px;
            width: 80px; /* Adjust width for side-by-side display */
            margin-top: 20px;
            margin-bottom: 20px;
            margin-right: 0px;
        }

        .profil-info {
            margin-bottom: 10px;
        }

        .modifier-link {
            color: blue;
            cursor: pointer;
            margin-left: 10px;
        }

        /* New CSS rule for side-by-side elements */
        .modification-container {
            display: flex;
            align-items: center;
        }
        .profil-info form {
            display: flex; /* Use flexbox for horizontal alignment */
            align-items: center; /* Vertically align elements */
        }
        .profil-info form span {  /* Target the "Téléphone:" span */
            margin-left: 5px; /* Add margin-right for spacing */
        }
        .modification-container.no-margin-top {
            margin-top: 0;
        }
        #retour {
            width: 200px; /* Adjust the width as needed */
        }
        #retour2 {
            width: 200px; /* Adjust the width as needed */
        }
    </style>
</head>
<body>
<div id="layoutSidenav_content"> <main>
        <div class="container-fluid px-4"> <h1 class="mt-4"><?php echo $_SESSION['nom']; ?> <?php echo $_SESSION['prenom']; ?></h1>
            <br>
            <div class="card mb-4"> <div class="card-header"> Informations personnelles
                </div> <br>
                <div class="profil-info" style="padding-left:20px">
                    <b style="text-decoration: underline">Mail:</b> <?php echo $_SESSION['mail']; ?>
                    <br>
                    <br>
                    <form id="modifierNumTelForm" action="" method="post">
                        <b style="text-decoration: underline">Téléphone:</b><span id="numTel"><?php echo $ligne['numTel']; ?></span>
                        <span class="modifier-link" id="modifierBtn" onclick="afficherChampModification()">Modifier</span>
                        <div id="champModificationContainer" style="display: none;">
                            <div class="modification-container" style="margin-left:10px; margin-top:10px">
                                <input type="text" name="nouveauNumTel" value="<?php echo $ligne['numTel']; ?>">
                                <button type="submit" class="modifier-link" id="enregistrerBtn" style="border: none; background: none; color: #31316a; cursor: pointer; margin-bottom:10px">Enregistrer</button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <b style="text-decoration: underline">Promo:</b> <?php echo $_SESSION['promo']; ?>
                    <br><br>
                    <b style="text-decoration: underline">N° étudiant:</b> <?php echo $ligne['numEtu']; ?>
                    <br><br>
                        <div>
                            <a href="suivi_stage.php">
                                <input type="submit" class="btn btn-primary" name="retour" id="retour" value="Informations du stage" style="margin-right:5px"></a>
                            <a href="suivi_convention.php">
                                <input type="submit" class="btn btn-warning" name="retour" id="retour2" value="État de la convention" style="margin-left:5px">
                            </a>
                    </div>
                </div>
            </div>
            <?php } ?>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>
<script>
    function afficherChampModification() {
        var champModificationContainer = document.getElementById('champModificationContainer');
        var numTel = document.getElementById('numTel');
        var modifierBtn = document.getElementById('modifierBtn');

        champModificationContainer.style.display = 'block'; // Changed to block for full visibility
        numTel.style.display = 'none'; // Hide the previous phone number
        modifierBtn.style.display = 'none'; // Hide the "Modifier" link

        // Remove margin-top when displayed
        champModificationContainer.classList.remove('no-margin-top');
    }
</script>

<?php
if (isset($_POST['nouveauNumTel'])) {
// Récupérer le nouveau numéro de téléphone
    $nouveauNumTel = $_POST['nouveauNumTel'];

// Mettre à jour le numéro de téléphone dans la base de données
    $updateReq = $bdd->prepare('UPDATE utilisateur SET numTel = ? WHERE idUtilisateur = ?');
    $updateReq->execute(array($nouveauNumTel, $_SESSION['user']));

// Rafraîchir la page pour afficher le numéro de téléphone mis à jour
    echo "<script>window.location.replace(\"profil.php\")</script>";
}
?>
</body>
</html>
