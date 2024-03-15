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
            $req = $bdd->prepare("SELECT * FROM convention_contrat JOIN utilisateur USING (idUtilisateur) WHERE idConvention = ?");
            $req->execute(array($idS));
            $resultat = $req->fetch();
            ?>

            <?php
            $idS = $_GET['id'];
            $req2 = $bdd->prepare("SELECT etat_convention, nomEntreprise, nomMDS, prenomMDS, nomTA, prenomTA, date, dateDeb, dateFin, gratification, format_gratification FROM convention_contrat LEFT JOIN tuteur_academique ON convention_contrat.idTA = tuteur_academique.idTA LEFT JOIN maitre_de_stage ON convention_contrat.idMDS = maitre_de_stage.idMDS LEFT JOIN site on site.idSite = maitre_de_stage.idSite LEFT JOIN entreprise on entreprise.idEntreprise = site.idEntreprise WHERE idConvention = ? order by idConvention");
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
                            <th>Entreprise</th>
                            <th>Nom Prénom Maître de stage</th>
                            <th>Nom Prénom Tuteur académique</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Gratification</th>
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
                            elseif ($ligne['etat_convention']== "conventionEnvoyee"){
                                $val= "Convention envoyée"; }
                            else {
                                $val= "Pas de convention pour le moment"; }
							?>
                            <tr>
                                <td><?php echo $val; ?></td>
                                <td><?php echo $ligne['nomEntreprise']; ?></td>
                                <td><?php echo $ligne['nomMDS']; echo " "; echo $ligne['prenomMDS']; ?></td>
                                <td><?php echo $ligne['nomTA']; echo " "; echo $ligne['prenomTA']; ?></td>
                                <td><?php echo $ligne['dateDeb']; ?></td>
                                <td><?php echo $ligne['dateFin']; ?></td>
                                <td><?php echo $ligne['gratification']; echo " "; echo $ligne['format_gratification']; ?></td>
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