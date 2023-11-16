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
include('fonctionality/annee+promo.php');

function debug_to_console($data) {
$output = $data;
if (is_array($output))
$output = implode(',', $output);

echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
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
                    <li class="breadcrumb-item active">Ajout d'offre</li>
                </ol>

    <!----------------------------Section offre------------------------------------------->

    <div class="card mb-4">      <!--div de section 1 -->
        <div class="card-header">         
            <!--div de encadré 1 -->
            <i class="far fa-file-pdf"></i>
            Ajouter une offre
        </div><!--fin div de encadré 1 -->
        <p>* : Saisie obligatoire</p>

        <!-- Tableau ajout de l'offre -->

        <form method ="post">
            <div class="card-body"><!--div de tableau 1 -->
                <div class="form-group">
                    <label><h5> Nom de l'entreprise* :</h5></label>
                    <br>
                    <select name="Entreprise"  id="Entreprise">
                        <option value="">Sélectionnez une entreprise</option>
                        <?php
                            $AjoutOffre = $bdd->query('SELECT * FROM entreprise ORDER BY nomEntreprise ASC');
                            while ($donnees = $AjoutOffre->fetch()) {
                        ?>
                        <option value="<?php echo $donnees['idEntreprise']; ?>">
                            <?php echo $donnees['nomEntreprise']; ?>
                        </option>
                        <?php }; ?>
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label><h5> Nom du site* :</h5></label>
                    <br>
                    <select name="ChoiceSite"  id="ChoiceSite">
                        <option value="">Sélectionnez un site</option>
                        <?php
                            $SelectSite = $bdd->query('SELECT DISTINCT nomSite FROM site ORDER BY nomSite ASC');
                            while ($donnee = $SelectSite->fetch()) {
                        ?>
                        <option value="<?php echo $donnee['nomSite']; ?>">
                            <?php echo $donnee['nomSite']; ?>
                        </option>
                        <?php } ?>
                    </select>
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
                    <label><h5> Intitulé de l'offre* :</h5></label>
                    <input type="text" class="form-control" id="Titre" name="Titre"required>
                </div>
                <br>
                <div class="form-group">
                    <label><h5> Description* :</h5></label>
                    <input type="text" class="form-control" id="Des" name="Des"required>
                </div>
                <br>
                <div class="form-group">
                    <label><h5> Nombre de postes* :</h5></label>
                    <input type="number" class="form-control" id="nbPostes" name="nbPostes"required>
                </div>
                <br>
                <input type="submit" class="btn btn-warning" name="ValidAjoutOffre" value="Ajouter">
                <br>
            </div><!--fin div de tableau 1 -->
        </form>
        <?php
            if (isset($_POST['ValidAjoutOffre'])) {

                $idEntreprise = $_POST['Entreprise'];
                $Site = $_POST['ChoiceSite'];
                $titre = $_POST['Titre'];
                $description = $_POST['Des'];
                $nbPoste = $_POST['nbPostes'];
                $ville = $_POST['Ville'];
                $ville = ucfirst($ville);
                $pays = $_POST['Pays'];
                $pays= strtoupper($pays);

                //Requete pour vérifier l'existence de l'offre

                $verifOffre = $bdd->prepare("SELECT idEntreprise, nomSite, site.idSite, ville, pays, titre FROM offre_stage JOIN site on offre_stage.idSite = site.idSite WHERE site.idEntreprise = ? and nomSite = ? and ville = ? and pays = ? and titre = ?");
                $verifOffre->execute(array($idEntreprise,$Site,$ville,$pays,$titre));
                $resultOffre = $verifOffre->fetch();
                $compteOf = $verifOffre->rowCount();

                //Requete pour vérifier l'existence du site

                $verifSite = $bdd->prepare("SELECT idSite FROM site WHERE nomSite = ? and ville = ? and pays = ? and idEntreprise = ?");
                $verifSite->execute(array($Site,$ville,$pays,$idEntreprise));
                $resultSite = $verifSite->fetch();
                $compteSi = $verifSite->rowCount();

                //récupération de l'id du site (uniquement s'il existe)
                $idSite = !empty($resultSite['idSite']) ? $resultSite['idSite'] : NULL ;

                if ($compteOf != 0) {//vérif si offre existe déjà ou pas

                    echo "L'offre existe déjà";


                }
                else{ //si l'offre n'existe pas, on vérifie que le site existe bien

                    if ($compteSi != 0) { //si le site existe alors on ajoute l'offre

                        $insertOffre = $bdd->prepare("INSERT INTO offre_stage (titre, description, NbPoste, idSite, stage_pourvu, annee) VALUES (?,?,?,?,?,?)");
                        $insertOffre->execute(array($titre,$description,$nbPoste,$idSite, 0, $annee));
                        //$resultInsertOffre = $insertOffre->fetch();
                        echo "L'offre a été ajoutée";


                    }else{//sinon on redirige vers la page d'ajout de site (ou on fait insert, a voir)

                        echo "Le site n'étant pas préexistant, l'offre n'a pas pu être ajoutée";

                    }
                }
            }else{
            
                echo " "; //pour garder un affichage vide avant de valider et éviter prb
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