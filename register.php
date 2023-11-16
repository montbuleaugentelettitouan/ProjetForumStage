<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */

include('fonctionality/bdd.php');
?>

<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Register - SB Admin</title>
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
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Création de compte</h3></div>
                                    <div class="card-body">
                                        <form method="post" action ="#">
                                            <div class="row mb-3">
                                                <div>
                                                    <p> Le compte créé sera : </p>
                                                    <input type = "radio" id ="etu" name = "account" value = "etu">
                                                    <label for = "account">un étudiant</label>
                                                    <br>
                                                    <input type = "radio" id ="admin" name = "account" value = "admin">
                                                    <label for = "account">un administrateur</label>
                                                </div>
                                                <div>
                                                    <br>
                                                    <p> Le parcours de l'étudiant : </p>
                                                    <input type = "radio" id ="gphy" name = "parcours" value = "gphy">
                                                    <label for = "account">GPhy</label>
                                                    <br>
                                                    <input type = "radio" id ="gcell" name = "parcours" value = "gcell">
                                                    <label for = "account">GCell</label>
                                                </div>
                                                <div class="col-md-6">
                                                <br>
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="inputId" name="inputId" type="text" placeholder="Enter your id" required/>
                                                        <label for="inputId">Identifiant</label>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="inputFirstName" name="inputFirstName" type="text" placeholder="Enter your first name" required/>
                                                        <label for="inputFirstName">Prenom</label>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input class="form-control" id="inputLastName" name="inputLastName" type="text" placeholder="Enter your last name" required/>
                                                        <label for="inputLastName">Nom</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputEmail" name="inputEmail" type="email" placeholder="name@example.com" required/>
                                                <label for="inputEmail">Adresse email</label>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="inputPassword" name="inputPassword" type="password" placeholder="Create a password" required/>
                                                        <label for="inputPassword">Mot de passe</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="inputPasswordConfirm" name="inputPasswordConfirm" type="password" placeholder="Confirm password" required/>
                                                        <label for="inputPasswordConfirm">Confirmer le mot de passe</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4 mb-0">
                                              <div class="d-grid">
                                                <input type="submit" class ="btn btn-primary btn-block" name="CreaCompte" value ="Création de compte">
                                              </div>
                                            </div>
                                        </form>
                                        <?php
                                          if (isset($_POST['CreaCompte'])) {

                                            $statut = $_POST['account'];

                                            $parcours = $_POST['parcours'];

                                            $prenom = $_POST['inputFirstName'];
                                            $prenom=strtoupper($prenom);

                                            $nom = $_POST['inputLastName'];
                                            $nom=strtoupper($nom);

                                            $email = $_POST['inputEmail'];

                                            $mdp1 = md5($_POST['inputPassword']);
                                            $mdp2 = md5($_POST['inputPasswordConfirm']);

                                            $idU = $_POST['inputId'];
                                            
                                            if ($mdp1 == $mdp2){                                             
                                              
                                              $mdp = $mdp1;

                                            }
                                            else {
                                              echo "Le mot de passe est incorrect";
                                            }

                                            if ($statut == "etu") {
                                              $statut = "etudiant";
                                            }
                                            else {
                                              $statut = "administrateur";

                                            }

                                              if ($parcours == "gphy") {
                                                  $parcours = "GPhy";
                                              }
                                              else {
                                                  $parcours = "GCell";

                                              }
                                            
                                            $verifAccount = $bdd->prepare('SELECT * FROM utilisateur WHERE idUtilisateur = ? and statut = ? and prenom = ? and nom = ? and email = ? and password = ?');
                                            $verifAccount->execute(array($idU,$statut,$prenom,$nom,$email,$mdp));
                                            $reqAccount = $verifAccount->fetch();
                                            $commpteU = $verifAccount->rowCount();

                                            if ($commpteU != 0) {
                                              echo "Cet utilisateur existe déja";
                                            }
                                            else {
                                              $InsertU = $bdd->prepare("INSERT INTO utilisateur (idUtilisateur, statut, prenom, nom, email, password, parcours) VALUES (?,?,?,?,?,?,?)");
                                              $InsertU->execute(array($idU,$statut,$prenom,$nom,$email,$mdp,$parcours));
                                              echo "L'utilisateur a été ajouté avec succès";
                                            }
                                          }
                                        ?>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="fonctionality/login.php">Déjà un compte ? Se connecter</a></div>
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
                            <div class="text-muted">Copyright &copy; Your Website 2021</div>
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