<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */
include('barre_nav_admin.php');
include('fonctionality/bdd.php');
?>
<style>
    /* Style pour la pop-up */
    .popup {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .popup .popup-content {
        visibility: hidden;
        width: max-content;
        background-color: #f8f9fa;
        color: #000;
        text-align: center;
        border-radius: 5px;
        padding: 10px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -100px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .popup:hover .popup-content {
        visibility: visible;
        opacity: 1;
    }
</style>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Gestion des entreprises</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"></li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <button onclick="bottomFunction()" id="scrollBottomBtn" class="btn btn-secondary" title="Aller en bas de la page">Bas de la page</button>
                    <script>
                        // Fonction pour aller en bas de la page
                        function bottomFunction() {
                            window.scrollTo(0, document.body.scrollHeight); // Fait défiler vers le bas de la page
                        }
                    </script>
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-striped table-bordered table-sm">
                        <thead>
                            <tr>
                                <th style="border-bottom : 2px solid black;border-left : 2px solid black; border-top : 2px solid black;">Nom de l'entreprise</th>
                                <th style="border-bottom : 2px solid black;border-top : 2px solid black;">
                                    <div class="popup" id="icon">
                                        <img src="assets/img/communication.png" alt="Icône">
                                        <span class="popup-content" style="position: absolute; top: -55px; left: 60px;"> Contacts RH</span>
                                    </div>
                                </th>
                                <th style="border-bottom : 2px solid black;border-top : 2px solid black;">Nom du site</th>
                                <th style="border-bottom : 2px solid black;border-top : 2px solid black;">Ville</th>
                                <th style="border-bottom : 2px solid black;border-top : 2px solid black;">Pays</th>
                                <th style="border-bottom : 2px solid black;border-right : 2px solid black; border-top : 2px solid black;">Modification</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $req = $bdd->query("SELECT entreprise.idEntreprise, nomEntreprise, idSite, nomSite, ville, pays, contactRH FROM entreprise LEFT JOIN site on entreprise.idEntreprise = site.idEntreprise order by nomEntreprise asc ");
                        //$resultat = $bdd->query($req);
                        $resultat = $req->fetchAll();
                        $i = 0;
                        $totalLigne = count($resultat);
                        foreach ($resultat as $ligne) {
                            $i++;
                            ?>
                            <tr>
                                <td style="border-left : 2px solid black; <?php if ($i == $totalLigne) { echo 'border-bottom : 2px solid black;"'; } ?>"><a href="tableau_adminE2.php?id=<?php echo $ligne['idEntreprise'];?>"><?php echo $ligne['nomEntreprise']; ?></a></td>
                                <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>>
                                    <?php echo $ligne['contactRH']; ?>
                                </td>
                                <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>><?php echo $ligne['nomSite']; ?></td>
                                <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>><?php echo $ligne['ville']; ?></td>
                                <td <?php if ($i == $totalLigne) { echo 'style="border-bottom : 2px solid black;"'; } ?>><?php echo $ligne['pays']; ?></td>
                                <td style="border-right : 2px solid black; <?php if ($i == $totalLigne) { echo 'border-bottom : 2px solid black;"'; } ?>">
                                    <center>
                                    <a href="modif_entreprise.php?id=<?php echo $ligne['idSite']; ?>" class="btn btn-primary">Modifier</a>
                                    </center>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                    <button onclick="topFunction()" id="scrollTopBtn" class="btn btn-secondary" title="Revenir en haut de la page">Haut de la page</button>

                    <script>
                        // Fonction pour revenir au haut de la page
                        function topFunction() {
                            document.body.scrollTop = 0; // Pour les navigateurs Chrome, Safari et Opera
                            document.documentElement.scrollTop = 0; // Pour les navigateurs Firefox, IE et Edge
                        }
                    </script>
            </div>
        </div>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>
</body>
</html>