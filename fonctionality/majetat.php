<?php 


            $req4 = $bdd->prepare('UPDATE postule_m1 SET etat_recherche = ? WHERE idUtilisateur = ? AND entretien_passe = ? AND proposition_recue = ? AND proposition_acceptee = ?');
            $req4->execute(array("accepte",$id, 1, 1, 1));

            $req5 = $bdd->prepare('UPDATE postule_m1 SET etat_recherche = ? WHERE idUtilisateur = ? AND entretien_passe = ? AND proposition_recue = ? AND proposition_acceptee = ?');
            $req5->execute(array("en attente",$id, 1, 1, 0));

            $req6 = $bdd->prepare('UPDATE postule_m1 SET etat_recherche = ? WHERE idUtilisateur = ? AND entretien_passe = ? AND proposition_recue = ? AND proposition_acceptee = ?');
            $req6->execute(array("refuse",$id, 1, 0, 0));

            $req7 = $bdd->prepare('UPDATE postule_m1 SET etat_recherche = ? WHERE idUtilisateur = ? AND entretien_passe = ? AND proposition_recue = ? AND proposition_acceptee = ?');
            $req7->execute(array("refuse",$id, 0, 0, 0));

            $req8 = $bdd->prepare('SELECT DISTINCT etat_recherche, COUNT(idOffre) as NB_Offre FROM postule_m1 WHERE idUtilisateur = ? GROUP BY Etat_Recherche ORDER BY Etat_Recherche LIMIT 1');
            $req8->execute(array($id));
            $resultReq8 = $req8->fetchAll();

            foreach($resultReq8 as $ligne) {
                if($ligne['etat_recherche'] == 'accepte'){
                    $req9 = $bdd->prepare('UPDATE utilisateur SET etatC = ? WHERE idUtilisateur = ?');
                    $req9->execute(array("accepte",$id));
                }
                elseif($ligne['etat_recherche'] == 'en attente'){
                    $req9 = $bdd->prepare('UPDATE utilisateur SET etatC = ? WHERE idUtilisateur = ?');
                    $req9->execute(array("en attente",$id));
                }
                elseif($ligne['etat_recherche'] == 'refuse'){
                    $req9 = $bdd->prepare('UPDATE utilisateur SET etatC = ? WHERE idUtilisateur = ?');
                    $req9->execute(array("en recherche",$id));
                }
            }


?>