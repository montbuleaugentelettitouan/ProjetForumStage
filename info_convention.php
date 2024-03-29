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
            <h1 class="mt-4">Suivi de l'avancement des signatures de la convention de Stage</h1>
            <form id="choixaccepte" method="post">
                <div class="card-body"> <!--div de tableau 1 -->
                    <table class="table table-striped" id="datatablesSimple">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Entreprise</th>
                            <th>Stage Accepté</th>
                            <th>Etat de la convention</th>
                            <th>Date du dernier changement d'état</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        /* recupération de la requête et affichage de toutes les données dans un tableau */
                        $req = $bdd->prepare("SELECT DISTINCT(idConvention), idUtilisateur, nom, prenom, nomEntreprise, titre FROM utilisateur JOIN convention_contrat USING (idUtilisateur) JOIN offre USING (idOffre) JOIN site USING (idSite) JOIN entreprise USING (idEntreprise) WHERE promo = ?;");
                        $req->execute(array($promo));
                        $resultat = $req->fetchAll();
                        foreach ($resultat as $ligne) {
                            $req2 = $bdd->prepare("SELECT etat_convention, date FROM convention_contrat WHERE idConvention = ? order by idConvention DESC LIMIT 1");
                            $req2->execute(array($ligne['idConvention']));
                            $resultat2 = $req2->fetch();
                            $rowcount = $req2->rowCount();
                            $val = "";
                            $date = "";
                            if ($rowcount != 0){
                                if ($resultat2['etat_convention']== "preconventionEnvoyee"){
                                    $val = "Préconvention envoyée";}
                                elseif ($resultat2['etat_convention']== "preconventionRecue"){
                                    $val= "Préconvention reçue"; }
                                elseif ($resultat2['etat_convention']== "conventionEditee"){
                                    $val= "Convention éditée"; }
                                elseif ($resultat2['etat_convention']== "conventionEnvoyee"){
                                    $val= "Convention envoyée"; }
                                else {
                                    $val= "Pas de convention pour le moment"; }
                                $date =$resultat2['date'];
                            }
                            ?>
                            <tr>
                                <td><?php echo $ligne['nom']; ?></td>
                                <td><?php echo $ligne['prenom']; ?></td>
                                <td><?php echo $ligne['nomEntreprise']; ?></td>
                                <td><?php echo $ligne['titre']; ?></td>
                                <td><a href="informations_convention.php?value=<?php echo $ligne['idUtilisateur']; ?>"> <?php echo $val; ?> </a></td>
                                <td><?php echo $date; ?></td>
                                <!-- <td> <a href="Send_mail_etu.php"> <?php echo $ligne['email']; ?> </a> </td> -->
                            </tr>

                        <?php }

                        ?>

                        </tbody>
                    </table>
                </div> <!--fin div de tableau 1 -->
        </div> <!-- fin div de page-->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin body de page-->
</body>
</html>