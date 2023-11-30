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

<!-- Body de la page -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Etat de la recherche des etudiants</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Vue générale de l'etat de recherche des etudiants</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="far fa-file-pdf"></i>
                    La liste des étudiants M1
                </div>

                <!-- Sélection de l'étudiant pour l'affichage -->
                <div class="card-body">

                    <form method="POST" action="#">
                        <select name="Etudiant" id="Etudiant" required>
                        <option value="">Sélectionnez un étudiant</option>
                            <?php
                            $reponse = $bdd->query('SELECT idUtilisateur, nom, prenom FROM utilisateur WHERE statut = "etudiant" ORDER BY nom ASC ');
                            while ($donnees = $reponse->fetch())
                            {
                                ?>

                                    <option value="<?php echo $donnees['idUtilisateur']; ?>">
                                        <?php echo $donnees['nom']; ?>
                                        <?php echo $donnees['prenom']; ?>

                                    </option>

                            <?php }

                            ?>
                        </select>
                        <input type="submit" class="btn btn-warning" name="valider" value="Valider">
                    </form>

                </div>
                <br>

                <!-- Affichage des noms des étudiants n'ayant réalisés aucun choix -->

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="far fa-file-pdf"></i>
                        Affichage du détail de l'étudiant
                    </div>


                    <div class="card-body">

                        <?php

                        if (isset($_POST['valider'])) {

                            $valid = $_POST['Etudiant'];
                            
                            ?>
                        <?php
                        $reponse76 = $bdd->query("SELECT * FROM utilisateur WHERE idUtilisateur='$valid'");
                        //$reponse74->execute(array($valid));
                        $resultat76 = $reponse76->fetch();
                        ?>

                        <h2>  <?php echo $resultat76['nom'] ?> <?php echo ''?> <?php echo $resultat76['prenom'] ?></h2>
                               <?php
                            $reponse74 = $bdd->query("SELECT utilisateur.idUtilisateur, etat_recherche, cr_forumM1 FROM utilisateur JOIN postule_m1 on utilisateur.idUtilisateur = postule_m1.idUtilisateur WHERE utilisateur.idUtilisateur = '$valid' ");
                            //$reponse74->execute(array($valid));
                            $resultat74 = $reponse74->fetch();

                            $reponse72 = $bdd->query("SELECT offre.idOffre, titre, description, nomEntreprise FROM utilisateur JOIN postule_m1 on utilisateur.idUtilisateur = postule_m1.idUtilisateur JOIN offre on postule_m1.idOffre = offre.idOffre JOIN site on offre.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise WHERE utilisateur.idUtilisateur = '$valid'");
                            //$reponse74->execute(array($valid));
                            $resultat72 = $reponse72->fetch();
                            
                            ?>

                        <br>

                            <div class="card" style="width: 100rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Etat de la recherche</h5>
                                    <p class="card-text"><?php echo $resultat74['etat_recherche'] ?></p>
                                </div>
                            </div>

                            <br>

                            <div class="card" style="width: 100rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Compte rendu des entretiens</h5>
                                    <table class="table table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <td>Entreprise</td>
                                                <td>Site</td>
                                                <td>Intitulé de l'offre</td>
                                                <td>Entretien</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                //On écrit la requête pour afficher les premiers champs dans le tableau
                                                $req = $bdd->prepare('SELECT titre, nomEntreprise, nomSite, cr_entretien FROM entreprise JOIN site on entreprise.idEntreprise = site.idEntreprise JOIN offre ON site.idSite = offre.idSite JOIN postule_m1 on offre.idOffre = postule_m1.idOffre WHERE postule_m1.idUtilisateur =? ORDER BY offre.idOffre ASC');
                                                $req->execute(array($valid));
                                                $resultat = $req->fetchAll();
                                                $count = $req->rowcount();
                                                //Affichage du tableau
                                                foreach($resultat as $ligne) { 
                                                ?>
                                                <tr>
                                                    <td><?php echo $ligne['nomEntreprise'];?></td>
                                                    <td><?php echo $ligne['nomSite'];?></td>
                                                    <td><?php echo $ligne['titre']; ?></td>
                                                    <td><?php echo $ligne['cr_entretien']; ?></td>
                                                </tr>
                                                <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <br>

                            <div class="card" style="width: 100rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Compte rendu du Forum Stage</h5>
                                    <p class="card-text"><?php echo $resultat74['cr_forumM1'] ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                    else {

                    }
                    ?>
            </div>
        </div>
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div>
</body>
</html>