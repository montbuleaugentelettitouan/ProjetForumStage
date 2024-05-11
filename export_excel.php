<?php
require 'vendor/autoload.php'; // Chargez PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include('fonctionality/bdd.php');
include('fonctionality/annee+promo.php');

$req = $bdd->prepare("SELECT
                            utilisateur.idUtilisateur,
                            utilisateur.nom,
                            utilisateur.prenom,
                            utilisateur.email,
                            utilisateur.numTel,
                            utilisateur.etatC,
                            utilisateur.parcours,
                            utilisateur.typeAnnee,
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
                            convention_contrat.statut_contrat               
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
$sheet->setCellValue('C1', 'Parcours');
$sheet->setCellValue('D1', 'Statut');
$sheet->setCellValue('E1', 'Nature');
$sheet->setCellValue('F1', 'Entreprise');
$sheet->setCellValue('G1', 'Site');
$sheet->setCellValue('H1', 'NomMDS');
$sheet->setCellValue('I1', 'NumMDS');
$sheet->setCellValue('J1', 'NomTA');

// Remplir les données
$row = 2;
foreach($resultat as $ligne) {
    $sheet->setCellValue('A' . $row, $ligne['nom']);
    $sheet->setCellValue('B' . $row, $ligne['prenom']);
    $sheet->setCellValue('C' . $row, $ligne['parcours']);
    $sheet->setCellValue('D' . $row, $ligne['statut_contrat']);
    $sheet->setCellValue('E' . $row, $ligne['type_contrat']);
    $sheet->setCellValue('F' . $row, $ligne['nomEntreprise']);
    $sheet->setCellValue('G' . $row, $ligne['ville']);
    $sheet->setCellValue('H' . $row, $ligne['nomMDS']);
    $sheet->setCellValue('I' . $row, $ligne['numMDS']);
    $sheet->setCellValue('J' . $row, $ligne['nomTA']);
    $row++;
}

// Créer un objet Writer pour XLSX
$writer = new Xlsx($spreadsheet);

// Enregistrer le fichier sur le serveur
$writer->save('liste_etu_postM1.xlsx');

// Télécharger le fichier
$file = 'liste_etu_postM1.xlsx';
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: ' . filesize($file));
header('Cache-Control: must-revalidate');
header('Pragma: public');
readfile($file);
exit;


?>
