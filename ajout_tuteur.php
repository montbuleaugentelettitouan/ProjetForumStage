<?php
/**
 * Fonctionnalité de login à l'application
 *
 * @autor : Anne SIECA, Victor ALLIOT, Alice Broussely et Audrey CHALAUX
 * @date : Promo GPhy 2022 - Année 2021 : 2022
 *
 */

include('barre_nav_admin.php');
include('fonctionality/bdd.php');
?>
<style>
    p {
        padding-left : 20px;
    }
</style>


<body>
<div id="layoutSidenav_content"> <!-- body de la page -->
    <main>
        <div class="container-fluid px-4"> <!-- div de page -->
            <h1 class="mt-4">Page Administrateur </h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Ajout d'un tuteur</li>
            </ol>

            <!----------------------------Section tuteur------------------------------------------->

            <div class="card mb-4">      <!--div de section 1 -->
                <div class="card-header">
                    <!--div de encadré 1 -->
                    <i class="far fa-file-pdf"></i>
                    Ajouter un tuteur
                </div><!--fin div de encadré 1 -->

                <br>

                <p style="color: red;">* : Saisie obligatoire</p>

                <!-- Tableau ajout du tuteur -->

                <form method ="post">
                    <div class="card-body"><!--div de tableau 1 -->
                        <div class="form-group">
                            <label><h5> Nom du tuteur<span style="color: red;">*</span> :</h5></label>
                            <input type="text" class="form-control" id="nom" name="nomT" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Prénom du tuteur<span style="color: red;">*</span> :</h5></label>
                            <input type="text" class="form-control" id="prenom" name="prenomT" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Email :</h5></label>
                            <input type="email" class="form-control" id="email" name="emailT">
                        </div>
                        <br>
                        <div class="form-group">
                            <label><h5> Numéro de téléphone :</h5></label>
                            <input type="text" class="form-control" id="num" name="numT">
                        </div>
                        <br>
                        <input type="submit" class="btn btn-warning" name="ValidAjoutTuteur" value="Ajouter">
                        <br>
                    </div><!--fin div de tableau 1 -->
                </form>
                <?php
                if (isset($_POST['ValidAjoutEntreprise'])) {
                    $nom = $_POST['nomT'];
                    $nom = strtoupper($nom);

                    $prenom = $_POST['prenomT'];
                    $prenom = strtoupper($prenom);

                    $email = $_POST['emailT'];
                    $email = ucfirst($email);

                    $num = $_POST['numT'];
                    $num = strtoupper($num);

                    //test des variables
                    /*echo $nomEntreprise;
                    echo $Site;
                    echo $ville;
                    echo $pays;
                    echo $contactRH*/

                    //on vérifie si l'entreprise existe déja
                    $verifT = $bdd ->prepare('SELECT nom_tuteur_academique, prenom_tuteur_academique from stage WHERE nom_tuteur_academique = ? AND prenom_tuteur_academique = ?');
                    $verifT->execute(array($nomEntreprise));
                    $resultatT = $verifT ->fetch();
                    $countT = $verifT->rowcount();

                    if ( $countT != 0) {
                        header("Location: ajout_tuteur.php?exists");

                    } else {   //s'il y a rien alors on rajoute dans la bdd et on récupère l'id
                        // FINIR ICI, IL FAUT ATTENDRE LA NOUVELLE BDD

                        $reqinsertT = $bdd -> prepare ('INSERT INTO stage (nomEntreprise) VALUES (?)');
                        $reqinsertT -> execute (array($nomEntreprise));
                        $resultatE = $reqinsertT-> fetch();
                    }
                ?>
            </div><!--fin div de section 1 -->
            <!----------------------------Footer------------------------------------------->

        </div><!--fin div de page -->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!--fin body de la page -->
</body>
</html>