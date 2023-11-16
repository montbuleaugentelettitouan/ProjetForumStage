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
<body>
<div id="layoutSidenav_content"> <!-- body de la page -->
    <main>
        <div class="container-fluid px-4"> <!-- div de page -->
            <h1 class="mt-4">Page Administrateur </h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Modifications des sites</li>
                </ol>

    <!----------------------------Section site/entreprise------------------------------------------->

    <div class="card mb-4"> <!--div de section 1 -->
        <div class="card-header"> <!--div de encadré 1 -->
            <i class="far fa-file-pdf"></i>
            Modification du site et de l'entreprise
        </div><!--fin div de encadré 1 -->
        <!-- Tableau modification de l'offre -->

        <form method="post">
            <div class="card-body"> <!--div de tableau 1 -->
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nom de l'entreprise </th>
                            <th>Nom du site </th>
                            <th>Ville </th>
                            <th>Pays </th>
                            <th>Contact RH </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $idS = $_GET['id'];
                        $afficheE = $bdd ->prepare("SELECT * FROM entreprise join site on entreprise.idEntreprise = site.idEntreprise WHERE idSite =?");
                        $afficheE ->execute(array($idS));
                        $resultAfficheE = $afficheE->fetch();

                        $afficheS = $bdd ->prepare("SELECT * FROM site WHERE idSite =?");
                        $afficheS->execute(array($idS));
                        $resultAfficheS = $afficheS->fetch();
                        ?>

                        <tr>
                            <td>
                                <textarea name = "texteAreaEnt" id = "texteAreaEnt" placeholder=
                                "<?php echo $resultAfficheE['nomEntreprise']; ?>"
                                          value = "<?php echo $resultAfficheE['nomEntreprise']; ?>"
                                class="form-control" rows="3" ></textarea> </td>
                            </td>
                            <td>
                                <textarea name = "texteAreaSite" id = "texteAreaSite" placeholder =
                                "<?php echo $resultAfficheS['nomSite']; ?>"
                                          value =  "<?php echo $resultAfficheS['nomSite']; ?>"
                                class="form-control" rows="3"></textarea> </td>
                            <td>
                                <textarea name = "texteAreaVille" id = "texteAreaVille" placeholder =
                                "<?php echo $resultAfficheS['ville']; ?>"
                                          value =  "<?php echo $resultAfficheS['ville']; ?>"
                                class="form-control" rows="3" required></textarea> </td>
                            <td>
                                <textarea name = "texteAreaPays" id = "texteAreaPays" placeholder =
                                "<?php echo $resultAfficheS['pays']; ?>"
                                          value =  "<?php echo $resultAfficheS['pays']; ?>"
                                class="form-control" rows="3" ></textarea> </td>
                            <td>
                            <textarea name = "texteAreaContact" id = "texteAreaContact" placeholder =
                                "<?php echo $resultAfficheS['contactRH']; ?>"
                                      value =  "<?php echo $resultAfficheS['contactRH']; ?>"
                                class="form-control" rows="3" ></textarea> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <input type="submit" class="btn btn-warning" name="ValidModifEnt" value="Valider">
            <br>
        </form>
        <?php
          if (isset($_POST['ValidModifEnt'])) {
              if ($_POST['texteAreaEnt'] == ""){
                  $nomEntreprise = $resultAfficheE['nomEntreprise'];
              }
              else{
                $nomEntreprise = $_POST['texteAreaEnt'];
                $nomEntreprise = strtoupper($nomEntreprise);
              }

              if ($_POST['texteAreaSite'] == ""){
                  $nomSite = $resultAfficheS['nomSite'];
              }
              else {
                  $nomSite = $_POST['texteAreaSite'];
                  $nomSite = strtoupper($nomSite);
              }
              if ($_POST['texteAreaVille'] == ""){
                  $ville = $resultAfficheS['ville'];
              }
              else {
                  $ville = $_POST['texteAreaVille'];
                  $ville = ucfirst($ville);
              }
              if ($_POST['texteAreaPays'] == ""){
                  $pays = $resultAfficheS['pays'];
              }
              else {
                  $pays = $_POST['texteAreaPays'];
                  $pays = strtoupper($pays);
              }
              if ($_POST['texteAreaContact'] == ""){
                  $contactRH = $resultAfficheS['contactRH'];
              }
              else {
                  $contactRH = $_POST['texteAreaContact'];
              }
          $ModifS = $bdd ->prepare("UPDATE site SET nomSite = ?, ville = ?, pays = ?, contactRH = ? WHERE idSite =?");
          $ModifS->execute(array($nomSite,$ville,$pays,$contactRH,$idS));
          $resultModifS = $ModifS->fetch();

          $ModifE = $bdd ->prepare("UPDATE entreprise SET nomEntreprise = ? WHERE idEntreprise =?");
          $ModifE->execute(array($nomEntreprise,$resultAfficheE['idEntreprise']));
          $resultModifE = $ModifE->fetch(); 
  
          echo "Le site a été modifié";
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