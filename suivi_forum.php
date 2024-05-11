<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */
/**
 * Modifications
 *
 * @autor : Tom ROBIN, Nathan GODART, Damien CALOIN et Axel ITEY
 * @date : Promo GPhy 2023 - Année 2022 : 2023
 *
 */
/**
 * démarrage de la session utilisateur pour pouvoir accéder au dashboard
 * ajout fichier avec la connexion à la base de données
 * code pour voir les éventuelles fautes de php
 */

 //fonction qui permet d'afficher du texte dans la console log 
 function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

include('barre_nav_M1.php');
include('fonctionality/bdd.php');

//Requete pour afficher plus loin les résultats saisis
$reqetat = $bdd->prepare('SELECT DISTINCT utilisateur.idUtilisateur, idOffre, etat_recherche, cr_forumM1 FROM postule_m1 JOIN utilisateur on postule_m1.idUtilisateur = utilisateur.idUtilisateur WHERE utilisateur.idUtilisateur = ?');
$reqetat->execute(array($_SESSION['user']));
$resultatetat = $reqetat->fetch();
//compteur pour savoir si la requete renvoie un résultat (= au nombre de lignes)
$count = $reqetat->rowcount();


if($count!=0){
    $etat = $resultatetat['etat_recherche'];
    $compterendu = $resultatetat['cr_forumM1'];
}
else{
    $etat = '';
    $compterendu = '';
}
?>
<div id="layoutSidenav_content"> <!-- body de page-->
    <main>
        <div class="container-fluid px-4"> <!-- div de page-->
            <h1 class="mt-4">Suivi Post Forum Stage</h1>
            
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Remplir le formulaire</li>
            </ol>



            <div class="card mb-4"> <!--div de section 1 -->
                <form id="dataglobal" method="post">
                    <div class="card-body"> <!--div de tableau 1 -->
                        <fieldset>


                <form id="tableperso" method="post">
                    <div class="card-body"> <!--div de tableau 1 -->
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Numéro de l'offre</th>
                                    <th>Entreprise</th>
                                    <th>Site</th>
                                    <th>Intitulé de l'offre</th>
                                    <th>Avez vous passé un entretien ?</th>
                                    <th>Avez vous reçu une proposition ?</th>
                                    <th>Compte-rendu de l'entretien</th>
                                </tr>
                            </thead>
                            <tbody>                                                    


                            <!-- requete pour récupérer le compte rendu de l'étudiant -->
                            <?php
                            $req = $bdd->prepare('SELECT cr_forumM1 FROM utilisateur WHERE idUtilisateur=?');
                            $req->execute(array($_SESSION['user']));
                            $resultat = $req->fetch();
                            $crglobal = $resultat['cr_forumM1'];
                            ?>

                            <div class="form-group">
                            <h4>Formulaire de suivi post forum-stage : </h4>

                            <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Indiquez votre état d'avancement au fur et à mesure (décrivez votre situation le plus précisément possible)</li>
                            </ol>
                            <textarea name="textCRglobal" id="textCRglobal" class="form-control" rows="3" required ><?php echo $crglobal;?></textarea>
                            </div>

                            
                            <br>
                                <?php
                                    $req = $bdd->prepare('SELECT offre.idOffre, nomEntreprise, nomSite, titre, entretien_passe, proposition_recue, cr_entretien 
                                                                FROM entreprise 
                                                                JOIN site on entreprise.idEntreprise = site.idEntreprise 
                                                                JOIN offre ON site.idSite = offre.idSite 
                                                                JOIN postule_m1 on offre.idOffre = postule_m1.idOffre 
                                                                WHERE postule_m1.idUtilisateur=? 
                                                                ORDER BY priorite ASC');
                                    $req->execute(array($_SESSION['user']));
                                    $resultat = $req->fetchAll();
                                    
                                    foreach ($resultat as $ligne) { ?>

                                <tr>
                                    <td><?php echo $ligne['idOffre']; ?></td>
                                    <td><?php echo $ligne['nomEntreprise']; ?></td>
                                    <td><?php echo $ligne['nomSite']; ?></td>
                                    <td><?php echo $ligne['titre']; ?></td>
                                    <td>
                                        <!-- en fonction du choix de l'étudiant on coche automatiquement  -->
                                        <?php if($ligne['entretien_passe'] == 1){
                                            $checkedoui = 'checked';}
                                            else{$checkedoui = '';}
                                            if($ligne['entretien_passe'] == 0){
                                                $checkednon = 'checked';}
                                                else{$checkednon = '';}
                                        ?>
                                        <input type="radio" name="entretien<?php echo $ligne['idOffre']; ?>" value="oui" <?php echo $checkedoui?> ><label for="oui">Oui</label><br><br>
                                        <input type="radio" name="entretien<?php echo $ligne['idOffre']; ?>" value="non" <?php echo $checkednon?> ><label for="non">Non</label>
                                    </td>
                                    <td>
                                        <!-- en fonction du choix de l'étudiant on coche automatiquement  -->
                                        <?php if($ligne['proposition_recue'] == 1){
                                            $checkedoui2 = 'checked';}
                                            else{$checkedoui2 = '';}
                                            if($ligne['proposition_recue'] == 0){
                                                $checkednon2 = 'checked';}
                                                else{$checkednon2 = '';}
                                        ?>
                                        <input type="radio" name="proposition<?php echo $ligne['idOffre']; ?>" value="oui" <?php echo $checkedoui2?> ><label for="non">Oui</label><br><br>
                                        <input type="radio" name="proposition<?php echo $ligne['idOffre']; ?>" value="non" <?php echo $checkednon2?> ><label for="non">Non</label>
                                    </td>
                                    <td>
                                        <textarea name="textAreaEntretien<?php echo $ligne['idOffre']; ?>"
                                        id="textAreaEntretien" class="form-control" rows="3"><?php echo $ligne['cr_entretien'];?></textarea></td> 
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        </fieldset>


                    </div> <!--fin div de tableau 1 -->
                    <input type="submit" class="btn btn-warning" name="Validersuivi" value="Valider">
                    <br>
                </form>

                <?php
                if (isset($_POST['Validersuivi'])) {

                    $id = $_SESSION['user'];

                    $req4 = $bdd->prepare('UPDATE utilisateur SET cr_forumM1 = ?  WHERE idUtilisateur = ?');
                    $req4->execute(array($_POST['textCRglobal'], $id));
                    $req4_reponse = $req4->fetch();

                    

                    //on fait une boucle pour passer sur tous les numéros d'offre
                    for ($i = 0; $i < 1000; $i++) {


                        //cas où l'étudiant a passé l'entretien
                        if (isset($_POST['entretien' . $i]) && $_POST['entretien' . $i] == 'oui') {

                            $entretienpasse = 1;
                    
                            $id = $_SESSION['user'];
                            $idoffre = $i;
        
                            $req4 = $bdd->prepare('UPDATE postule_m1 SET entretien_passe = ?  WHERE idUtilisateur = ? AND idOffre = ? ');
                            $req4->execute(array($entretienpasse, $id, $idoffre));
                            $req4_reponse = $req4->fetch();
        
                            //cas où l'étudiant a passé l'entretien + recu une proposition de stage
                            if (isset($_POST['proposition' . $i]) && $_POST['proposition' . $i] == 'oui'){

                                $propositionrecue = 1;
                                $etatrecherche = 'en attente';

                                $req5 = $bdd->prepare('UPDATE postule_m1 SET etat_recherche = ? ,proposition_recue = ?  WHERE idUtilisateur = ? AND idOffre = ? ');
                                $req5->execute(array($etatrecherche, $propositionrecue, $id, $idoffre));
                                $req5_reponse = $req5->fetch();
                                
                            }
                            //cas où l'étudiant a passé l'entretien mais n'a pas recu de proposition de stage
                            elseif(isset($_POST['proposition' . $i]) && $_POST['proposition' . $i] == 'non'){
            
                                $propositionrecue = 0;
                                $etatrecherche = 'pas de proposition';

                                $req6 = $bdd->prepare('UPDATE postule_m1 SET etat_recherche = ? ,proposition_recue = ?  WHERE idUtilisateur = ? AND idOffre = ? ');
                                $req6->execute(array($etatrecherche ,$propositionrecue,$id,$idoffre));
                                $req6_reponse = $req6->fetch();
                                
                            } 

                        }
                        //cas où l'étudiant n'a pas passé l'entretien (par défault il ne recois donc pas de proposition de stage)
                        elseif (isset($_POST['entretien' . $i]) && $_POST['entretien' . $i] == 'non'){

                            $entretienpasse = 0;

                            $id = $_SESSION['user'];
                            $idoffre = $i;
                            $etatrecherche = 'refuse';
                            $propositionrecue = 0;

                            $req7 = $bdd->prepare('UPDATE postule_m1 SET etat_recherche = ?, entretien_passe = ?, proposition_recue = ?  WHERE idUtilisateur = ? AND idOffre = ? ');
                            $req7->execute(array($etatrecherche, $entretienpasse, $propositionrecue,$id,$idoffre));
                            $req7_reponse = $req7->fetch();
                        }

                        //si l'étudiant à déjà saisie un compte rendu ou en saisi un 
                        if (isset($_POST['textAreaEntretien' . $i]) && $_POST['textAreaEntretien' . $i] !== ''){

                            $id = $_SESSION['user'];
                            $idoffre = $i;
                            $crentretien = $_POST['textAreaEntretien' . $i];

                            $req8 = $bdd->prepare('UPDATE postule_m1 SET cr_entretien = ? WHERE idUtilisateur = ? AND idOffre = ? ');
                            $req8->execute(array($crentretien,$id,$idoffre));
                            $req8_reponse = $req8->fetch();
                        }
                    }

                    //on appelle la fonction qui met à jour tous les états de l'étudiant
                    $id = $_SESSION['user'];
                    include('fonctionality/majetat.php');

                    //on actualise automatiquement la page pour ré-afficher les nouvelles données 
                    echo "<script>window.location.replace(\"suivi_forum.php\")</script>";
                }
                ?>
            </div> <!--fin div de section 1 -->
                
<!----------------------------Footer------------------------------------------->

        </div> <!-- fin div de page-->

    </main>
    <?php
        include('fonctionality/footer.php');
    ?>
</div> <!-- fin body de page-->
</body>
</html>