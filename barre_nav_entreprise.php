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
    <a class="navbar-brand ps-3">Stages GPhy</a>
    <a class="navbar-brand ps-3", centered>Forum Stage du 26/01/2024</a>
</nav>


<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <div class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <br>
                    <!--<div class="sb-sidenav-menu-heading">Core</div>-->
                    <a class="nav-link" href="Formulaire_entreprise.php">
                        <div class="sb-nav-link-icon"><!--<i class="fas fa-tachometer-alt"></i>--></div>
                        Ajout d'un stage
                    </a>
                </div>
            </div>
        </div>
    </div>
