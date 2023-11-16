<?php
/**
 *
 * @autor : Damien Caloin, Nathan Godart
 * @date : Promo GPhy 2023 - Année 2022 : 2023
 *
 */
include('barre_nav_admin.php');
include('fonctionality/bdd.php');
include('fonctionality/annee+promo.php');
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"> </script>

<!-- Body de la page -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard des entreprises et des offres</h1>
            <ol class="breadcrumb mb-4">
                <br>
                <li class="breadcrumb-item active">Vue générale du statut des entreprises</li>
				<!-- Requete pour récupérer le nombre d'entreprise présente au forum ayant pourvu ou non des stages -->
                <?php
                $requetesearch = $bdd->prepare("select count(*) as 'Nombre Entreprise' from ( select sum(stage_pourvu) as pourvu , nomEntreprise from offre_stage join site using (idSite) join entreprise using (idEntreprise) where annee = ? group by idEntreprise) as T where T.pourvu != 0;");
                $requetesearch->execute(array($annee));
                $resultatEntrepriseComplet = $requetesearch->fetch();
                $resultatEntrepriseComplet = $resultatEntrepriseComplet['Nombre Entreprise'];

                $requetesearch = $bdd->prepare("select count(*) as 'Nombre Entreprise' from ( select sum(stage_pourvu) as pourvu , nomEntreprise from offre_stage join site using (idSite) join entreprise using (idEntreprise) where annee = ? group by idEntreprise) as T where T.pourvu = 0;");
                $requetesearch->execute(array($annee));
                $resultatEntrepriseVide = $requetesearch->fetch();
                $resultatEntrepriseVide = $resultatEntrepriseVide['Nombre Entreprise'];
                ?>
                <!--Emplacement du Pie Chart-->
                <div>
                    <br><br>
                    <canvas id="myChart" width="600" height="600"></canvas>
                    <br><br>
                </div>

                <script>
                    //paramètre du chart entreprise 
                    const ctx = document.getElementById('myChart').getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
							// attention, les labels sont utilisé pour indiqué quel élément a été cliquer dans la fonction onclick ci dessous. ceci permet la redirection
                            labels: ['Entreprise ayant pourvu des stages' , "Entreprise n'ayant pourvu aucun stages"],
                            datasets: [{
                                label: "Nombre d'entreprises",
                                data: [<?php echo $resultatEntrepriseComplet ?>, <?php echo $resultatEntrepriseVide ?> ],
                                backgroundColor: [
                                    'rgba(176,216,169)',
                                    'rgba(218,111,111)'


                                ],

                                borderColor: [
                                    'rgba(0,0,0)',
                                    'rgba(0,0,0)'
                                ],
                                borderWidth: 1.5
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    display: false
                                }
                            },
                            onClick:function(e){
                                const activePoints = myChart.getElementsAtEvent(e);
                                const selectedIndex = activePoints[0]._index;
                                let label = this.data.labels[selectedIndex];
                                console.log(this.data.labels[selectedIndex])
                                if (label == 'Entreprise ayant pourvu des stages'){
                                    window.location.href = "tableau_adminE1.php";
                                }
                                else if (label == "Entreprise n'ayant pourvu aucun stages"){
                                    window.location.href = "tableau_adminE3.php";
                                }
                            }
                        }
                    });
                </script>

                <script>
				// creation et affichage du pie chart entreprise
                    const myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                </script>
            </ol>

            <!-- fin body de page-->

    </main>
    <div>
        <?php
        include('fonctionality/footer.php');
        ?>
    </div>
</div>