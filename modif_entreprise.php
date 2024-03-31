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
            <h1 class="mt-4">Modification entreprise </h1>
            <br>
            <center>
            <div class="card mb-4"> <!--div de section 1 -->
                <div id="confirmationMessage" style="display: none; font-size: 20px; color: mediumseagreen;">
                    <b>Modifications prises en compte</b>
                </div>
            </div>
            </center>
    <!----------------------------Section site/entreprise------------------------------------------->

    <div class="card mb-4"> <!--div de section 1 -->
        <div class="card-header"> <!--div de encadré 1 -->
        </div><!--fin div de encadré 1 -->
        <!-- Tableau modification de l'offre -->

        <form method="post">
            <div class="card-body"> <!--div de tableau 1 -->
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nom de l'entreprise </th>
                            <th>Contact RH </th>
                            <th>Nom du site </th>
                            <th>Ville </th>
                            <th>Pays </th>
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
                            <textarea name = "texteAreaContact" id = "texteAreaContact" placeholder =
                            "<?php echo $resultAfficheS['contactRH']; ?>"
                                      value =  "<?php echo $resultAfficheS['contactRH']; ?>"
                                      class="form-control" rows="3" ></textarea> </td>
                            <td>
                                <textarea name = "texteAreaSite" id = "texteAreaSite" placeholder =
                                "<?php echo $resultAfficheS['nomSite']; ?>"
                                          value =  "<?php echo $resultAfficheS['nomSite']; ?>"
                                class="form-control" rows="3"></textarea> </td>
                            <td>
                                <textarea name = "texteAreaVille" id = "texteAreaVille" placeholder =
                                "<?php echo $resultAfficheS['ville']; ?>"
                                          value =  "<?php echo $resultAfficheS['ville']; ?>"
                                class="form-control" rows="3" ></textarea> </td>
                            <td>
                                <textarea name = "texteAreaPays" id = "texteAreaPays" placeholder =
                                "<?php echo $resultAfficheS['pays']; ?>"
                                          value =  "<?php echo $resultAfficheS['pays']; ?>"
                                class="form-control" rows="3" ></textarea> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <input type="submit" class="btn btn-warning" name="ValidModifEnt" value="Valider">
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

              $url = "modif_entreprise.php?id=$idS&success=true";
              echo "<script>window.location.replace(\"$url\")</script>";
             exit();
        }else{
          echo " ";
        }
        ?>
        <br>
        <div>
            <a href="gestion_entreprise.php">
                <input type="submit" class="btn btn-secondary" name="retour" id="retour2" value="Retour">
            </a>
        </div>
        <script>
            // Récupérer le paramètre GET de l'URL
            const urlParams = new URLSearchParams(window.location.search);
            const success = urlParams.get('success');

            // Vérifier si le paramètre success est présent et égal à true
            if (success === 'true') {
                // Afficher la div de confirmation
                const confirmationDiv = document.getElementById('confirmationMessage');
                if (confirmationDiv) {
                    confirmationDiv.style.display = 'block';
                }
            }
        </script>
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