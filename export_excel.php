<?php
require 'vendor/autoload.php'; // Chargez PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include('fonctionality/bdd.php');
include('fonctionality/annee+promo.php');

// Démarrer la session pour accéder aux variables de session
session_start();

// Accéder à la variable de session de la page barre_nav_admin.php
$promo = $_SESSION['promo'];

$req = $bdd->prepare("SELECT
                            utilisateur.idUtilisateur,
                            utilisateur.nom,
                            utilisateur.prenom,
                            utilisateur.email,
                            utilisateur.numTel,
                            utilisateur.etatC,
                            utilisateur.parcours,
                            utilisateur.promo,
                            utilisateur.typeAnnee,
                            utilisateur.etatCM2,
                            tuteur_academique.nomTA,
                            tuteur_academique.prenomTA,
                            tuteur_academique.emailTA,
                            tuteur_academique.numTA,
                            maitre_de_stage.nomMDS,
                            maitre_de_stage.prenomMDS,
                            maitre_de_stage.emailMDS,
                            maitre_de_stage.numMDS,
                            entreprise.nomEntreprise,
                            ville,
                            site.nomSite,
                            convention_contrat.idConvention,
                            convention_contrat.type_contrat,
                            convention_contrat.statut_contrat,
                            convention_contrat.dateDeb,
                            convention_contrat.dateFin
                        FROM
                            utilisateur
                        LEFT JOIN convention_contrat ON utilisateur.idUtilisateur = convention_contrat.idUtilisateur
                        LEFT JOIN tuteur_academique ON convention_contrat.idTA = tuteur_academique.idTA
                        LEFT JOIN maitre_de_stage ON convention_contrat.idMDS = maitre_de_stage.idMDS
                        LEFT JOIN site ON maitre_de_stage.idSite = site.idSite
                        LEFT JOIN entreprise ON site.idEntreprise = entreprise.idEntreprise
                        WHERE statut='etudiant' AND promo = ? AND parcours = ? AND ((convention_contrat.type_contrat = 'stage' AND utilisateur.typeAnnee = 'M2') OR convention_contrat.type_contrat = 'apprentissage' OR convention_contrat.type_contrat = 'pro')ORDER BY nom");

$req->execute(array($promo, $parcours));
$resultat = $req->fetchAll();

// Créer un nouvel objet Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Ajouter les en-têtes
$sheet->setCellValue('A1', 'Nom');
$sheet->setCellValue('B1', 'Prénom');
$sheet->setCellValue('C1', 'Promo');
$sheet->setCellValue('D1', 'TypeAnnée');
$sheet->setCellValue('E1', 'Parcours');
$sheet->setCellValue('F1', 'Statut');
$sheet->setCellValue('G1', 'Nature');
$sheet->setCellValue('H1', 'Entreprise');
$sheet->setCellValue('I1', 'Site');
$sheet->setCellValue('J1', 'NomMDS');
$sheet->setCellValue('K1', 'NumMDS');
$sheet->setCellValue('L1', 'NomTA');
$sheet->setCellValue('M1', 'DateDébut');
$sheet->setCellValue('N1', 'DateFin');

// Remplir les données
$row = 2;
foreach($resultat as $ligne) {
    $sheet->setCellValue('A' . $row, $ligne['nom']);
    $sheet->setCellValue('B' . $row, $ligne['prenom']);
    $sheet->setCellValue('C' . $row, $ligne['promo']);
    $sheet->setCellValue('D' . $row, $ligne['typeAnnee']);
    $sheet->setCellValue('E' . $row, $ligne['parcours']);
    $sheet->setCellValue('F' . $row, $ligne['etatCM2']);
    $sheet->setCellValue('G' . $row, $ligne['type_contrat']);
    $sheet->setCellValue('H' . $row, $ligne['nomEntreprise']);
    $sheet->setCellValue('I' . $row, $ligne['ville']);
    $sheet->setCellValue('J' . $row, $ligne['nomMDS']);
    $sheet->setCellValue('K' . $row, $ligne['numMDS']);
    $sheet->setCellValue('L' . $row, $ligne['nomTA']);
    $sheet->setCellValue('M' . $row, $ligne['dateDeb']);
    $sheet->setCellValue('N' . $row, $ligne['dateFin']);
    $row++;
}

// Créer un objet Writer pour XLSX
$writer = new Xlsx($spreadsheet);

// Enregistrer le fichier sur le serveur
$writer->save('uploads/liste_etu_postM1.xlsx');

// Télécharger le fichier
$file = 'uploads/liste_etu_postM1.xlsx';
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: ' . filesize($file));
header('Cache-Control: must-revalidate');
header('Pragma: public');
readfile($file);
exit;
?>
