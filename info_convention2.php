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


            <?php
            $idS = $_GET['id'];
            $req = $bdd->prepare("SELECT * FROM stage join utilisateur using(idUtilisateur) WHERE idStage = ?");
            $req->execute(array($idS));
            $resultat = $req->fetch();
            ?>

            <?php
            $idS = $_GET['id'];
            $req2 = $bdd->prepare("SELECT etat_convention, date FROM convention WHERE idStage = ? order by id");
            $req2->execute(array($idS));
            $resultat2 = $req2->fetchAll();
            ?>

            <form id="choixaccepte" method="post">
                <div class="card-body"> <!--div de tableau 1 -->
                    <h1 class="mt-4"> Historique du suivi de la convention de stage de  <?php echo $resultat['prenom'];?> <?php echo $resultat['nom'];?></h1>
                    <br>
                    <table class="table table-bordered table-striped " id="datatablesSimple">
                        <thead class="thead-dark">
                        <tr>
                            <th>Etat de la convention</th>
                            <th>Date du changement d'état</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
						// permet l'affichage correcte de l'état d'avancement de la convention 
                        foreach ($resultat2 as $ligne) { 
						$val="";
						if ($ligne['etat_convention']== "preconventionEnvoyee"){
							$val = "Préconvention envoyée";}
						elseif ($ligne['etat_convention']== "preconventionRecue"){
							$val= "Préconvention reçue"; }
						elseif ($ligne['etat_convention']== "conventionEditee"){
							$val= "Convention éditée"; }
						else{
							$val= "Convention envoyée"; }
							?>
                            <tr>
                                <td><?php echo $val; ?></td>
                                <td><?php echo $ligne['date']; ?></td>
                            </tr>
                        <?php }

                        ?>

                        </tbody>
                    </table>
                </div> <!--fin div de tableau 1 -->





                <a href="info_convention.php" class="btn btn-warning" >BACK</a>
        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>