<?php
require_once('tcpdf/tcpdf.php'); // Chemin vers la bibliothèque TCPDF

// Récupérer les données JSON depuis l'URL
$json = file_get_contents('https://fidest.ci/logi/getAllRequestApi.php');
$demandesEnAttente = json_decode($json, true);

// Créer un nouveau document PDF en mode paysage
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false); // 'L' pour paysage, 'mm' pour millimètres, 'A4' pour le format de page
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Alex Braud');
$pdf->SetTitle('Demandes de Décaissement');
$pdf->SetSubject('Liste des demandes de décaissement');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Ajouter une page
$pdf->AddPage();

// Définir la police (réduite)
$pdf->SetFont('helvetica', 'B', 14); // Réduire la police
$pdf->Cell(0, 10, 'Demandes de Décaissement', 0, 1, 'C');

// Ajouter une ligne vide
$pdf->Ln(5);

// Définir le style du tableau
$pdf->SetFillColor(220, 220, 220); // Couleur de fond
$pdf->SetFont('helvetica', 'B', 9); // Taille de police pour l'en-tête

// En-tête du tableau
$pdf->Cell(10, 8, 'N°', 1, 0, 'C', 1);
$pdf->Cell(25, 8, 'Bénéficiaire', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'Montant', 1, 0, 'C', 1);
$pdf->Cell(90, 8, 'Affectation', 1, 0, 'C', 1);
$pdf->Cell(75, 8, 'Désignation', 1, 0, 'C', 1);
$pdf->Cell(60, 8, 'Détail', 1, 1, 'C', 1);


// Définir une nouvelle police pour le contenu
$pdf->SetFont('helvetica', '', 8); // Taille de police pour le contenu

$total = 0;

// Afficher les données sous forme de tableau
foreach ($demandesEnAttente as $demande) {
    $pdf->Cell(10, 8, $demande['num_fiche'], 1, 0, 'C');
    $pdf->Cell(25, 8, $demande['beficiaire_fiche'], 1, 0, 'C');
    $pdf->Cell(20, 8, number_format($demande['montant_fiche'], 0, ',', ' ') . ' FCFA', 1, 0, 'C');
    $pdf->Cell(90, 8, $demande['lib_affectation'] . ' ' . $demande['lib_chantier'], 1, 0, 'C');
    $pdf->Cell(75, 8, $demande['designation_fiche'], 1, 0, 'C'); // Nouvelle colonne Désignation
    $pdf->Cell(60, 8, $demande['precision_fiche'], 1, 1, 'C'); // Nouvelle colonne Désignation

    $total += $demande['montant_fiche'];
}

// Définir la police (réduite)
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Total = '.number_format($total, 0, ',', ' ') . ' FCFA', 0, 1, 'C');

// Générer le PDF
$pdf->Output('demandes_decaissement.pdf', 'D'); // 'D' pour télécharger le fichier
?>
