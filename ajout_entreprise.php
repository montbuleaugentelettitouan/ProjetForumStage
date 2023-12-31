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
                    <li class="breadcrumb-item active">Ajout d'une entreprise</li>
                </ol>

    <!----------------------------Section entreprise------------------------------------------->

    <div class="card mb-4">      <!--div de section 1 -->
        <div class="card-header">         
            <!--div de encadré 1 -->
            <i class="far fa-file-pdf"></i>
            Ajouter une entreprise
        </div><!--fin div de encadré 1 -->

        <br>
        
        <p>* : Saisie obligatoire</p>

        <!-- Tableau ajout de l'entreprise -->

        <form method ="post">
            <div class="card-body"><!--div de tableau 1 -->
                <div class="form-group">
                    <label><h5> Nom de l'entreprise* :</h5></label>
                    <input type="text" class="form-control" id="Entreprise" name="Entreprise" required>
                </div>
                <br>
                <div class="form-group">
                    <label><h5> Nom du site* :</h5></label>
                    <input type="text" class="form-control" id="ChoiceSite" name="ChoiceSite" required>
                </div>
                <br>
                <div class="form-group">
                    <label><h5> Ville* :</h5></label>
                    <input type="text" class="form-control" id="Ville" name="Ville" required>
                </div>
                <br>
                <div class="form-group">
                    <label><h5> Pays* :</h5></label>
                    <input type="text" class="form-control" id="Pays" name="Pays" required>
                </div>
                <br>
                <div class="form-group">
                    <label><h5> Contact RH :</h5></label>
                    <input type="text" class="form-control" id="contactRH" name="contactRH">
                </div>
                <br>
                <input type="submit" class="btn btn-warning" name="ValidAjoutEntreprise" value="Ajouter">
                <br>
            </div><!--fin div de tableau 1 -->
        </form>
        <?php
        if (isset($_POST['ValidAjoutEntreprise'])) {
            $nomEntreprise = $_POST['Entreprise'];
            $nomEntreprise= strtoupper($nomEntreprise);

            $Site = $_POST['ChoiceSite'];
            $Site= strtoupper($Site);

            $ville = $_POST['Ville'];
            $ville = ucfirst($ville);

            $pays = $_POST['Pays'];
            $pays= strtoupper($pays);

            $contactRH = $_POST['contactRH'];
            
            //test des variables
            /*echo $nomEntreprise;
            echo $Site;
            echo $ville;
            echo $pays;
            echo $contactRH*/

            //on vérifie si l'entreprise existe déja
            $verifE = $bdd ->prepare('SELECT * from entreprise WHERE nomEntreprise = ?');
            $verifE->execute(array($nomEntreprise));
            $resultatENT = $verifE ->fetch();
            $countENT = $verifE->rowcount();

            if ( $countENT != 0) {
                //si oui, on récupère l'id
                $recupE = $bdd->prepare('SELECT * FROM entreprise WHERE nomEntreprise = ?');
                $recupE->execute(array($nomEntreprise));
                $resultRecup = $recupE->fetch();

                $idEnt = !empty($resultRecup['idEntreprise']) ? $resultRecup['idEntreprise'] : NULL ;

            } else {   //s'il y a rien alors on rajoute dans la bdd et on récupère l'id

                $reqinsertE = $bdd -> prepare ('INSERT INTO entreprise (nomEntreprise) VALUES (?)');
	            $reqinsertE -> execute (array($nomEntreprise));
	            $resultatE = $reqinsertE-> fetch();

                //on récupère l'identifiant de l'entreprise qui vient d'être ajouté
        
                $recupE = $bdd->prepare('SELECT * FROM entreprise WHERE nomEntreprise = ?');
                $recupE->execute(array($nomEntreprise));
                $resultRecup = $recupE->fetch();

                $idEnt = !empty($resultRecup['idEntreprise']) ? $resultRecup['idEntreprise'] : NULL ;
            }
            //on vérifie si le site existe déjà
            $verifS = $bdd ->prepare('SELECT * from site WHERE nomSite =? and ville = ? and idEntreprise = ? ');
            $verifS->execute(array($Site,$ville,$idEnt));
            $resultatSite = $verifS ->fetch();
            $countSite = $verifS->rowcount();

            if($countSite !=0) {
                echo "Ce site existe déjà"; 

            } else{

                //si non, on l'ajoute
                $reqinsertS = $bdd -> prepare ('INSERT INTO site (nomSite, ville, pays, contactRH, idEntreprise) values (?,?,?,?,?) ');
                $reqinsertS -> execute (array($Site, $ville, $pays, $contactRH, $idEnt));
                $resultatS = $reqinsertS -> fetch();

                echo "Le site a été ajoutée avec succès";

            }
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