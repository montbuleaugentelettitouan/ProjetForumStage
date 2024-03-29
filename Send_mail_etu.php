<?php
/**
 * Fonctionnalité de login à l'application
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
            <h1 class="mt-4"> Envoi de Mail</h1>

                        <body>
                            <?php
                              $liste = '';
                            //   dans la liste des destinataires du mail, on mets patrick thierry, et dominique, cest ce qu'ils voulaient
                              $liste = "patrick.girard@univ-poitiers.fr, thierry.urruty@univ-poitiers.fr, dominique.geniet@univ-poitiers.fr, ";

                            if (isset($_GET['mail'])) {
                                $liste .= $_GET['mail'];
                            } else {
                                $liste .= $_SESSION["Listemail"];
                            }
                            ?>  
                            <!-- la on affiche la liste des destinataire -->
                                <h3>Destinataires : </h3> <br> <?php echo $liste ?><br><br>

                                        <div class="contactez-nous">

                                            <form  method="post">
                                                <?php
                                                // décaration d'une variable pour l'objet
                                                $objet ='';
                                                // ajout d'un message par defaut pour l'objet
                                                if (isset($_GET['mail'])) {
                                                    $objet = "Forum Stage Gphy";
                                                } else {
                                                    $objet = "Forum Stage Gphy Rappel";
                                                } ?>
                                                <div>
                                                <h3> <label for="nom">Objet du mail </label><br><br> </h3>
                                                <!-- text area pour l'objet, section modifiable par l'utilisateur -->
                                                    <textarea rows="1%" cols="30%" id="Object" name="Object" placeholder="Object"> <?php echo $objet ?> </textarea>
                                                </div><br>
                                                
                                                <?php
                                                // la pareil, déclaration de la variable du contenu du message
                                                $message = '';
                                                // la on y mets un message par defaut
                                                $message = "Bonjour,\n";
                                                $message .= "	\n";
                                                if (!isset($_GET['mail'])) {
                                                    $message .= "	Ce mail est envoyé automatiquement aux élèves n'ayant pas encore trouvé un stage,\n";
                                                } else {
                                                    $message .= "	Entrez votre message ici...\n";
                                                }
                                                $message .= "	\n";
                                                $message .= "	\n";
                                                $message .= "	\n";
                                                $message .= "Cordialement, l'équipe pédagogique\n";

                                                ?>
                                                <div>
                                                <h3>  <label for="message">Votre message</label><br><br> </h3>
                                                <!-- text area pour le contenu du mail, section modifiable par l'utilisateur -->
                                                    <textarea rows="10%" cols="100%" id="message" name="Message" placeholder="Message" ><?php echo $message ?>  </textarea>
                                                </div><br>
                                      <!-- bouton back pour retrouner en arriere  -->
                                <!-- bouton envoyer pour envoyer le mail (logique ... ) -->
                                <input type= "submit" class="btn btn-warning" name="Envoyer" value="Envoyer">
                                </form>
                               
                                </div>
                                <?php
                                // si le user clique sur le bouton envoyer (ligne 62), ca envoie le mail avec la fonction mail (ligne 70)
                                if (isset($_POST['Envoyer'])) 
                                    {
                                    // fonction mail : composition : mail(destinataire, l'objet du mail, le message du mail, l'adresse qui envoie le mail) (cest le serveur qui s'occupe du reste)
                                    mail($liste, $_POST["Object"], $_POST["Message"], "From:forumStageGphy@univ-poitiers.fr");
                                    echo "Mail(s) envoyé(s) !";                                    
                                    }
                                ?>
                        </body>
    
        </div> <!-- fin div de page-->

    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>
