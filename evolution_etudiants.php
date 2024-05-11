<?php
include('barre_nav_admin.php');
include('fonctionality/bdd.php');
include('fonctionality/annee+promo.php');

if (isset($_POST['submit']) && isset($_POST['etudiants'])) {
    // Vérifier si la confirmation a été donnée
    if ($_POST['confirmation'] === 'oui') {
        $etudiants = $_POST['etudiants'];

        // Préparer les paramètres nommés pour les ID d'étudiants
        $params = [];
        foreach ($etudiants as $key => $etudiant) {
            $params[":etudiant$key"] = $etudiant;
        }

        // Construire la requête SQL avec les paramètres nommés
        $placeholders = implode(',', array_keys($params));
        $sql = "UPDATE utilisateur SET typeAnnee = 'M2' WHERE idUtilisateur IN ($placeholders)";

        // Exécuter la requête SQL UPDATE avec les paramètres nommés
        $req = $bdd->prepare($sql);
        $req->execute($params);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évolution des étudiants M1 en M2</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Évolution des étudiants M1 en M2</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"></li>
            </ol>
            <div class="card mb-4">
                <div class="card-header">
                    <!-- Bouton de bas de page -->
                    <button onclick="bottomFunction()" id="scrollBottomBtn" class="btn btn-secondary" title="Aller en bas de la page">Bas de la page</button>
                    <script>
                        // Fonction pour aller en bas de la page
                        function bottomFunction() {
                            window.scrollTo(0, document.body.scrollHeight);
                        }
                    </script>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="table-responsive">
                            <table id="datatablesSimple" class="table table-striped table-bordered table-sm">
                                <thead>
                                <tr>
                                    <th>Nom Prénom</th>
                                    <th>Sélection pour passage en M2</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $req = $bdd->prepare("SELECT idUtilisateur, nom, prenom FROM utilisateur WHERE statut='etudiant' AND typeAnnee = 'M1' AND promo = ? AND parcours = ? ORDER BY nom");
                                $req->execute(array($promo, $parcours));
                                $resultat = $req->fetchAll();
                                foreach ($resultat as $ligne) {
                                    ?>
                                    <tr>
                                        <td><?php echo $ligne['nom'] . " " . $ligne['prenom']; ?></td>
                                        <td><input type="checkbox" name="etudiants[]" value="<?php echo $ligne['idUtilisateur']; ?>"></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="confirmation" id="confirmation" value="">
                        <button type="submit" name="submit" class="btn btn-primary" onclick="return confirmSubmit()">Valider la sélection</button>
                        <script>
                            function confirmSubmit() {
                                // Récupère les noms des étudiants cochés
                                var checkedStudentsNames = [];
                                var checkedStudents = document.querySelectorAll('input[name="etudiants[]"]:checked');
                                checkedStudents.forEach(function(student) {
                                    checkedStudentsNames.push(student.parentNode.parentNode.querySelector('td:first-child').innerText);
                                });
                                var namesList = checkedStudentsNames.join(", ");

                                // Affiche une boîte de dialogue de confirmation avec les noms des étudiants cochés
                                var confirmation = confirm("Vous êtes sur le point de passer les étudiants suivants en M2 :\n" + namesList + "\nÊtes-vous sûr de vouloir continuer ?");

                                // Mettre à jour le champ caché avec la confirmation
                                if (confirmation) {
                                    document.getElementById('confirmation').value = 'oui';
                                } else {
                                    document.getElementById('confirmation').value = 'non';
                                }

                                // Retourne la valeur de confirmation pour soumettre ou non le formulaire
                                return confirmation;
                            }
                        </script>
                    </form>
                    <!-- Bouton de haut de page -->
                    <br>
                    <button onclick="topFunction()" id="scrollTopBtn" class="btn btn-secondary" title="Revenir en haut de la page">Haut de la page</button>
                    <script>
                        // Fonction pour revenir au haut de la page
                        function topFunction() {
                            document.body.scrollTop = 0;
                            document.documentElement.scrollTop = 0;
                        }
                    </script>
                </div>
            </div>
        </div>
    </main>
    <?php include('fonctionality/footer.php'); ?>
</div>
</body>
</html>
