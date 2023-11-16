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
                    <li class="breadcrumb-item active">Modifications des offres</li>
                </ol>

    <!----------------------------Section offre------------------------------------------->

    <div class="card mb-4"> <!--div de section 1 -->
        <div class="card-header"> <!--div de encadré 1 -->
            <i class="far fa-file-pdf"></i>
            Modification d'une offre
        </div><!--fin div de encadré 1 -->
        <p>* : Saisie obligatoire</p>
        <!-- Tableau modification de l'offre -->

        <form method="post">
            <div class="card-body"> <!--div de tableau 1 -->
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nom de l'entreprise</th>
                            <th>Nom du site</th>
                            <th>Intitulé de l'offre*</th>
                            <th>Description</th>
                            <th>Nombre de postes*</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $idO = $_GET['id'];
                        $afficheO = $bdd ->prepare("SELECT * FROM offre_stage WHERE idOffre =?");
                        $afficheO->execute(array($idO));
                        $resultAfficheO = $afficheO->fetch();

                        $afficheES = $bdd ->prepare("SELECT nomEntreprise, nomSite FROM offre_stage JOIN site on offre_stage.idSite = site.idSite JOIN entreprise on site.idEntreprise = entreprise.idEntreprise WHERE idOffre =?");
                        $afficheES->execute(array($idO));
                        $resultAfficheES = $afficheES->fetch();
                        ?>

                        <tr>
                            <td>
                                <textarea name = "texteAreaEnt6" id = "texteAreaEnt6" placeholder =
                                "<?php echo $resultAfficheES['nomEntreprise']; ?>"
                                class="form-control" rows="3" ></textarea> </td>
                            </td>
                            <td>
                                <textarea name = "texteAreaSite6" id = "texteAreaSite6" placeholder =
                                "<?php echo $resultAfficheES['nomSite']; ?>"
                                class="form-control" rows="3"></textarea> </td>
                            <td>
                                <textarea name = "texteAreaTitre" id = "texteAreaTitre" placeholder =
                                "<?php echo $resultAfficheO['titre']; ?>"
                                class="form-control" rows="3" required></textarea> </td>
                            <td>
                                <textarea name = "texteAreaDes" id = "texteAreaDes" placeholder =
                                "<?php echo $resultAfficheO['description']; ?>"
                                class="form-control" rows="3" ></textarea> </td>
                            <td>
                            <input type="number" id="nbPoste" name="nbPoste" min= "1" max= "50"required></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <input type="submit" class="btn btn-warning" name="ValidModifOffre" value="Valider">
            <br>
        </form>
        <?php
        if (isset($_POST['ValidModifOffre'])) {
          $titre = $_POST['texteAreaTitre'];
          $Des = $_POST['texteAreaDes'];
          $Poste = $_POST['nbPoste'];
  
          $AjoutO = $bdd ->prepare("UPDATE offre_stage SET titre = ?, description = ?, NbPoste = ? WHERE idOffre =?");
          $AjoutO->execute(array($titre,$Des,$Poste,$idO));
          $resultAjoutO = $AjoutO->fetch();
  
          echo "L'offre a bien été modifiée";
        }else{
          echo " ";
        }

        ?>
    </div> <!--fin div de section 1 -->

      <!----------------------------Footer------------------------------------------->

    </div><!--fin div de page -->
  </main>
  <?php
    include('fonctionality/footer.php');
  ?>
</div><!--fin body de la page -->
</body>
</html>