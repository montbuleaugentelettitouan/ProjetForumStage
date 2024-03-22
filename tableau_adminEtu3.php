<?php
/**
 *
 * @autor:  Tom ROBIN, Axel ITEY, Nathan GODART, Damien CALOIN
 * @date : Promo Gphy 2023 - Année 2022 - 2023
 *
 */
include('barre_nav_admin.php');
include('fonctionality/bdd.php');
include('fonctionality/annee+promo.php');
?>
<div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4"> <!-- div de page-->
        <!-- titre de la page -->
            <h1 class="mt-4"> Etudiants étant encore en recherche</h1>
            <form id="choixaccepte" method="post">
                <div class="card-body"> <!--div de tableau 1 -->
                <!-- tableau des Etudiants étante encore en recherche de stage  -->
                    <table class="table table-striped" id="datatablesSimple">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Adresse Mail</th>
                            <th>Selection pour l'Envoi de Mail</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // recupération de la requête et affichage de toutes les données dans un tableau
                        $req = $bdd->prepare("select DISTINCT(email), nom, prenom, idUtilisateur from utilisateur where etatC = 'en recherche' and promo = ? ; ");
                        $req->execute(array($promo));
                        $resultat = $req->fetchAll();
                        // déclaration d'un compteur pour afficher le nombre d'entrée dans le tableau des etudiants étant encore en recherche
                        $cpt1 = 0;

                        foreach ($resultat as $ligne) { ?>
                        <!-- Pour chaque tour de boucle le compteur prendre +1 -->
                        <?php $cpt1 = $cpt1 + 1;?>
                            
                            <!-- Affichage du tableau, pour les noms et prénoms, ce sont des liens cliquables (href) qui renvoie vers la page perso de l'étudiant en question -->
                            <tr>
                                <!-- le nom -->
                                <td><a href="dashboardSUIVIFORUM2.php?value=<?php echo $ligne['idUtilisateur'];?>"><?php echo $ligne['nom']; ?></a></td>
                                <!-- le prénom -->
                                <td><a href="dashboardSUIVIFORUM2.php?value=<?php echo $ligne['idUtilisateur'];?>"><?php echo $ligne['prenom']; ?></a></td>
                                <!-- L'adresse mail de létudiant -->
                                <td><?php echo $ligne['email']; ?></td>
                                <!-- <td> <a href="Send_mail_etu.php"> <?php echo $ligne['email']; ?> </a> </td> -->
                                <td align = "right">
                                    <!-- une checkbox pour l'enbvoie de mail aux etudiant (selection de la checkbox afin de l'envoyer un mail a l'étudiant selectioné, plusieurs etudiants peuvent etre selectiionés) -->
                                    <input type="checkbox" name="mailing<?php echo $cpt1?>" value="oui" > 
                                </td>
                            </tr>

                        <?php 
                        }
                        ?>

                        </tbody>
                    </table>
                <!-- LES BOUTONS DE BAS DE PAGE :  -->
                </div> <!--fin div de tableau 1 -->
                <!-- bouton back qui renvoie vers la page d'avant -->
                <a href="tableau_de_bord_ADMIN.php" class="btn btn-warning" >BACK</a>
                <!-- bouton pour envoyer un mail a tout les étudiants présents dans le tableau -->
                <input type="submit" class="btn btn-warning" name="validerenvoitous" value="Envoyer un mail à tous les étudiants">
                <!-- bouton pour envoyer un mail aux etudiants étant selectionné uniquement -->
                <input type="submit" class="btn btn-warning" name="validerenvoi" value="Envoyer un mail aux étudiants sélectionnés"> 
            </form>

            <?php
            // Si le user clique sur "Envoyer un mail aux étudiants sélectionnés"
                if (isset($_POST['validerenvoi']))
                {
                    $cpt2 = 0;
                    $liste = '';
                    foreach ($resultat as $ligne) {
                        $cpt2 = $cpt2 +1;
                        // pour chaque ligne du tableau, si la checkbox est cochée, on mets l'adresse mail dans la liste déclarée plus tôt
                        if (isset($_POST['mailing' . $cpt2]) && $_POST['mailing' . $cpt2] == 'oui') {
                            $liste.= $ligne['email'];
                            // on sépare les adresses mails par une virgule (cest pour la fonction d'envoie de mail)
                            $liste.= ", ";
                        }
                    }
                    $liste = substr($liste, 0, -2);

                    $_SESSION["Listemail"] = $liste;
                    echo "<script>window.location.replace(\"Send_mail_etu.php\")</script>";

                }
                // Si le user clique sur "Envoyer un mail à tous les étudiants"
                if (isset($_POST['validerenvoitous'])) 
                {
                    // la, ca mets dans la liste tout les etudiants qui sont en recherche (on va les chercher direct dans la bbd)
                    $req = $bdd->prepare("select DISTINCT(email) from utilisateur where etatC = 'en recherche' and promo = ? ; ");
                        $req->execute(array($promo));
                        $resultat = $req->fetchAll();
                        $liste = '';
                        // la pareil : on sépare les adresses mails par une virgule (cest pour la fonction d'envoie de mail)
                        foreach ($resultat as $ligne) {
                            $liste.= $ligne['email'] ;
                            $liste.= ", "; 
                        }
                    $liste = substr($liste, 0, -2);
                    $_SESSION["Listemail"] = $liste;
                    echo "<script>window.location.replace(\"Send_mail_etu.php\")</script>";


                }

            ?>



        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>