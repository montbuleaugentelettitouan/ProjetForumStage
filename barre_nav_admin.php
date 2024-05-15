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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-red">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="gestion_etudiants.php"><?php echo $_SESSION['parcours'] ?> Promo <?php echo $_SESSION['promo'] ?></a>
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
                <li>
                    <hr class="dropdown-divider"/>
                </li>
                <li><a class="dropdown-item" href="ajout_promo.php">Ajouter une nouvelle promo</a></li>
                <li>
                    <hr class="dropdown-divider"/>
                </li>
                <li><a class="dropdown-item" href="evolution_etudiants.php">Passage M1 -> M2</a></li>
                <li>
                    <hr class="dropdown-divider"/>
                </li>
                <li><a class="dropdown-item" href="uploads/GuideAdmin.pdf" download="GuideAdmin.pdf"><i class="fas fa-file-pdf"></i> Guide d'utilisation</a></li>
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
                    <!-- Autres éléments de la barre latérale -->
                    <li class="border-bottom">
                        <a class="nav-link" href="gestion_etudiants.php">
                            <div class="sb-nav-link-icon"><!--<i class="fas fa-chart-area"></i>--></div>
                            <b><span style="color: #ffc107;">Gestion des étudiants</span></b>

                        </a>
                    </li>
                    <li class="border-bottom">
                        <a class="nav-link" href="gestion_entreprise.php">
                            <div class="sb-nav-link-icon"><!--<i class="fas fa-chart-area"></i>--></div>
                            <b><span style="color: #ffc107;">Gestion des entreprises</span></b>

                        </a>
                    </li>
                    <li>
                        <a class="nav-link" data-bs-toggle="collapse" href="#collapseExample2" role="button" aria-expanded="false" aria-controls="collapseExample2">
                            <div class="sb-nav-link-icon"><!--<i class="fas fa-tachometer-alt"></i>--></div>
                            <b><span id="toggleIcon2" style="color: #dc3545;">▷</span><span style="color: #dc3545;">GESTION DES OFFRES</span></b>

                        </a>
                    </li>
                    <div class="collapse" id="collapseExample2">
                        <li>
                            <a class="nav-link" href="nombres_postulations_offres.php">
                                <div class="sb-nav-link-icon"></div>
                                Récap postes et offres
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="dashboardVueOffres.php">
                                <div class="sb-nav-link-icon"></div>
                                Recueil des offres
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="validation_offres.php">
                                <div class="sb-nav-link-icon"></div>
                                Validation des offres
                            </a>
                        </li>
                    </div>
                    <!-- Onglet principal -->
                    <li>
                        <a class="nav-link" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <div class="sb-nav-link-icon"><!--<i class="fas fa-tachometer-alt"></i>--></div>
                            <b><span id="toggleIcon" style="color: #dc3545;">▷</span><span style="color: #dc3545;">FORUM STAGE</span></b>

                        </a>
                    </li>
                    <!-- Section des autres onglets -->
                    <div class="collapse" id="collapseExample">
                        <li>
                            <a class="nav-link" href="dashboardADMIN.php">
                                <div class="sb-nav-link-icon"><!--<i class="fas fa-chart-area"></i>--></div>
                                Priorités des étudiants
                            </a>
                        </li>
                        <!-- <br>
                        <li>
                            <a class="nav-link" href="dashboardEtatEtudiant.php">
                                <div class="sb-nav-link-icon"> ne pas oublier de commenter cette class comme plus haut <i class="fas fa-chart-area"></i></div>
                            Etat de recherche des étudiants (obsolète - voir Suivi Post Forum Stage)
                        </a>
                        </li> -->
                        <!--<br>
                        <li class="border-bottom">
                            <a class="nav-link" href="suivi_convention_stage.php">
                                <div class="sb-nav-link-icon"></div>
                                Suivi des conventions de stage
                            </a>
                        </li>-->
                        <li>
                            <a class="nav-link" href="tableau_de_bord_ADMIN.php">
                                <div class="sb-nav-link-icon"></div>
                                Stats des étudiants
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="tableau_de_bord_ADMIN_entreprise.php">
                                <div class="sb-nav-link-icon"></div>
                                Stats des entreprises
                            </a>
                        </li>
                    </div>
                    <li>
                        <a class="nav-link" data-bs-toggle="collapse" href="#collapseExample3" role="button" aria-expanded="false" aria-controls="collapseExample3">
                            <div class="sb-nav-link-icon"><!--<i class="fas fa-tachometer-alt"></i>--></div>
                            <b><span id="toggleIcon3" style="color: #dc3545;">▷</span><span style="color: #dc3545;">SUIVI POST-M1</span></b>

                        </a>
                    </li>
                    <div class="collapse" id="collapseExample3">

                        <li>
                            <a class="nav-link" href="gestion_etudiants_alternance.php">
                                <div class="sb-nav-link-icon"></div>
                                Tableau des étudiants
                            </a>
                        </li>
                        
                        <!-- Ajoutez d'autres onglets ici si nécessaire -->
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- JavaScript pour activer le comportement collapse -->
    <script>
        $(document).ready(function(){
            // Fonction pour changer l'icône et stocker l'état du menu
            function toggleMenuState(menuId, toggleIconId) {
                $(menuId).on('show.bs.collapse', function () {
                    $(toggleIconId).text('▽ ');
                    localStorage.setItem(menuId + '_state', 'expanded'); // Stocker l'état du menu
                });

                $(menuId).on('hide.bs.collapse', function () {
                    $(toggleIconId).text('▷ ');
                    localStorage.setItem(menuId + '_state', 'collapsed'); // Stocker l'état du menu
                });

                // Restaurer l'état du menu lors du chargement de la page
                var menuState = localStorage.getItem(menuId + '_state');
                if (menuState === 'expanded') {
                    $(menuId).collapse('show');
                    $(toggleIconId).text('▽ ');
                } else {
                    $(menuId).collapse('hide');
                    $(toggleIconId).text('▷ ');
                }
            }

// Menu déroulant "FORUM STAGE"
            toggleMenuState('#collapseExample', '#toggleIcon');

// Menu déroulant "STAGES M1"
            toggleMenuState('#collapseExample2', '#toggleIcon2');

// Menu déroulant "ALTERNANCES"
            toggleMenuState('#collapseExample3', '#toggleIcon3');

            // Empêcher la fermeture automatique du menu lorsqu'un élément de menu est cliqué
            $('.nav-link').on('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
