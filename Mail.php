
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
        <h1> TEST SUR SERVEUR </h1>    
        <h1 class="mt-4"> Envoie de Mail</h1>
            <body>
        
                        <?php
                            
                                        mail("damien.caloin@etu.univ-poitiers.fr, nathan.godart@etu.univ-poitiers.fr ", "ForumStage", "message essai", "From:forumStageGphy@univ-poitiers.fr");
                                        echo "Mail envoyé aveeeec succès.";        
    
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
