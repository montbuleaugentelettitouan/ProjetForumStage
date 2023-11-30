<?php
session_start();
include('fonctionality/bdd.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"
            crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body class="container-body">

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-red">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.php">Stage GPhy</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

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

    <div id="layoutSidenav_nav">
        <div class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">

                    <!--<div class="sb-sidenav-menu-heading">Core</div>-->
                    <a class="nav-link" href="dashboardPersonnel.php">
                        <div class="sb-nav-link-icon"><!--<i class="fas fa-tachometer-alt"></i>--></div>
                        Mes choix
                    </a>

                    <a class="nav-link" href="dashboardM1.php">
                        <div class="sb-nav-link-icon"><!--<i class="fas fa-chart-area"></i>--></div>
                        Toutes les offres
                    </a>

                    <a class="nav-link" href="suivi_forum.php">
                        <div class="sb-nav-link-icon"><!--<i class="fas fa-chart-area"></i>--></div>
                        Suivi Post Forum Stage
                    </a>

                    <a class="nav-link" href="stage_accepte.php">
                        <div class="sb-nav-link-icon"></div>
                        Stage Accepté
                    </a>

                    <a class="nav-link" href="suivi_stage.php">
                        <div class="sb-nav-link-icon"></div>
                        Informations du stage
                    </a>

                     <a class="nav-link" href="suivi_convention.php">
                        <div class="sb-nav-link-icon"></div>
                        Informations de la convention
                    </a> 


<!--                    <a class="nav-link" href="test.php">
                        <div class="sb-nav-link-icon"></div>
                        Zone de test
                    </a>
-->
                </div>
            </div>
        </div>
    </div>