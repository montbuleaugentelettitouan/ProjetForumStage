<?php
    //Connexion à la base de données avec le nom de la base
    $dns = 'mysql:host=localhost;dbname=stage2';

    try {
    //Nom d'utilisateur et mot de passe pour accéder à la base de données

    //Connexion pour serveur local (ici XAMPP)
        $user = 'root';
        $password = '';
        $bdd = new PDO($dns, $user,$password);
    }

    //On vérifie les erreurs
    catch (PDOexception $e) {
        exit ('<b>Erreur de connection à la ligne: '.$e->getLine().':</b>'.$e->getMessage());
}
?>