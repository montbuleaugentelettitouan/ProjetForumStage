<?php

/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */

session_destroy();
session_start();

// paramètres DNS pour connecter à la base de données - test
    $dns = 'mysql:host=localhost;dbname=dbs9769312';

// on tente d'établir une connexion avec la BDD
try {
    //connexion pour serveur local (ici XAMPP)
    $user = 'root';
    $password = '';

    $bdd = new PDO($dns, $user, $password);

}
// on vérifie les erreurs
catch(PDOexception $e)
{
    exit ('<b>Erreur de connexion à la ligne '.$e->getLine().':</b>'.$e->getMessage());

}

// fin du script PHP de connexion à la base de données

// on vérifie que les champs "Mail" et "mot de passe (mdp)" sont complétés

if(!empty($_POST['mail']) && !empty($_POST['mdp']))
{
	$_POST['mdp'] = md5($_POST['mdp']);
    // si les champs sont complets alors on récupère les informations saisies (mail et mdp)
    $userEmail = htmlspecialchars($_POST['mail']);
    $userPassword = htmlspecialchars($_POST['mdp']);

    // On vérifie qu'ils ne sont pas rempli de rien
    if($userEmail !== "" && $userPassword !== "")
    {
        // On sélectionne l'ensemble des paramètres de chaque utilisateur décrit dans la table "Utilisateurs"
        $requete = $bdd->prepare('SELECT * FROM utilisateur where email =:mail and password =:mdp');
        // On exécute le tableau avec les paramètres $userEmail et $userPassword décrits dans les champs de saisie
        $requete->execute(array(':mail'=>$userEmail, ':mdp'=>$userPassword));

        // On récupère les données
        $reponse = $requete->fetch();

        // On compte le nombre de ligne pour valider le fait que l'on ait trouvé l'utilisateur
        $count = $requete->rowcount();

        // Si c'est égal à 0 alors on n'a pas trouvé l'utilisateur
        if($count != 0) // nom d'utilisateur et mot de passe correctes
        {
            // On commence regarder si l'utilisateur est un Administrateur de l'application
            if ($reponse['statut'] == "administrateur") {

                // S'il est un ADMIN alors on récupère la SESSION et on le redirige vers la page admin
                $_SESSION['user'] = $reponse['idUtilisateur'];
                $_SESSION['prenom'] = $reponse['prenom'];
                $_SESSION['nom'] = $reponse['nom'];
                $_SESSION['mail'] = $reponse['email'];
                $_SESSION['statut'] = $reponse['statut'];
                $_SESSION['parcours'] = 'GPhy';
                header('Location: ../gestion_etudiants.php');

            }
            else {

                // S'il est un M1 alors on récupère la SESSION et on le redirige vers son dashboard
                $_SESSION['user'] = $reponse['idUtilisateur'];
                $_SESSION['prenom'] = $reponse['prenom'];
                $_SESSION['nom'] = $reponse['nom'];
                $_SESSION['mail'] = $reponse['email'];
                $_SESSION['statut'] = $reponse['statut'];
                $_SESSION['parcours'] = $reponse['parcours'];

                header('Location: ../profil.php');

            }
        }
        else
        {
           header('Location: ../index.php?erreur=1'); // utilisateur ou mot de passe incorrect
        }
    }
    else
    {
       header('Location: ../index.php?erreur=2'); // utilisateur ou mot de passe vide
    }
}
else
{
   header('Location: ../index.php');
}

?>