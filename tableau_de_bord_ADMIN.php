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
            <h1 class="mt-4">Dashboard étudiants</h1>

            <ol class="breadcrumb mb-4">
                <br>
                <li class="breadcrumb-item active">Vue générale du statut des étudiants</li>
				<!-- Récupère le nombre d'étudiants en fonction de leur statut sur la plateforme -->
                <?php
                $stockStatus1 = $bdd->prepare("select count(distinct idUtilisateur) as 'Nombre étudiants' from utilisateur where statut = 'etudiant' and promo = ? and etatC = 'accepte';");
                $stockStatus1->execute(array($promo));
                $resultatEtuAccepte = $stockStatus1->fetch();
                $resultatEtuAccepte = $resultatEtuAccepte['Nombre étudiants'];

                $stockStatus2 = $bdd->prepare("select count(distinct idUtilisateur) as 'Nombre étudiants' from utilisateur where statut = 'etudiant' and promo = ? and etatC = 'en attente';");
                $stockStatus2->execute(array($promo));
                $resultatEtuEnAttente = $stockStatus2->fetch();
                $resultatEtuEnAttente = $resultatEtuEnAttente['Nombre étudiants'];

                $stockStatus3 = $bdd->prepare("select count(distinct idUtilisateur) as 'Nombre étudiants' from utilisateur where statut = 'etudiant' and promo = ? and etatC = 'en recherche';");
                $stockStatus3->execute(array($promo));
                $resultatEtuEnRecherche = $stockStatus3->fetch();
                $resultatEtuEnRecherche = $resultatEtuEnRecherche['Nombre étudiants'];

                ?>

                <!--Emplacement du Bar Chart-->
                <div>
                    <br><br>
                    <canvas id="myChart" width="600" height="600"></canvas>
                    <br><br>

                </div>
                <script>
                    // parametre du chart ci dessous
                    const ctx = document.getElementById('myChart').getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
							// attention, les labels sont utilisé pour indiqué quel élément a été cliquer dans la fonction onclick ci dessous. ceci permet la redirection
                            labels: ['Accepté', "En Attente", "En Recherche"],
                            datasets: [{
                                label: "Nombre d'étudiants",
                                data: [<?php echo $resultatEtuAccepte ?>, <?php echo $resultatEtuEnAttente ?>, <?php echo $resultatEtuEnRecherche ?>],
                                backgroundColor: [
                                    'rgba(176,216,169)',
                                    'rgb(219,183,98)',
                                    'rgb(218,111,111)'
                                ],

                                borderColor: [
                                    'rgba(0,0,0)',
                                    'rgba(0, 0,0)',
                                    'rgba(0,0,0)'
                                ],
                                borderWidth: 1.5
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            onClick:function(e){
                                const activePoints = myChart.getElementsAtEvent(e);
                                const selectedIndex = activePoints[0]._index;
                                let label = this.data.labels[selectedIndex];
                                console.log(this.data.labels[selectedIndex])
                                if (label == 'En Attente'){
                                    window.location.href = "tableau_adminEtu2.php";
                                }
                                else if (label == 'En Recherche'){
                                    window.location.href = "tableau_adminEtu3.php";
                                }
                                else if (label == 'Accepté'){
                                    window.location.href = "tableau_adminEtu1.php";
                                }
                            }
                        }
                    });
                </script>



                <script>
				//création et affichage du chart étudiant
                    const myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                    );
                </script>

            </ol>

        </div>
    </main>
            <div>
            <?php
            include('fonctionality/footer.php');
            ?>

        </div>
