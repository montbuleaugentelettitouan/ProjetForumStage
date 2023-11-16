<?php
include('annee+promo.php');

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}



    if (isset($_POST['Valideraccepte'])) {
        //Partie Utilisateur

        //on récupère l'id et d'autres variables utiles pour la suite
        $id = $_SESSION['user'];
        $accepte = "accepte";

        //On modifie l'état de l'utilisateur à "accepte"
        //On regarde en premier si l'état de l'utilisateur est déjà à accepté ou pas 
        $req = $bdd->prepare('SELECT etat FROM utilisateur WHERE idUtilisateur = ?');
        $req->execute(array($id));
        $resultReq = $req->fetch();
        //Si l'état n'est pas à "accepte" alors on le change sinon on le laisse 
        if($resultReq != $accepte){
            $req = $bdd->prepare('UPDATE utilisateur SET etat = ? WHERE idUtilisateur = ?');
            $req->execute(array($accepte, $id));
            }


        //section avec offres pré-sélectionnées
        if ($_POST['inlineRadioOptions'] != "new") {
            $offre=$_POST['inlineRadioOptions'];

            $recupS = $bdd->prepare('SELECT nomSite, site.idSite FROM site JOIN offre_stage on site.idSite = offre_stage.idSite WHERE idOffre = ?');
            $recupS->execute(array($offre));
            $resultRecup = $recupS->fetch();

            $Idsite = !empty($resultRecup['idSite']) ? $resultRecup['idSite'] : NULL ;
            //test des variables
            /*echo $id;
            echo $accepte;
            echo $offre;
            echo $Idsite;*/

            // On cherche si l'étudiant est dans la table stage
            $requetesearch1 = $bdd->prepare('SELECT * FROM stage WHERE idUtilisateur=?');
            $requetesearch1->execute(array($id));
            $resultat1 = $requetesearch1->fetch();
            $count1 = $requetesearch1->rowcount();

            //si etudiant dans la table stage alors on actualise les informations et on vérifie que état est bien à accepté
            if ($count1 != 0) {

                //permet de récupérer le nombre de stage pourvu sur l'offre
                $requetesearch15 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                $requetesearch15->execute(array($id));
                $resultatStagePourvu =$requetesearch15->fetch();
				// mise a jour du stage pourvu en enlevant 1 pour celui qui été précédemment sélctionné.
                $resultatStagePourvu1 = $resultatStagePourvu['stage_pourvu'];
                $newresultatStagePourvu1 = $resultatStagePourvu1 - 1;

                $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                $requetesearch16->execute(array($id));
                $AncienneOffreAccepte = $requetesearch16->fetch();
                $AncienneOffreAccepte = $AncienneOffreAccepte['idOffre'];


                $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                $requeteupdate3->execute(array($newresultatStagePourvu1, $AncienneOffreAccepte));

                $requeteupdate1 = $bdd->prepare("UPDATE stage SET idOffre = ?, idSite = ? WHERE idUtilisateur = ?");
                $requeteupdate1->execute(array($offre,$Idsite,$id));

                //$requeteupdate2 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                //$requeteupdate2->execute(array($accepte,$id));

                $requetesearch17 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                $requetesearch17->execute(array($id));
                $resultatStagePourvu =$requetesearch17->fetch();
				// mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                $resultatStagePourvu2 = $resultatStagePourvu['stage_pourvu'];
                $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                $requetesearch16->execute(array($id));
                $NouvelleOffreAccepte = $requetesearch16->fetch();
                $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));

            }
            //sinon on vérifie que l'état est à "accepte" et on insert dans la table stage
            else{

                $requeteinsert1 = $bdd->prepare("INSERT INTO stage (type_contrat, idUtilisateur, idOffre, idSite, annee_stage) VALUES (?,?,?,?,?)");
                $requeteinsert1->execute(array('stage',$id,$offre,$Idsite, $annee));

                //$requeteupdate3 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                //$requeteupdate3->execute(array($accepte,$id));

                $requetesearch17 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                $requetesearch17->execute(array($id));
                $resultatStagePourvu =$requetesearch17->fetch();
				// mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                $resultatStagePourvu2 = $resultatStagePourvu['stage_pourvu'];
                $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                $requetesearch16->execute(array($id));
                $NouvelleOffreAccepte = $requetesearch16->fetch();
                $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
            }

            //on modifie la table postule pour mettre dans la ligne du stage accepté le champs proposition_acceptee à 1
            //si l'étudiant avait renseigner un autre stage accepte il faut modifier l'ancien à 0

            //On reset le champs proposition_acceptee à 0
            $req2 = $bdd->prepare('UPDATE postule SET proposition_acceptee = ? WHERE idUtilisateur = ?');
            $req2->execute(array(0,$id));
            //On passe le champs proposition_acceptee à 1
            $req3 = $bdd->prepare('UPDATE postule SET proposition_acceptee = ? WHERE idUtilisateur = ? AND idOffre = ?');
            $req3->execute(array(1,$id, $offre));

            //on met à jour l'état de la recherche dans la table postule en fonction du stage accepté
            include('fonctionality/majetat.php');
        }

        //AUTRE QUE FORUM STAGE

        //si stage autre que forum stage sélectionné on récupère les données du formulaire
        else {
            $newposte = $_POST['nomposte'];
            $newposte = ucfirst($newposte);

            $newentreprise = $_POST['nomentreprise'];
            $newentreprise=strtoupper($newentreprise);

            $newdescription = $_POST['nomdescription'];
            $newdescription = ucfirst($newdescription);

            $newsite = $_POST['nomsite'];
            $newsite=strtoupper($newsite);

            $newville = $_POST['Ville'];
            $newville = ucfirst($newville);
            //test des variables
            /*echo $newposte;
            echo $newentreprise;
            echo $newdescription;
            echo $newsite;
            echo $newville;
            echo $id;*/

            //on cherche si l'offre est déjà dans la table offre_stage
            $requetesearch2 = $bdd->prepare('SELECT offre_stage.idOffre, titre, offre_stage.idSite FROM offre_stage JOIN site on offre_stage.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise WHERE titre=? and nomSite=? and nomEntreprise = ?');
            $requetesearch2->execute(array($newposte,$newsite,$newentreprise));
            $resultat2 = $requetesearch2->fetch();
            $count2 = $requetesearch2->rowCount();

            //si l'offre existe déjà, alors récupération de l'idOffre et l'idSite
            if ($count2 != 0) {
                $newidSite = !empty($resultat2['idSite']) ? $resultat2['idSite'] : NULL ;
                $newidOffre = !empty($resultat2['idOffre']) ? $resultat2['idOffre'] : NULL ;
                //test des variables
                /*echo $newidSite;
                echo $newidOffre;*/

                //on cherche si étu existe dans la table stage
                $requetesearch3 = $bdd->prepare('SELECT * FROM stage WHERE idUtilisateur=?');
                $requetesearch3->execute(array($id));
                $resultat3 = $requetesearch3->fetch();
                $count3 = $requetesearch3->rowcount();

                //si etudiant dans la table stage alors on actualise les informations et on vérifie que état est bien à accepté en ajoutant la nouvelle offre
                if ($count3 != 0) {

                    $requetesearch15 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                    $requetesearch15->execute(array($id));
                    $resultatStagePourvu =$requetesearch15->fetch();
					// mise a jour du stage pourvu en enlevant 1 pour celui qui été précédemment sélctionné.
                    $resultatStagePourvu1 = $resultatStagePourvu['stage_pourvu'];
                    $newresultatStagePourvu1 = $resultatStagePourvu1 - 1;

                    $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                    $requetesearch16->execute(array($id));
                    $AncienneOffreAccepte = $requetesearch16->fetch();
                    $AncienneOffreAccepte = $AncienneOffreAccepte['idOffre'];

                    $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                    $requeteupdate3->execute(array($newresultatStagePourvu1, $AncienneOffreAccepte));

                    $requeteupdate4 = $bdd->prepare("UPDATE stage SET idOffre = ?, idSite = ? WHERE idUtilisateur = ?");
                    $requeteupdate4->execute(array($newidOffre,$newidSite,$id));

                    //$requeteupdate5 = $bdd->prepare("UPDATE utilisateur SET etat = ? WHERE idUtilisateur = ?");
                    //$requeteupdate5->execute(array($accepte,$id));

                    $requetesearch17 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                    $requetesearch17->execute(array($id));
                    $resultatStagePourvu =$requetesearch17->fetch();
					// mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                    $resultatStagePourvu2 = $resultatStagePourvu['stage_pourvu'];
                    $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                    $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                    $requetesearch16->execute(array($id));
                    $NouvelleOffreAccepte = $requetesearch16->fetch();
                    $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                    $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                    $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
                }
                //sinon on vérifie que l'état est à "accepte" et on insert dans la table stage
                else{
                    $requeteinsert2 = $bdd->prepare("INSERT INTO stage (type_contrat, idUtilisateur, idOffre, idSite, annee_stage) VALUES (?,?,?,?,?)");
                    $requeteinsert2->execute(array('stage',$id,$newidOffre,$newidSite, $annee));

                    //$requeteupdate6 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                    //$requeteupdate6->execute(array($accepte,$id));

                    $requetesearch17 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                    $requetesearch17->execute(array($id));
                    $resultatStagePourvu =$requetesearch17->fetch();
					// mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                    $resultatStagePourvu2 = $resultatStagePourvu['stage_pourvu'];
                    $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                    $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                    $requetesearch16->execute(array($id));
                    $NouvelleOffreAccepte = $requetesearch16->fetch();
                    $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                    $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                    $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
                }
            //sinon, on vérifie si l'entreprise existe
            }else {
                $requetesearch4 = $bdd->prepare('SELECT * FROM entreprise WHERE nomEntreprise = ?');
                $requetesearch4->execute(array($newentreprise));
                $resultat4 = $requetesearch4->fetch();
                $count4 = $requetesearch4->rowCount();

                    if ($count4 !=0) {
                        $newidEnt = !empty($resultat4['idEntreprise']) ? $resultat4['idEntreprise'] : NULL ;
                        //test des variables
                        //echo $newidEnt;

                        // on vérifie si le site existe
                        $requetesearch5 = $bdd->prepare('SELECT * FROM site WHERE nomSite =? and ville =? and idEntreprise = ?');
                        $requetesearch5->execute(array($newidEnt));
                        $resultat5 = $requetesearch5->fetch();
                        $count5 = $requetesearch5->rowCount();

                        if ($count5 ==0 ) {
                            //s'il n'existe pas on l'ajoute et on récupère l'id
                            $requeinsert3 = $bdd->prepare("INSERT INTO site (nomsite, ville, idEntreprise) VALUES (?,?,?)");
                            $requeinsert3->execute(array($newsite,$newville,$newidEnt));

                            $requetesearch6 = $bdd->prepare('SELECT * FROM site WHERE nomSite = ? and ville = ? and idEntreprise = ?');
                            $requetesearch6->execute(array($newsite,$newville,$newidEnt));
                            $resultat6 = $requetesearch6->fetch();

                            $newidSite = !empty($resultat6['idSite']) ? $resultat6['idSite'] : NULL ;

                            //ajout dans la table offre et récupération de l'id

                            $requeinsert4 = $bdd->prepare("INSERT INTO offre_stage (titre, description, NbPoste, idSite,stage_pourvu, annee) VALUES (?,?,?,?,?,?)");
                            $requeinsert4->execute(array($newposte,$newdescription,1,$newidSite,0, $annee));

                            $requetesearch7 = $bdd->prepare('SELECT * FROM offre_stage WHERE titre = ? and idSite = ?');
                            $requetesearch7->execute(array($newposte,$newidSite));
                            $resultat7 = $requetesearch7->fetch();

                            $newidOffre = !empty($resultat7['idOffre']) ? $resultat7['idOffre'] : NULL ;

                            //on cherche si étu existe dans la table stage
                            $requetesearch8 = $bdd->prepare('SELECT * FROM Stage WHERE idUtilisateur=?');
                            $requetesearch8->execute(array($id));
                            $resultat8 = $requetesearch8->fetch();
                            $count8 = $requetesearch8->rowcount();

                            //si etudiant dans la table stage alors on actualise les informations et on vérifie que état est bien à accepté
                            if ($count8 != 0) {

                                $requetesearch15 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch15->execute(array($id));
                                $resultatStagePourvu =$requetesearch15->fetch();
								// mise a jour du stage pourvu en enlevant 1 pour celui qui été précédemment sélctionné.
                                $resultatStagePourvu1 = $resultatStagePourvu['stage_pourvu'];
                                $newresultatStagePourvu1 = $resultatStagePourvu1 - 1;

                                $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch16->execute(array($id));
                                $AncienneOffreAccepte = $requetesearch16->fetch();
                                $AncienneOffreAccepte = $AncienneOffreAccepte['idOffre'];

                                $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                                $requeteupdate3->execute(array($newresultatStagePourvu1, $AncienneOffreAccepte));

                                $requeteupdate7 = $bdd->prepare("UPDATE stage SET idOffre = ?, idSite = ? WHERE idUtilisateur = ?");
                                $requeteupdate7->execute(array($newidOffre,$newidSite,$id));

                                //$requeteupdate8 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                                //$requeteupdate8->execute(array($accepte,$id));

                                $requetesearch17 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch17->execute(array($id));
                                $resultatStagePourvu =$requetesearch17->fetch();
								// mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                                $resultatStagePourvu2 = $resultatStagePourvu['stage_pourvu'];
                                $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                                $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch16->execute(array($id));
                                $NouvelleOffreAccepte = $requetesearch16->fetch();
                                $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                                $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                                $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
                            }
                            //sinon on vérifie que l'état est à "accepte" et on insert dans la table stage
                            else{
                                $requeteinsert5 = $bdd->prepare("INSERT INTO stage (type_contrat, idUtilisateur, idOffre, idSite, annee_stage) VALUES (?,?,?,?,?)");
                                $requeteinsert5->execute(array('stage',$id,$newidOffre,$newidSite,$annee));

                                //$requeteupdate9 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                                //$requeteupdate9->execute(array($accepte,$id));

                                $requetesearch17 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch17->execute(array($id));
                                $resultatStagePourvu =$requetesearch17->fetch();
								// mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                                $resultatStagePourvu2 = $resultatStagePourvu['stage_pourvu'];
                                $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                                $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch16->execute(array($id));
                                $NouvelleOffreAccepte = $requetesearch16->fetch();
                                $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                                $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                                $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
                            }
                        }else{
                            $newidSite = !empty($resultat5['idSite']) ? $resultat5['idSite'] : NULL ;

                            //ajout dans la table offre et récupération de l'id

                            $requeinsert6 = $bdd->prepare("INSERT INTO offre_stage (titre, description, NbPoste, idSite, stage_pourvu) VALUES (?,?,?,?,?)");
                            $requeinsert6->execute(array($newposte,$newdescription,1,$newidSite,0));

                            $requetesearch9 = $bdd->prepare('SELECT * FROM offre_stage WHERE titre = ? and idSite = ?');
                            $requetesearch9->execute(array($newposte,$newidSite));
                            $resultat9 = $requetesearch9->fetch();

                            $newidOffre = !empty($resultat9['idOffre']) ? $resultat9['idOffre'] : NULL ;

                            //on cherche si étu existe dans la table stage
                            $requetesearch10 = $bdd->prepare('SELECT * FROM stage WHERE idUtilisateur=?');
                            $requetesearch10->execute(array($id));
                            $resultat10 = $requetesearch10->fetch();
                            $count10 = $requetesearch10->rowcount();

                            //si etudiant dans la table stage alors on actualise les informations et on vérifie que état est bien à accepté
                            if ($count10 != 0) {

                                $requetesearch15 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch15->execute(array($id));
                                $resultatStagePourvu =$requetesearch15->fetch();
								// mise a jour du stage pourvu en enlevant 1 pour celui qui été précédemment sélctionné.
                                $resultatStagePourvu1 = $resultatStagePourvu['stage_pourvu'];
                                $newresultatStagePourvu1 = $resultatStagePourvu1 - 1;

                                $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch16->execute(array($id));
                                $AncienneOffreAccepte = $requetesearch16->fetch();
                                $AncienneOffreAccepte = $AncienneOffreAccepte['idOffre'];

                                $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                                $requeteupdate3->execute(array($newresultatStagePourvu1, $AncienneOffreAccepte));

                                $requeteupdate10 = $bdd->prepare("UPDATE stage SET idOffre = ?, idSite = ? WHERE idUtilisateur = ?");
                                $requeteupdate10->execute(array($newidOffre,$newidSite,$id));

                                //$requeteupdate11 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                                //$requeteupdate11->execute(array($accepte,$id));

                                $requetesearch17 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch17->execute(array($id));
                                $resultatStagePourvu =$requetesearch17->fetch();
								// mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                                $resultatStagePourvu2 = $resultatStagePourvu['stage_pourvu'];
                                $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                                $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch16->execute(array($id));
                                $NouvelleOffreAccepte = $requetesearch16->fetch();
                                $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                                $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                                $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
                            }
                            //sinon on vérifie que l'état est à "accepte" et on insert dans la table stage
                            else{
                                $requeteinsert7 = $bdd->prepare("INSERT INTO stage (type_contrat, idUtilisateur, idOffre, idSite,annee_stage) VALUES (?,?,?,?,?)");
                                $requeteinsert7->execute(array('stage',$id,$newidOffre,$newidSite, $annee));

                                //$requeteupdate12 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                                //$requeteupdate12->execute(array($accepte,$id));

                                $requetesearch17 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch17->execute(array($id));
                                $resultatStagePourvu =$requetesearch17->fetch();
								// mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                                $resultatStagePourvu2 = $resultatStagePourvu['stage_pourvu'];
                                $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                                $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                                $requetesearch16->execute(array($id));
                                $NouvelleOffreAccepte = $requetesearch16->fetch();
                                $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                                $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                                $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
                            }
                        }
                    }else{
                        //si l'entreprise n'existe pas
                        //ajout de l'entreprise et récupération de son id
                        $requeinsert8 = $bdd->prepare("INSERT INTO entreprise (nomEntreprise) VALUES (?)");
                        $requeinsert8->execute(array($newentreprise));

                        $requetesearch11 = $bdd->prepare('SELECT * FROM entreprise WHERE nomEntreprise = ?');
                        $requetesearch11->execute(array($newentreprise));
                        $resultat11 = $requetesearch11->fetch();

                        $newidEnt = !empty($resultat11['idEntreprise']) ? $resultat11['idEntreprise'] : NULL ;
                        //test de variable
                        //echo $newidEnt;

                        //ajout du site et récupération de l'id
                        $requeinsert9 = $bdd->prepare("INSERT INTO site (nomsite, ville, idEntreprise) VALUES (?,?,?)");
                        $requeinsert9->execute(array($newsite,$newville,$newidEnt));

                        $requetesearch12 = $bdd->prepare('SELECT * FROM site WHERE nomSite = ? and ville = ? and idEntreprise = ?');
                        $requetesearch12->execute(array($newsite,$newville,$newidEnt));
                        $resultat12 = $requetesearch12->fetch();

                        $newidSite = !empty($resultat12['idSite']) ? $resultat12['idSite'] : NULL ;

                        //ajout dans la table offre et récupération de l'id

                        $requeinsert10 = $bdd->prepare("INSERT INTO offre_stage (titre, description, NbPoste, idSite,stage_pourvu, annee) VALUES (?,?,?,?,?,?)");
                        $requeinsert10->execute(array($newposte,$newdescription,1,$newidSite,0, $annee));

                        $requetesearch13 = $bdd->prepare('SELECT * FROM offre_stage WHERE titre = ? and idSite = ?');
                        $requetesearch13->execute(array($newposte,$newidSite));
                        $resultat13 = $requetesearch13->fetch();

                        $newidOffre = !empty($resultat13['idOffre']) ? $resultat13['idOffre'] : NULL ;

                        //on cherche si étu existe dans la table stage
                        $requetesearch14 = $bdd->prepare('SELECT * FROM stage WHERE idUtilisateur=?');
                        $requetesearch14->execute(array($id));
                        $resultat14 = $requetesearch14->fetch();
                        $count14 = $requetesearch14->rowcount();

                        //si etudiant dans la table stage alors on actualise les informations et on vérifie que état est bien à accepté
                        if ($count14 != 0) {

                            $requetesearch15 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                            $requetesearch15->execute(array($id));
                            $resultatStagePourvu =$requetesearch15->fetch();
							// mise a jour du stage pourvu en enlevant 1 pour celui qui été précédemment sélctionné.
                            $resultatStagePourvu1 = $resultatStagePourvu['stage_pourvu'];
                            $newresultatStagePourvu1 = $resultatStagePourvu1 - 1;

                            $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                            $requetesearch16->execute(array($id));
                            $AncienneOffreAccepte = $requetesearch16->fetch();
                            $AncienneOffreAccepte = $AncienneOffreAccepte['idOffre'];

                            $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                            $requeteupdate3->execute(array($newresultatStagePourvu1, $AncienneOffreAccepte));

                            $requeteupdate13 = $bdd->prepare("UPDATE stage SET idOffre = ?, idSite = ? WHERE idUtilisateur = ?");
                            $requeteupdate13->execute(array($newidOffre,$newidSite,$id));

                            //$requeteupdate14 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                            //$requeteupdate14->execute(array($accepte,$id));

                            $requetesearch17 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                            $requetesearch17->execute(array($id));
                            $resultatStagePourvu =$requetesearch17->fetch();
							// mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                            $resultatStagePourvu2 = $resultatStagePourvu['stage_pourvu'];
                            $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                            $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                            $requetesearch16->execute(array($id));
                            $NouvelleOffreAccepte = $requetesearch16->fetch();
                            $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                            $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                            $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
                        }
                        //sinon on vérifie que l'état est à "accepte" et on insert dans la table stage
                        else{
                            $requeteinsert11 = $bdd->prepare("INSERT INTO stage (type_contrat, idUtilisateur, idOffre, idSite, annee_stage) VALUES (?,?,?,?,?)");
                            $requeteinsert11->execute(array('stage',$id,$newidOffre,$newidSite, $annee));

                            //$requeteupdate15 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                            //$requeteupdate15->execute(array($accepte,$id));

                            $requetesearch17 =$bdd->prepare("select stage_pourvu from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                            $requetesearch17->execute(array($id));
                            $resultatStagePourvu =$requetesearch17->fetch();
							// mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                            $resultatStagePourvu2 = $resultatStagePourvu['stage_pourvu'];
                            $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                            $requetesearch16 =$bdd->prepare("select idOffre from offre_stage join stage using (idOffre) where idUtilisateur = ?");
                            $requetesearch16->execute(array($id));
                            $NouvelleOffreAccepte = $requetesearch16->fetch();
                            $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                            $requeteupdate3 = $bdd->prepare("UPDATE offre_stage set stage_pourvu = ? where idOffre = ?");
                            $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
                        }
                    }
            }
        }
    echo "<script>window.location.replace(\"stage_accepte.php\")</script>";
    }