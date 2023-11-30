<?php
//A la connexion, l'année est définit dans une variable de session pour les admins. 
//Elle n'est en revanche pas initialisé lors d'une connexion étudiant. 
//Ces valeurs doivent être modifié directement ici suivant les années pour que les étudiants voient les stages de leur année seulement et pas des autres. 
//Il est également important de faire en sorte que l'année de la promo soit d'une année supérieur à l'année en cours.
if (!isset($_SESSION['compt'])){
    $annee = 2024;
    $promo = 2025;
    $parcours = 'GPhy';
}
?>
