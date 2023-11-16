<?php
session_start();
include('fonctionality/bdd.php');
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
    <a class="navbar-brand ps-3" href="nombres_postulations_offres.php"><?php echo $_SESSION['parcours'] ?> Promo <?php echo $_SESSION['promo'] ?></a>
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
        if (!isset($_SESSION['compt'])){
            $annee = 2023;
            $promo = 2024;
            $parcours = 'GPhy';
        }
        else {
            $annee = $_SESSION['annee'];
            $promo = $_SESSION['promo'];
            $parcours = $_SESSION['parcours'];
        }
    }
    ?>
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

                    <!--<div class="sb-sidenav-menu-heading">Core</div>-->
                    <br>
                    <li>
                        <a class="nav-link" href="nombres_postulations_offres.php">
                            <div class="sb-nav-link-icon"><!--<i class="fas fa-tachometer-alt"></i>--></div>
                            Les offres de stage
                        </a>
                    </li>
                    <br>
                    <li>
                        <a class="nav-link" href="dashboardADMIN.php">
                            <div class="sb-nav-link-icon"><!--<i class="fas fa-chart-area"></i>--></div>
                            Choix des étudiants
                        </a>
                    </li>
                    <!-- <br>
                    <li>
                        <a class="nav-link" href="dashboardEtatEtudiant.php">
                            <div class="sb-nav-link-icon"> ne pas oublier de commenter cette class comme plus haut <i class="fas fa-chart-area"></i></div>
                        Etat de recherche des étudiants (obsolète - voir Suivi Post Forum Stage)
                    </a>
                    </li> -->
                    <br>
                    <li>
                        <a class="nav-link" href="dashboardSUIVIFORUM.php">
                            <div class="sb-nav-link-icon"><!--<i class="fas fa-chart-area"></i>--></div>
                            Suivi Post Forum Stage
                        </a>
                    </li>
                    <br>
                    <li>
                        <a class="nav-link" href="infos_stage.php">
                            <div class="sb-nav-link-icon"></div>
                            Suivi des stages
                        </a>
                    </li>
                    <br>
                    <li class="border-bottom">
                        <a class="nav-link" href="info_convention.php">
                            <div class="sb-nav-link-icon"></div>
                            Suivi des conventions
                        </a>
                    </li>
                    <!--<br>
                    <li class="border-bottom">
                        <a class="nav-link" href="suivi_convention_stage.php">
                            <div class="sb-nav-link-icon"></div>
                            Suivi des conventions de stage
                        </a>
                    </li>-->
                    <br>
                    <li>
                        <a class="nav-link" href="tableau_de_bord_ADMIN.php">
                            <div class="sb-nav-link-icon"></div>
                            Tableau de bord étudiants
                        </a>
                    </li>
                    <br>
                    <li class="border-bottom">
                        <a class="nav-link" href="tableau_de_bord_ADMIN_entreprise.php">
                            <div class="sb-nav-link-icon"></div>
                            Tableau de bord entreprises
                        </a>
                    </li>
                    <br>
                    <li>
                        <a class="nav-link" href="gestion_etudiants.php">
                            <div class="sb-nav-link-icon"><!--<i class="fas fa-chart-area"></i>--></div>
                            Gestion des étudiants

                        </a>
                    </li>
                    <br>
                    <li>
                        <a class="nav-link" href="gestion_entreprise.php">
                            <div class="sb-nav-link-icon"></div>
                            Gestion des entreprises
                        </a>
                    </li>
                    <br>
                    <li>
                        <a class="nav-link" href="dashboardVueOffres.php">
                            <div class="sb-nav-link-icon"></div>
                            Gestion des offres
                        </a>
                    </li>
                    <br>
                    <li>
                        <a class="nav-link" href="gestion_tuteur.php">
                            <div class="sb-nav-link-icon"></div>
                            Gestion des tuteurs
                        </a>
                    </li>
                </div>
            </div>
        </div>
    </div>