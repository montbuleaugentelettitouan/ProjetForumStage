<?php
include('fonctionality/bdd.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Password Reset - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Password Recovery</h3></div>
                                    <div class="card-body">
                                        <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password. </div>
                                        <form method="post">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputEmail" name="inputEmail" type="email" placeholder="name@example.com" />
                                                <label for="inputEmail">University Email address </label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="index.php">Return to login</a>
                                                <!-- <a class="btn btn-primary">Reset Password</a> -->
                                                <input type= "submit" class="btn btn-primary" name="Reset_Password" value="Reset Password">

                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="register.php">Need an account? Sign up!</a></div>
                                    </div>
                                  
                                </div>

                                <?php
                                $email = '';
                                $email = $_POST["inputEmail"];
                                if (isset($_POST['Reset_Password'])) {
                                    $req = $bdd->prepare("select * from utilisateur where email = ?");
                                    $req->execute(array($email));
                                    $resultat = $req->fetch();

                                    
                                        if ($resultat['idUtilisateur'] == '' ) {
                                        echo "Veuillez renseigner une adresse mail valide";
                                        }
                                        else {
                                            
                                            $req = $bdd->prepare("update utilisateur set utilisateur.firstConnection = true where email = ?");
                                            $req->execute(array($email));

                                            function getRandomStr($n) { 
                                                // Stockez toutes les lettres possibles dans une chaîne.
                                                $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
                                                $randomStr = ''; 
                                                
                                                // Générez un index aléatoire de 0 à la longueur de la chaîne -1.
                                                for ($i = 0; $i < $n; $i++) { 
                                                    $index = rand(0, strlen($str) - 1); 
                                                    $randomStr .= $str[$index]; 
                                                } 
                                                return $randomStr; 
                                            }
                                            $n = 12;
                                        $nouveau_mdp = getRandomStr($n);

                                                $message = "Bonjour,\n";
                                                $message .= "		\n";
                                                $message .= "Votre nouveau mot de passe pour la plateforme Gphy Forum est : ";
                                                $message .= "$nouveau_mdp ";
                                                $message .= "		\n";
                                                $message .= "Veuillez changer votre mot de passe lors de votre prochaine connection\n";
                                                $message .= "Veuillez ne pas repondre\n";
                                                $message .= "		\n";
                                                $message .= "Assistance Gphy Forum\n";
                                        mail($email, "Changement de mot de passe", $message, "From:AsistantceForumStageGphy@univ-poitiers.fr");
                                    
                                    $req = $bdd->prepare("update utilisateur set utilisateur.password = md5(?) where idUtilisateur = ?");
                                    $req->execute(array($nouveau_mdp, $resultat['idUtilisateur']));

                                    ?>
                                      <script>
                                        window.location.href = 'index.php';
                                      </script>

                                      <?php
                                        }
                                      }                                
                                ?>
                            </div>
                           
                        </div>
                    </div>
                </main>
            </div>
                    <?php
                      include('fonctionality/footer.php');
                    ?>
            </div>
    </body>
</html>
