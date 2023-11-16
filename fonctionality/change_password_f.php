<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */
session_start();

// connexion à la bdd
$dns = 'mysql:host=db5011586734.hosting-data.io;dbname=dbs9769312;charset=utf8';

try {
    //connexion pour serveur local (ici XAMPP)
    $user = 'dbu918330';
    $password = 'soinosnb-54645984-jnvjkqnv';

    $bdd = new PDO($dns, $user,$password);


    //connexion avec la bdd forumstage2022
    /*
    $user = 'forumstage2022';
    $password = 'ytudyfh-tyruh-45213';

    $bdd = new PDO($dns, 'forumstage2022' , 'ytudyfh-tyruh-45213');
    */

}

    // on vérifie les erreurs
catch (PDOexception $e) {
    exit ('<b>Erreur de connection à la ligne: '.$e->getLine().':</b>'.$e->getMessage());
}

if (isset($_POST['submit'])) {

    //on fait quelques modifications pour éviter les attaques
    $oldpassword = md5(htmlspecialchars($_POST['oldpassword']));
    $newpassword = md5(htmlspecialchars($_POST['newpassword']));
    $newpassword2 = md5(htmlspecialchars($_POST['newpassword2']));

    if ($newpassword2 !== $newpassword) {
        //message erreur nouveau mot de passe
        header('Location: ../change_password.php?erreur=3');
    } else {
        $userpassword=$_SESSION['user'];

        $requete = $bdd->prepare('SELECT password FROM utilisateur where idUtilisateur = ?');
        $requete->execute(array($userpassword));
        $recup = $requete->fetch();

        if ($oldpassword == $recup['password']) {

            $requete2 = $bdd->prepare("UPDATE utilisateur SET password =:mdp WHERE idUtilisateur=:mdpid");
            $requete2->execute(array(':mdp'=>$newpassword, ':mdpid'=>$userpassword));

            header('Location: ../changement_password_ok.php');

        }
        else {
            //mauvais ancien mot de passe
            header('Location: ../change_password.php?erreur=2');
        }

    }
}
else{

   // header('Location: ../dashboardM1.php');

}
?>