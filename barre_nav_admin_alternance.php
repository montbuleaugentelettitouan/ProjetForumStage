<?php
session_start();
include('fonctionality/bdd.php');
include('fonctionality/loggedAdmin.php');
include('fonctionality/annee+promo.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_POST['recherche'])) {
    $searchQuery = $_POST['recherche'];

    if (!empty($searchQuery)) {
        // Rediriger vers la page de résultats
        header("Location: resultat_recherche.php?query=" . urlencode($searchQuery));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Forum Stage GPhy</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet"/>
    <link href="css/styles.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/datatables.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"
            crossorigin="anonymous"></script>

</head>

<body class="container-body">

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-red">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="Vierge_alternance.php">Alternances <?php echo $_SESSION['parcours'] ?> Promo <?php echo $_SESSION['promo'] ?></a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
    <form method="POST" action="#">
        <div class="card-body"> <!--div de tableau 1 -->
            <select name="annee" id="annee" required>
                <option value="">Sélectionnez une Promotion... </option>
                <?php
                $ReqParcours = $bdd->prepare('SELECT DISTINCT parcours, promo FROM utilisateur WHERE parcours IS NOT NULL AND promo IS NOT NULL ORDER BY promo DESC;');
                $ReqParcours->execute();

                while ($donnees = $ReqParcours->fetch()) {
                    $pomo = $donnees['promo'];
                    ?>
                    <option value="<?php echo $donnees['parcours'] . ' ' . $donnees['promo']; ?>">
                        Promo <?php echo $donnees['parcours'] . ' ' . $pomo; ?>
                    </option>
                <?php } ?>
            </select>
            <input type="submit" class="btn btn-warning" name="ValidAnnee" value="Valider">
        </div> <!--fin div de tableau 1 -->
    </form>
    <?php
    if(isset($_POST['annee'])) {
        $selectedOption = $_POST['annee'];

        // On sépare le parcours de l'année dans ce qui nous a été envoyé par le formulaire en deux parties distinctes grâce à "explode"
        list($parRes, $anneeRes) = explode(' ', $selectedOption);
        $_SESSION['compt']= 0;
        $_SESSION['annee'] = (int)$anneeRes - 1;
        $_SESSION['promo'] = (int)$anneeRes;
        $_SESSION['parcours'] = $parRes;
        $annee = $_SESSION['annee'];
        $promo = $_SESSION['promo'];
        $parcours = $_SESSION['parcours'];
        echo "<script>window.location.replace('" . $_SERVER['PHP_SELF'] . "')</script>";
    }
    else{
        $annee = $_SESSION['annee'];
        $promo = $_SESSION['promo'];
        $parcours = $_SESSION['parcours'];
    }
    ?>
    <!-- Navbar Alternance-->
    <a href="nombres_postulations_offres.php">
        <input type="button" class="btn btn-warning" name="Stages" value="Stages" style="margin-left: 200px; background-color: orange;"> <!--chemin accès a la page alternance-->
    </a>

    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" action="" method="POST">
        <input type="text" name="recherche" placeholder="Rechercher...">
        <input type="submit" value="Rechercher">
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
                <li><a class="dropdown-item" href="register.php">Création de compte</a></li>
        </li>
        <li>
            <hr class="dropdown-divider"/>
        </li>
        <li><a class="dropdown-item" href="fonctionality/deconnexion.php">Déconnexion</a></li>
    </ul>
    </li>
    </ul>
</nav>

<!-- Barre lateral de navigation -->
<div id="layoutSidenav">

    <div id="layoutSidenav_nav">
        <div class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <a class="nav-link" href="dashboardPersonnel.php">
                        <div class="sb-nav-link-icon"><!--<i class="fas fa-tachometer-alt"></i>--></div>
                        Mes choix
                    </a>
                </div>
            </div>
        </div>
    </div>