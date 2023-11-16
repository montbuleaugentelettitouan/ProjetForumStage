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

<!-- Body de la page -->
<div id="layoutSidenav_content"><!-- Body de la page -->
    <main>
        <div class="container-fluid px-4"><!-- div de page -->
            <h1 class="mt-4">Gestion des offres</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Menu de gestion des offres</li>
            </ol>
            <div class="card mb-4"><!--div de section 1 -->
                <div class="card-header"><!--div de encadré 1 -->
                    <i class="far fa-file-pdf"></i>
                    Ajouter une offre
                </div><!--fin div de encadré 1 -->
                <!-- Tableau saisie de la nouvelle offre -->
                <div class="card-body"><!--div de tableau 1 -->
                    <table class="table table-bordered" >
                        <thead class="thead-dark">
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Ville</th>
                            <th>Pays</th>
                            <th>Nom de l'entreprise</th>
                            <th>Nom du site</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <textarea name = "texteAreaTitre" id = "texteAreaTitre"
                                    class="form-control" rows="3" required>  </textarea> </td>
                                <td>
                                    <textarea name = "texteAreaDes" id = "texteAreaDes"
                                    class="form-control" rows="3" required>  </textarea> </td>
                                <td>
                                    <textarea name = "texteAreaVille" id = "texteAreaVille"
                                    class="form-control" rows="3" required>  </textarea> </td>
                                <td>
                                    <textarea name = "texteAreaPays" id = "texteAreaPays"
                                    class="form-control" rows="3" required>  </textarea> </td>
                                <td>
                                    <select name="Entreprise"  id="Entreprise">
                                    <?php
                                    $AjoutOffre = $bdd->query('SELECT * FROM Entreprise ORDER BY nomEntreprise ASC');
                                    while ($donnees = $AjoutOffre->fetch()) {
                                        ?>
                                        <option value="<?php echo $donnees['idEntreprise']; ?>">
                                            <?php echo $donnees['nomEntreprise']; ?>
                                        </option>
                                    <?php }; ?> </td>
                                <td>
                                    <textarea name = "texteAreaSite" id = "texteAreaSite"
                                    class="form-control" rows="3" required>  </textarea> </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="submit" class="btn btn-warning" name="ValidAjoutOffre" value="Valider">
                    <br>
                    <label> Fait une recherche dans la bdd au cas où l'offre existerait déjà (message d'erreur si c'est le cas)</label>
                    <br>
                    <label> Si l'offre n'existe pas encore alors redirection vers une page pour ajouter les informations dans la bdd</label>
                </div><!--fin div de tableau 1 -->
                <br>
            </div> <!-- fin div de section 1 -->
            <!------------------------------------------------------------------------------------------------------------------------------>
            <div class="card mb-4"><!--div de section 2 -->
                <div class="card-header"><!--div de encadré 2 -->
                    <i class="far fa-file-pdf"></i>
                    Sélectionnez l'offre à modifier
                </div><!--fin div de encadré 2 -->
                <!-- Tableau de sélection de l'offre -->
                <div class="card-body"><!--div de tableau 2 -->
                    <table class="table table-bordered" >
                        <thead class="thead-dark">
                        <tr>
                            <th>Nom de l'offre</th>
                            <th>Nom de l'entreprise</th>
                            <th>Nom du site</th>
                            <th>Ville</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                <select name="nomOffre"  id="nomOffre" required>
                                    <?php
                                    $SearchOffreT = $bdd->query('SELECT titre FROM offre_stage ORDER BY titre ASC');
                                    while ($offreT = $SearchOffreT->fetch()) {
                                        ?>
                                        <option value="<?php echo $offreT['titre']; ?>">
                                            <?php echo $offreT['titre']; ?>
                                        </option>
                                    <?php }; ?> </td>
                                <td>
                                    <select name="nomEntre"  id="nomEntre" required>
                                    <?php
                                    $SearchOffreE = $bdd->query('SELECT nomEntreprise FROM Entreprise ORDER BY nomEntreprise ASC');
                                    while ($offreE = $SearchOffreE->fetch()) {
                                        ?>
                                        <option value="<?php echo $offreE['nomEntreprise']; ?>">
                                            <?php echo $offreE['nomEntreprise']; ?>
                                        </option>
                                    <?php }; ?> </td>
                                <td>
                                    <select name="nameSite"  id="nameSite" required>
                                    <?php
                                    $SearchOffreS = $bdd->query('SELECT nomSite FROM Site ORDER BY nomSite ASC');
                                    while ($offreS = $SearchOffreS->fetch()) {
                                        ?>
                                        <option value="<?php echo $offreS['nomSite']; ?>">
                                            <?php echo $offreS['nomSite']; ?>
                                        </option>
                                    <?php }; ?> </td>
                                <td>
                                    <select name="city"  id="city" required>
                                    <?php
                                    $SearchOffreV = $bdd->query('SELECT ville FROM site WHERE ville IS NOT NULL ORDER BY ville ASC');
                                    while ($offreV = $SearchOffreV->fetch()) {
                                        ?>
                                        <option value="<?php echo $offreV['ville']; ?>">
                                            <?php echo $offreV['ville']; ?>
                                        </option>
                                    <?php }; ?> </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="submit" class="btn btn-warning" name="ValidModifOffre" value="Valider">
                    <br>
                    <label> Redirection vers une page pour modifier les informations dans la bdd</label>
                </div><!--fin div de tableau 2 -->
                <br>
            </div> <!-- fin div de section 2 -->
        </div><!-- fin div de page -->
    </main>
    <?php
    include('fonctionality/footer.php');
    ?>
</div><!-- fin Body de la page -->
</body>
</html>