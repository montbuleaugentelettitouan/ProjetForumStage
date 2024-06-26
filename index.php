<?php
/**
 * Description et paramètres projet
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 * @UE : Projet annuel des entreprises Virtuelle
 * @ent : Grp 312
 *
 * @param : Utilisation de la librairie Boostrap v5.1.3
 * @param : Template SB Admin - https://github.com/startbootstrap/startbootstrap-sb-admin
 * @param : PHP v.8.1
 *
 */


//session_destroy();
// On initialise la session si elle existe (on redirige l'utilisateur s'il est déjà connecté)
session_start();

include('fonctionality/bdd.php');
// Requête SQL pour récupérer l'année de promotion la plus grande
$requete_max_annee = $bdd->query("SELECT MAX(promo) AS max_annee FROM utilisateur");
$resultat_max_annee = $requete_max_annee->fetch(PDO::FETCH_ASSOC);

// Récupération de l'année de promotion la plus grande
$annee_max = $resultat_max_annee['max_annee'];

$_SESSION['promo'] = $annee_max;
$_SESSION['annee'] = $_SESSION['promo'] - 1;

// permet de rediriger si l'utilisateur c'est déjà connecté, on regarde si la SESSION est vide (présence d'un ID ou non)
if (!empty($_SESSION["user"])) {
    if ($_SESSION['statut'] == "administrateur") {
        // si l'utilisateur est un admin alors on redirige vers la page admin
        header('Location: ../gestion_etudiants.php');
        exit();
    } else {
        // si l'utilisateur est un M1 on redirige vers la page de gestion de ses offres.
        header('Location: dashboardM1.php');
        exit();

    }
}

?>

<head>

    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Forum Stage - Login</title>
    <link href="css/styles.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"
            crossorigin="anonymous"></script>

</head>
<body class="bg-primary">
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                            <div class="card-body">


                                <form action='fonctionality/login.php' method="POST">

                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="mail" type="email" name="mail"
                                               required="required" placeholder="name@example.com"/>
                                        <label for="inputEmail">Email address</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="mdp" id="mdp" type="password"
                                               required="required" placeholder="Password"/>
                                        <label for="inputPassword">Password</label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" id="inputRememberPassword" type="checkbox"
                                               value=""/>
                                        <label class="form-check-label" for="inputRememberPassword">Remember
                                            Password</label>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <!-- <a class="small" href="password.php">Forgot Password ?</a> -->

                                        <button class="btn btn-primary" type="submit">Login</button>
                                    </div>

                                    <?php
                                    // on récupères les erreurs en cas de saisie incorrecte - Methode GET pour récupérer les erreurs via l'URL
                                    if (isset($_GET['erreur'])) {
                                        $err = $_GET['erreur'];
                                        if ($err == 1 || $err == 2)
                                        echo "Saisie incorrecte";
                                        ?>
                                        <p style='color:red'></p>
                                        <?php
                                    }
                                    ?>

                                </form>


                            </div>

                        </div>
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