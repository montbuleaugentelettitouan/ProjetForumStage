<?php
session_start();
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Changement de mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet"/>
    <link href="css/styles.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"
            crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.php">Stage M1 GPhy</a>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
               aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="change_password.php">Changement mot de passe</a></li>
                <li>
                    <hr class="dropdown-divider"/>
                </li>
                <li><a class="dropdown-item" href="fonctionality/deconnexion.php">Déconnexion</a></li>
            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="form-check">
                        </div>
                        <form action='fonctionality/change_password_f.php' method="POST">
                            <div class="form-group">
                                <label for="oldpassword">Ancien mot de passe</label>
                                <input type="password" class="form-control" name="oldpassword" placeholder="Password"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="newpassword">Nouveau mot de passe</label>
                                <input type="password" class="form-control" name="newpassword" placeholder="Password"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="newpassword2">Entrez à nouveau votre nouveau mot de passe</label>
                                <input type="password" class="form-control" name="newpassword2" placeholder="Password"
                                       required>
                            </div>
                            <div class="form-check">

                            </div>
                            <button type="submit" class="btn btn-danger" name="submit">Changer le mot de passe</button>
                            <button type="button" class="btn btn-outline-danger"
                                    onclick=window.location.href='index.php';>Annuler
                            </button>
                            <?php
                            if (isset($_GET['erreur'])) {
                                $err = $_GET['erreur'];
                                if ($err == 1) {
                                    echo "<p style='color:red'>Veuillez remplir tous les champs</p>";
                                } else if ($err == 2) {
                                    echo "<p style='color:red'>L'ancien mot de passe saisi ne correspond pas</p>";
                                } else if ($err == 3) {
                                    echo "<p style='color:red'>Vos deux nouveaux mots de passes ne sont pas identiques</p>";
                                }
                            }

                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php
        include('fonctionality/footer.php');
        ?>
    </div>
</div>
</body>
</html>