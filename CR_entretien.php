<?php
include('barre_nav_M1.php');
include('fonctionality/bdd.php');
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!-- Affichage des paramètres de session de l'utilisateur -->
            <h1 class="mt-4">Suivi des entretiens de <?php echo $_SESSION['nom']; ?> <?php echo $_SESSION['prenom']; ?></h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Remplir le formulaire</li>
            </ol>

            <form id="dataCR" method="post">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="far fa-file-pdf"></i>
                        Mes entretiens
                    </div>
                    <div class="cart-body">

                        <!-- <form id="datatablesSimple"> -->

                        <!-- Table d'affichage des offres -->
                        <table class="table table-bordered">
                            <thead class="thead-dark">

                            <tr>
                                <th>Nom</th>
                                <th>Entreprise</th>
                                <th>Déroulé de l'entretien</th>
                            </tr>
                            </thead>

                    
                            <tbody>
                            <?php
                            
                            // requête pour sélectionner les offres de la table OFFRES inscrites dans la table MES CHOIX (jointure)
                            // On ajoute une autre jointure pour limiter la sélection des offres ou l'ID utilisateur correspond à MES CHOIX + jointure pour integrer cr de l'entretien
                            $req = $bddd->prepare('select idOf, nomOf, entreprise, CR_entretien from suivi_forum join choix_offre on suivi_forum.idU = choix_offre.idUtilisateur join offres_stages on choix_offre.idOffre = offres_stages.idOf where choix_offre.idUtilisateur=?');
                            $req->execute(array($_SESSION['user']));
                            $resultat = $req->fetchAll();

                            // on compte les lignes pour valider lors du développement
                            $count = $req->rowcount();
                            
                            foreach($resultat as $ligne) { ?>
                                <tr>
                                    <td><?php echo $ligne['nomOf']; ?></td>
                                    <td><?php echo $ligne['entreprise']; ?></td>
                                    <td><?php echo $ligne['CR_entretien']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <br>
                    </div>
                <div>
                <input type="submit" class="btn btn-warning" name="Valider" value="Valider">
            </form>
            <br>
        </div>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>