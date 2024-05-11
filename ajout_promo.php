<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */

include('fonctionality/bdd.php');
require 'vendor/autoload.php';
?>

<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Ajout d'une promo</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header"><h3 class="text-center font-weight-light my-4">Ajout d'une promotion</h3></div>
                            <div class="card-body">
                                <form method="post" action ="#" enctype="multipart/form-data">
                                    <div class="row mb-3">
                                        <div>
                                            <p> Année de la promotion</p>
                                            <select name="annee">
                                                <?php
                                                for ($annees = 1974; $annees <= 2974; $annees++) {
                                                    echo "<option value='$annees'>$annees</option>";
                                                }
                                                ?>
                                            </select>
                                            <br>
                                        </div>
                                        <div>
                                            <br>
                                            <p> Parcours de la promotion</p>
                                            <input type = "radio" id ="gphy" name = "parcours" value = "GPhy" checked>
                                            <label for = "parcours">GPhy</label>
                                            <br>
                                            <input type = "radio" id ="gcell" name = "parcours" value = "GCell">
                                            <label for = "parcours">GCell</label>
                                            <br>
                                            <input type = "radio" id ="ecmps" name = "parcours" value = "ECMPS">
                                            <label for = "parcours">ECMPS</label>
                                            <br>
                                        </div>
                                        <br>
                                        <div class="row mb-3">
                                            <br>
                                            <div class="col-md-6">
                                                <br>
                                                <label for="fichier_excel">Fichier Excel</label>
                                                <input class="form-control" id="fichier_excel" name="fichier_excel" type="file" accept=".xls,.xlsx" required />
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="mt-4 mb-0">
                                <div class="d-grid">
                                    <input type="submit" class ="btn btn-primary btn-block" name="AjoutPromo" value ="Ajouter la promo">
                                </div>
                            </div>
                            </form>
                            <?php
                            use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

                            if (isset($_POST['AjoutPromo'])) {
                                $type_annee = 'M1';
                                $statut = 'etudiant';
                                $parcours = $_POST['parcours'];
                                $annee = $_POST['annee'];
                                $fichier_excel = $_FILES['fichier_excel']['tmp_name'];

                                // Charger le fichier Excel
                                $reader = new Xlsx();
                                $excel = $reader->load($fichier_excel);
                                $feuille = $excel->getActiveSheet();

                                // Parcourir les lignes du fichier Excel
                                foreach ($feuille->getRowIterator() as $ligne) {
                                    // Ignorer la première ligne (en-têtes)
                                    if ($ligne->getRowIndex() == 1) {
                                        continue;
                                    }

                                    // Récupérer les données de la ligne
                                    $nom = $feuille->getCell('A' . $ligne->getRowIndex())->getValue();
                                    $nom=strtoupper($nom);
                                    $prenom = $feuille->getCell('B' . $ligne->getRowIndex())->getValue();
                                    $prenom=strtoupper($prenom);
                                    $mail_etudiant = $feuille->getCell('C' . $ligne->getRowIndex())->getValue();
                                    $numero_etudiant = $feuille->getCell('D' . $ligne->getRowIndex())->getValue();
                                    $mdp = md5($numero_etudiant);
                                    $idEtu = $feuille->getCell('E' . $ligne->getRowIndex())->getValue();

                                    // Insérer l'étudiant dans la base de données
                                    $requete = $bdd->prepare("INSERT INTO utilisateur (idUtilisateur, statut, prenom, nom, email, password, numEtu, parcours, typeAnnee, promo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                    $requete->execute([$idEtu, $statut, $prenom, $nom, $mail_etudiant, $mdp, $numero_etudiant, $parcours, $type_annee, $annee]);
                                }

                                // Message de succès
                                echo "Les étudiants ont été ajoutés avec succès.";
                            }
                            ?>
                        </div>
                        <br>
                        <div class="card-footer text-center py-3">
                            <!-- <div class="small"><a href="dashboardADMIN.php">Retour à l'accueil</a></div> -->
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </main>
</div>
<div id="layoutAuthentication_footer">
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; GPhy 2024</div>
                <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
</body>
</html>