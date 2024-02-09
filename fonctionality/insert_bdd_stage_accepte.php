<?php
session_start();
include('annee+promo.php');
include('bdd.php');

if (isset($_POST['Valideraccepte'])) {
    //Partie Utilisateur

    //on récupère l'id et d'autres variables utiles pour la suite
    $id = $_SESSION['user'];
    $accepte = "accepte";

    //On modifie l'état de l'utilisateur à "accepte"
    //On regarde en premier si l'état de l'utilisateur est déjà à accepté ou pas
    $req = $bdd->prepare('SELECT etatC FROM utilisateur WHERE idUtilisateur = ? AND etatC = "accepte"');
    $req->execute(array($id));
    $resultReq = $req->fetch();

    //Si l'état n'est pas à "accepte" alors on le change sinon on le laisse
    if(!$resultReq) {
        // Si l'état n'est pas à "accepte", alors on le change
        $req = $bdd->prepare('UPDATE utilisateur SET etatC = ? WHERE idUtilisateur = ?');
        $req->execute(array($accepte, $id));
    }
}


//section avec offres pré-sélectionnées
if ($_POST['inlineRadioOptions'] != "new") {
    $offre=$_POST['inlineRadioOptions'];

    $recupS = $bdd->prepare('SELECT nomSite, site.idSite FROM site JOIN offre on site.idSite = offre.idSite WHERE idOffre = ?');
    $recupS->execute(array($offre));
    $resultRecup = $recupS->fetch();

    $Idsite = !empty($resultRecup['idSite']) ? $resultRecup['idSite'] : NULL ;

    // On cherche si l'étudiant est dans la table convention_contrat, c'est-à-dire si il a accepté une offre
    $requetesearch1 = $bdd->prepare('SELECT * FROM convention_contrat WHERE idUtilisateur=?');
    $requetesearch1->execute(array($id));
    $resultat1 = $requetesearch1->fetch();
    $count1 = $requetesearch1->rowcount();

    //si etudiant dans la table stage alors on actualise les informations et on vérifie que état est bien à accepté
    if ($count1 != 0) {

        //permet de récupérer le nombre de stage pourvu sur l'offre
        $requetesearch15 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
        $requetesearch15->execute(array($id));
        $resultatStagePourvu =$requetesearch15->fetch();
        // mise a jour du stage pourvu en enlevant 1 pour celui qui été précédemment sélctionné.
        $resultatStagePourvu1 = $resultatStagePourvu['nbPostePourvu'];
        $newresultatStagePourvu1 = $resultatStagePourvu1 - 1;

        $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
        $requetesearch16->execute(array($id));
        $AncienneOffreAccepte = $requetesearch16->fetch();
        $AncienneOffreAccepte = $AncienneOffreAccepte['idOffre'];

        $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
        $requeteupdate3->execute(array($newresultatStagePourvu1, $AncienneOffreAccepte));

        $requeteupdate1 = $bdd->prepare("UPDATE convention_contrat SET idOffre = ? WHERE idUtilisateur = ?");
        $requeteupdate1->execute(array($offre,$id));

        //$requeteupdate2 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
        //$requeteupdate2->execute(array($accepte,$id));

        $requetesearch17 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
        $requetesearch17->execute(array($id));
        $resultatStagePourvu =$requetesearch17->fetch();
        // mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
        $resultatStagePourvu2 = $resultatStagePourvu['nbPostePourvu'];
        $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

        $requetesearch16 =$bdd->prepare("select idOffre from convention_contrat where idUtilisateur = ?");
        $requetesearch16->execute(array($id));
        $NouvelleOffreAccepte = $requetesearch16->fetch();
        $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

        $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
        $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
    }
    //sinon on vérifie que l'état est à "accepte" et on insert dans la table stage
    else{

        $requeteinsert1 = $bdd->prepare("INSERT INTO convention_contrat (type_contrat, idUtilisateur, idOffre) VALUES (?,?,?)");
        $requeteinsert1->execute(array('stage',$id,$offre));

        //$requeteupdate3 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
        //$requeteupdate3->execute(array($accepte,$id));

        $requetesearch17 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
        $requetesearch17->execute(array($id));
        $resultatStagePourvu =$requetesearch17->fetch();
        // mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
        $resultatStagePourvu2 = $resultatStagePourvu['nbPostePourvu'];
        $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

        $requetesearch16 =$bdd->prepare("select idOffre from convention_contrat where idUtilisateur = ?");
        $requetesearch16->execute(array($id));
        $NouvelleOffreAccepte = $requetesearch16->fetch();
        $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

        $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
        $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
    }

    //on modifie la table postule pour mettre dans la ligne du stage accepté le champs proposition_acceptee à 1
    //si l'étudiant avait renseigner un autre stage accepte il faut modifier l'ancien à 0

    //On reset le champs proposition_acceptee à 0
    $req2 = $bdd->prepare('UPDATE postule_m1 SET proposition_acceptee = ? WHERE idUtilisateur = ?');
    $req2->execute(array(0,$id));
    //On passe le champs proposition_acceptee à 1
    $req3 = $bdd->prepare('UPDATE postule_m1 SET proposition_acceptee = ? WHERE idUtilisateur = ? AND idOffre = ?');
    $req3->execute(array(1,$id, $offre));

    //on met à jour l'état de la recherche dans la table postule en fonction du stage accepté
    include('majetat.php');
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

    $newpays = $_POST['Pays'];
    $newpays=strtoupper($newsite);

    $newmail = $_POST['contact'];

    $newsecteur = $_POST['Secteur'];
    $newsite=strtoupper($newsite);

    //on cherche si l'offre est déjà dans la table 'Offre'
    $requetesearch2 = $bdd->prepare('SELECT offre.idOffre, titre, offre.idSite FROM offre JOIN site on offre.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise WHERE titre=? and nomSite=? and nomEntreprise = ?');
    $requetesearch2->execute(array($newposte,$newsite,$newentreprise));
    $resultat2 = $requetesearch2->fetch();
    $count2 = $requetesearch2->rowCount();

    //si l'offre existe déjà, alors récupération de l'idOffre et l'idSite
    if ($count2 != 0) {
        $newidSite = !empty($resultat2['idSite']) ? $resultat2['idSite'] : NULL ;
        $newidOffre = !empty($resultat2['idOffre']) ? $resultat2['idOffre'] : NULL ;

        //on cherche si étu existe dans la table stage
        $requetesearch3 = $bdd->prepare('SELECT * FROM convention_contrat WHERE idUtilisateur=?');
        $requetesearch3->execute(array($id));
        $resultat3 = $requetesearch3->fetch();
        $count3 = $requetesearch3->rowcount();

        //si etudiant dans la table stage alors on actualise les informations et on vérifie que état est bien à accepté en ajoutant la nouvelle offre
        if ($count3 != 0) {

            $requetesearch15 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
            $requetesearch15->execute(array($id));
            $resultatStagePourvu =$requetesearch15->fetch();
            // mise a jour du stage pourvu en enlevant 1 pour celui qui été précédemment sélctionné.
            $resultatStagePourvu1 = $resultatStagePourvu['nbPostePourvu'];
            $newresultatStagePourvu1 = $resultatStagePourvu1 - 1;

            $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
            $requetesearch16->execute(array($id));
            $AncienneOffreAccepte = $requetesearch16->fetch();
            $AncienneOffreAccepte = $AncienneOffreAccepte['idOffre'];

            $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
            $requeteupdate3->execute(array($newresultatStagePourvu1, $AncienneOffreAccepte));

            $requeteupdate4 = $bdd->prepare("UPDATE convention_contrat SET idOffre = ? WHERE idUtilisateur = ?");
            $requeteupdate4->execute(array($newidOffre,$id));

            $requetesearch17 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
            $requetesearch17->execute(array($id));
            $resultatStagePourvu =$requetesearch17->fetch();
            // mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
            $resultatStagePourvu2 = $resultatStagePourvu['nbPostePourvu'];
            $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

            $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
            $requetesearch16->execute(array($id));
            $NouvelleOffreAccepte = $requetesearch16->fetch();
            $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

            $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
            $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
        }
        //sinon on vérifie que l'état est à "accepte" et on insert dans la table 'convention_contrat'
        else{
            $requeteinsert2 = $bdd->prepare("INSERT INTO convention_contrat (type_contrat, idUtilisateur, idOffre) VALUES (?,?,?)");
            $requeteinsert2->execute(array('stage',$id,$newidOffre));

            $requetesearch17 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
            $requetesearch17->execute(array($id));
            $resultatStagePourvu =$requetesearch17->fetch();
            // mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
            $resultatStagePourvu2 = $resultatStagePourvu['nbPostePourvu'];
            $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

            $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
            $requetesearch16->execute(array($id));
            $NouvelleOffreAccepte = $requetesearch16->fetch();
            $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

            $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
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

            // on vérifie si le site existe
            $requetesearch5 = $bdd->prepare('SELECT * FROM site WHERE nomSite =? and ville =? and idEntreprise = ?');
            $requetesearch5->execute(array($newsite,$newville,$newidEnt));
            $resultat5 = $requetesearch5->fetch();
            $count5 = $requetesearch5->rowCount();

            if ($count5 ==0 ) {
                //s'il n'existe pas on l'ajoute et on récupère l'id
                $requeinsert3 = $bdd->prepare("INSERT INTO site (nomsite, ville, idEntreprise, pays) VALUES (?,?,?,?)");
                $requeinsert3->execute(array($newsite,$newville,$newidEnt,$newpays));

                $requetesearch6 = $bdd->prepare('SELECT * FROM site WHERE nomSite = ? and ville = ? and idEntreprise = ?');
                $requetesearch6->execute(array($newsite,$newville,$newidEnt));
                $resultat6 = $requetesearch6->fetch();

                $newidSite = !empty($resultat6['idSite']) ? $resultat6['idSite'] : NULL ;

                //ajout dans la table offre et récupération de l'id

                $requeinsert4 = $bdd->prepare("INSERT INTO offre (titre, description, nbPoste, idSite, nbPostePourvu, anneeO, parcours, niveau, mailContact, secteur, valider) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                $requeinsert4->execute(array($newposte,$newdescription,1,$newidSite,0,$annee,'GPhy','M1',$newmail,$newsecteur,1));

                $requetesearch7 = $bdd->prepare('SELECT * FROM offre WHERE titre = ? and idSite = ?');
                $requetesearch7->execute(array($newposte,$newidSite));
                $resultat7 = $requetesearch7->fetch();

                $newidOffre = !empty($resultat7['idOffre']) ? $resultat7['idOffre'] : NULL ;

                //on cherche si étu existe dans la table stage
                $requetesearch8 = $bdd->prepare('SELECT * FROM convention_contrat WHERE idUtilisateur=?');
                $requetesearch8->execute(array($id));
                $resultat8 = $requetesearch8->fetch();
                $count8 = $requetesearch8->rowcount();

                //si etudiant dans la table stage alors on actualise les informations et on vérifie que état est bien à accepté
                if ($count8 != 0) {

                    $requetesearch15 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch15->execute(array($id));
                    $resultatStagePourvu =$requetesearch15->fetch();
                    // mise a jour du stage pourvu en enlevant 1 pour celui qui été précédemment sélctionné.
                    $resultatStagePourvu1 = $resultatStagePourvu['nbPostePourvu'];
                    $newresultatStagePourvu1 = $resultatStagePourvu1 - 1;

                    $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch16->execute(array($id));
                    $AncienneOffreAccepte = $requetesearch16->fetch();
                    $AncienneOffreAccepte = $AncienneOffreAccepte['idOffre'];

                    $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
                    $requeteupdate3->execute(array($newresultatStagePourvu1, $AncienneOffreAccepte));

                    $requeteupdate7 = $bdd->prepare("UPDATE convention_contrat SET idOffre = ? WHERE idUtilisateur = ?");
                    $requeteupdate7->execute(array($newidOffre,$id));

                    //$requeteupdate8 = $bdd->prepare("UPDATE postule_m1 SET etat_recherche = ? WHERE idUtilisateur = ?");
                    //$requeteupdate8->execute(array($accepte,$id));

                    $requetesearch17 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch17->execute(array($id));
                    $resultatStagePourvu =$requetesearch17->fetch();
                    // mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                    $resultatStagePourvu2 = $resultatStagePourvu['nbPostePourvu'];
                    $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                    $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch16->execute(array($id));
                    $NouvelleOffreAccepte = $requetesearch16->fetch();
                    $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                    $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
                    $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
                }
                //sinon on vérifie que l'état est à "accepte" et on insert dans la table stage
                else{
                    $requeteinsert5 = $bdd->prepare("INSERT INTO convention_contrat (type_contrat, idUtilisateur, idOffre) VALUES (?,?,?)");
                    $requeteinsert5->execute(array('stage',$id,$newidOffre));

                    //$requeteupdate9 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                    //$requeteupdate9->execute(array($accepte,$id));

                    $requetesearch17 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch17->execute(array($id));
                    $resultatStagePourvu =$requetesearch17->fetch();
                    // mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                    $resultatStagePourvu2 = $resultatStagePourvu['nbPostePourvu'];
                    $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                    $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch16->execute(array($id));
                    $NouvelleOffreAccepte = $requetesearch16->fetch();
                    $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                    $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
                    $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
                }
            }else{
                $newidSite = !empty($resultat5['idSite']) ? $resultat5['idSite'] : NULL ;

                //ajout dans la table offre et récupération de l'id

                $requeinsert6 = $bdd->prepare("INSERT INTO offre (titre, description, nbPoste, idSite, nbPostePourvu, anneeO, parcours, niveau, mailContact, secteur, valider) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                $requeinsert6->execute(array($newposte,$newdescription,1,$newidSite,0,$annee,'GPhy','M1',$newmail,$newsecteur,1));

                $requetesearch9 = $bdd->prepare('SELECT * FROM offre WHERE titre = ? and idSite = ?');
                $requetesearch9->execute(array($newposte,$newidSite));
                $resultat9 = $requetesearch9->fetch();

                $newidOffre = !empty($resultat9['idOffre']) ? $resultat9['idOffre'] : NULL ;

                //on cherche si étu existe dans la table stage
                $requetesearch10 = $bdd->prepare('SELECT * FROM convention_contrat WHERE idUtilisateur=?');
                $requetesearch10->execute(array($id));
                $resultat10 = $requetesearch10->fetch();
                $count10 = $requetesearch10->rowcount();

                //si etudiant dans la table stage alors on actualise les informations et on vérifie que état est bien à accepté
                if ($count10 != 0) {

                    $requetesearch15 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch15->execute(array($id));
                    $resultatStagePourvu =$requetesearch15->fetch();
                    // mise a jour du stage pourvu en enlevant 1 pour celui qui été précédemment sélctionné.
                    $resultatStagePourvu1 = $resultatStagePourvu['nbPostePourvu'];
                    $newresultatStagePourvu1 = $resultatStagePourvu1 - 1;

                    $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch16->execute(array($id));
                    $AncienneOffreAccepte = $requetesearch16->fetch();
                    $AncienneOffreAccepte = $AncienneOffreAccepte['idOffre'];

                    $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
                    $requeteupdate3->execute(array($newresultatStagePourvu1, $AncienneOffreAccepte));

                    $requeteupdate10 = $bdd->prepare("UPDATE convention_contrat SET idOffre = ? WHERE idUtilisateur = ?");
                    $requeteupdate10->execute(array($newidOffre,$id));

                    //$requeteupdate11 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                    //$requeteupdate11->execute(array($accepte,$id));

                    $requetesearch17 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch17->execute(array($id));
                    $resultatStagePourvu =$requetesearch17->fetch();
                    // mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                    $resultatStagePourvu2 = $resultatStagePourvu['nbPostePourvu'];
                    $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                    $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch16->execute(array($id));
                    $NouvelleOffreAccepte = $requetesearch16->fetch();
                    $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                    $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
                    $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
                }
                //sinon on vérifie que l'état est à "accepte" et on insert dans la table stage
                else{
                    $requeteinsert7 = $bdd->prepare("INSERT INTO convention_contrat (type_contrat, idUtilisateur, idOffre) VALUES (?,?,?)");
                    $requeteinsert7->execute(array('stage',$id,$newidOffre));

                    //$requeteupdate12 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                    //$requeteupdate12->execute(array($accepte,$id));

                    $requetesearch17 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch17->execute(array($id));
                    $resultatStagePourvu =$requetesearch17->fetch();
                    // mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                    $resultatStagePourvu2 = $resultatStagePourvu['nbPostePourvu'];
                    $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                    $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                    $requetesearch16->execute(array($id));
                    $NouvelleOffreAccepte = $requetesearch16->fetch();
                    $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                    $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
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

            //ajout du site et récupération de l'id
            $requeinsert9 = $bdd->prepare("INSERT INTO site (nomsite, ville, idEntreprise,pays) VALUES (?,?,?,?)");
            $requeinsert9->execute(array($newsite,$newville,$newidEnt,$newpays));

            $requetesearch12 = $bdd->prepare('SELECT * FROM site WHERE nomSite = ? and ville = ? and idEntreprise = ?');
            $requetesearch12->execute(array($newsite,$newville,$newidEnt));
            $resultat12 = $requetesearch12->fetch();

            $newidSite = !empty($resultat12['idSite']) ? $resultat12['idSite'] : NULL ;

            //ajout dans la table offre et récupération de l'id

            $requeinsert10 = $bdd->prepare("INSERT INTO offre (titre, description, nbPoste, idSite, nbPostePourvu, anneeO, parcours, niveau, mailContact, secteur, valider) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $requeinsert10->execute(array($newposte,$newdescription,1,$newidSite,0,$annee,'GPhy','M1',$newmail,$newsecteur,1));

            $requetesearch13 = $bdd->prepare('SELECT * FROM offre WHERE titre = ? and idSite = ?');
            $requetesearch13->execute(array($newposte,$newidSite));
            $resultat13 = $requetesearch13->fetch();

            $newidOffre = !empty($resultat13['idOffre']) ? $resultat13['idOffre'] : NULL ;

            //on cherche si étu existe dans la table stage
            $requetesearch14 = $bdd->prepare('SELECT * FROM convention_contrat WHERE idUtilisateur=?');
            $requetesearch14->execute(array($id));
            $resultat14 = $requetesearch14->fetch();
            $count14 = $requetesearch14->rowcount();

            //si etudiant dans la table stage alors on actualise les informations et on vérifie que état est bien à accepté
            if ($count14 != 0) {

                $requetesearch15 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                $requetesearch15->execute(array($id));
                $resultatStagePourvu =$requetesearch15->fetch();
                // mise a jour du stage pourvu en enlevant 1 pour celui qui été précédemment sélctionné.
                $resultatStagePourvu1 = $resultatStagePourvu['nbPostePourvu'];
                $newresultatStagePourvu1 = $resultatStagePourvu1 - 1;

                $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                $requetesearch16->execute(array($id));
                $AncienneOffreAccepte = $requetesearch16->fetch();
                $AncienneOffreAccepte = $AncienneOffreAccepte['idOffre'];

                $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
                $requeteupdate3->execute(array($newresultatStagePourvu1, $AncienneOffreAccepte));

                $requeteupdate13 = $bdd->prepare("UPDATE convention_contrat SET idOffre = ? WHERE idUtilisateur = ?");
                $requeteupdate13->execute(array($newidOffre,$id));

                //$requeteupdate14 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                //$requeteupdate14->execute(array($accepte,$id));

                $requetesearch17 =$bdd->prepare("select nbPostePourvu from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                $requetesearch17->execute(array($id));
                $resultatStagePourvu =$requetesearch17->fetch();
                // mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                $resultatStagePourvu2 = $resultatStagePourvu['nbPostePourvu'];
                $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                $requetesearch16->execute(array($id));
                $NouvelleOffreAccepte = $requetesearch16->fetch();
                $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
                $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
            }
            //sinon on vérifie que l'état est à "accepte" et on insert dans la table stage
            else{
                $requeteinsert11 = $bdd->prepare("INSERT INTO convention_contrat (type_contrat, idUtilisateur, idOffre) VALUES (?,?,?)");
                $requeteinsert11->execute(array('stage',$id,$newidOffre));

                //$requeteupdate15 = $bdd->prepare("UPDATE postule SET etat_recherche = ? WHERE idUtilisateur = ?");
                //$requeteupdate15->execute(array($accepte,$id));

                $requetesearch17 =$bdd->prepare("select nbPostePourvu from offre join convetion_contrat using (idOffre) where idUtilisateur = ?");
                $requetesearch17->execute(array($id));
                $resultatStagePourvu =$requetesearch17->fetch();
                // mise a jour du stage pourvu en ajoutant 1 pour celui qui été nouvellement sélctionné.
                $resultatStagePourvu2 = $resultatStagePourvu['nbPostePourvu'];
                $newresultatStagePourvu2 = $resultatStagePourvu2 + 1;

                $requetesearch16 =$bdd->prepare("select idOffre from offre join convention_contrat using (idOffre) where idUtilisateur = ?");
                $requetesearch16->execute(array($id));
                $NouvelleOffreAccepte = $requetesearch16->fetch();
                $NouvelleOffreAccepte = $NouvelleOffreAccepte['idOffre'];

                $requeteupdate3 = $bdd->prepare("UPDATE offre set nbPostePourvu = ? where idOffre = ?");
                $requeteupdate3->execute(array($newresultatStagePourvu2, $NouvelleOffreAccepte));
            }
        }
    }
    // Au cas où ces éléments sont importants pour visionner les données du côté admin
    $requetePostule =$bdd->prepare("UPDATE postule_m1 SET entretien_passe = 1, proposition_recue = 1, proposition_acceptee = 1 WHERE idUtilisateur = ? AND idOffre = ?");
    $requetePostule->execute(array($id,$newidOffre));
}
header("Location: ../stage_accepte.php");
exit();