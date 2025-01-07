
<?php
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant la variable de session
if ( !isset($_SESSION['id_utilisateur']) || !isset($_SESSION['nom_utilisateur']) || !isset($_SESSION['motpass_utilisateur']) ) {
    // L'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: authentification.php");
    exit(); // Arrêter l'exécution du script pour éviter toute exécution supplémentaire
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste des demandes de décaissement</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
  <div class="container mt-3">
      
        <div class="row">
          <div class="col-md-12">
            <div class="text-center">
              <div class="title-wrapper">
                <h3 class="title">Demandes de décaissement</h3>
              </div>
            </div>
          </div>
        </div>

    
      <?php 
             
             // Récupérer les données JSON depuis l'URL
$json = file_get_contents('https://fidest.ci/logi/getAllRequestApi.php');

// Convertir les données JSON en tableau PHP
$demandesEnAttente = json_decode($json, true);

// Taux de change fictif EUR -> XOF
$tauxDeChange = 1;
$grandTotal = 0; // Initialiser le grand total à 0

// Vérifier si les données ont été récupérées avec succès
if ($demandesEnAttente) {
    // Boucle foreach pour parcourir toutes les demandes
    foreach ($demandesEnAttente as $demande) {
        // Accumuler le montant de chaque demande pour obtenir le grand total
        $grandTotal += $demande['montant_fiche'] * $tauxDeChange;
    }

    // Maintenant, le grand total est correctement calculé
    // Vous pouvez afficher le grand total où vous le souhaitez dans votre code HTML
//    echo "Grand Total: " . number_format($grandTotal, 0, ',', ' ') . " FCFA";
} else {
    // Afficher un message d'erreur si les données n'ont pas été récupérées avec succès
    echo '<p class="text-center">Erreur lors de la récupération des données.</p>';
}
             
             ?>
        <div id="fixedContent">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Application de décaissement</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="#">Décaissements autorisés</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Décaissements refusés</a>
        </li>
      </ul>
        <!-- Ajout du message de bienvenue avec le nom de l'utilisateur -->
      <?php
      // Vérifier si l'utilisateur est connecté
      if (isset($_SESSION['nom_utilisateur'])) {
        echo '<span class="navbar-text">Bonjour ' . $_SESSION['nom_utilisateur'] . '</span>';
      }
      ?>
    </div>
  </nav>

    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="text-center" style="color: green; font-weight: bold;">Total: <?= number_format($grandTotal, 0, ',', ' ') ?> FCFA  
            <button id="exportPDFButton" onclick="window.location.href='export_pdf.php'" class="btn btn-primary">Exporter en PDF</button>
</h2>
        </div>
    </div>
    
    <div class="row mb-4">
      <div class="col-md-12">
        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom, numéro de fiche, affectation ou désignation">
      </div>
    </div>
 </div>
    
    <div id="demandesContainer" class="row">
             <!-- Ajoutez ceci juste avant la boucle foreach -->
             
           

<?php
// Récupérer les données JSON depuis l'URL
$json = file_get_contents('https://fidest.ci/logi/getAllRequestApi.php');

// Convertir les données JSON en tableau PHP
$demandesEnAttente = json_decode($json, true);

// Taux de change fictif EUR -> XOF
$tauxDeChange = 1;
$grandTotal=0;// 1 EUR = 655 XOF

// Vérifier si les données ont été récupérées avec succès
if ($demandesEnAttente) {
    

    
foreach ($demandesEnAttente as $demande) { ?>
  
  
<div class="col-md-6 mb-4">
  <div class="card demande-card" data-id-fiche="<?= $demande['id_fiche'] ?>">
    <img src="https://fidest.ci/img_demande/<?= $demande['photo_beneficiaire'] ?>" alt="Photo du bénéficiaire" class="beneficiaire-img">
    <div class="card-body">
      <!-- Utilisation de classes CSS personnalisées pour appliquer les couleurs -->
      <h5 class="card-title text-danger"><b>Fiche N°</b> <?= $demande['num_fiche'] ?></h5>
      <p class="card-text"><strong>Bénéficiaire:</strong> <?= $demande['beficiaire_fiche'] ?></p>
      <?php
      // Convertir le montant de l'euro en XOF
      $montantXOF = number_format($demande['montant_fiche'] * $tauxDeChange, 0, ',', ' ');
      ?>
      <!-- Utilisation de classes CSS personnalisées pour appliquer les couleurs -->
      <p class="card-text text-success"><strong>Montant:</strong> <?= $montantXOF ?> FCFA</p>
      <p class="card-text"><strong>Affectation:</strong> <?= $demande['lib_affectation'].' '.$demande['lib_chantier'] ?></p>
      <!-- Ajout de la zone de texte pour le motif/description -->
      <div class="form-group">
        <label for="motif_description"><strong>Motif/Description:</strong></label>
        <textarea readonly class="form-control" id="motif_description" name="motif_description" rows="3"><?= $demande['designation_fiche'] ?></textarea>
      </div>
      <p class="card-text"><strong>Date :</strong> <?= $demande['date_creat_fiche'] ?></p>
      <p class="card-text"><strong>N° Pièce :</strong> <?= $demande['num_piece'] ?></p>
      <p class="card-text"><strong>Statut:</strong> <?= $demande['etat_fiche'] ?></p>
      <p class="card-text">
        <span class="badge <?= $demande['approuve'] ? 'badge-success' : 'badge-danger' ?>">Approbation Directeur: <?= $demande['approuve'] ? 'ok' : 'non' ?></span>
        <a href="#" data-toggle="modal" data-target="#modalSignature"><span class="badge <?= $demande['signature_beneficiaire'] ? 'badge-success' : 'badge-danger' ?>">Signature: <?= $demande['signature_beneficiaire'] ? 'ok' : 'non' ?></span></a>
        <a href="#" data-toggle="modal" data-target="#modalCNI"><span class="badge <?= $demande['cni_beneficiaire'] ? 'badge-success' : 'badge-danger' ?>">Image CNI: <?= $demande['cni_beneficiaire'] ? 'ok' : 'non' ?></span></a>
      </p>
<div class="d-flex flex-wrap justify-content-between mt-2">
    <a href="autoriserDemande.php?idFiche=<?= $demande['id_fiche'] ?>" class="btn btn-success mb-2">Autoriser</a>
    <button class="btn btn-warning mb-2" onclick="confirmDecaissement()">Décaisser</button>
    <a href="refuserDemande.php?idFiche=<?= $demande['id_fiche'] ?>" class="btn btn-danger mb-2">Refuser</a>
        <a href="reporterDemande.php?idFiche=<?= $demande['id_fiche'] ?>"  class="btn btn-success mb-2 btn-reporter"  data-id-fiche="<?= $demande['id_fiche'] ?>">Reporter</a>
    <!-- Bouton modifier avec fenêtre modale -->
<button class="btn btn-info mb-2 btn-modifier" data-id-fiche="<?= $demande['id_fiche'] ?>" data-num-fiche="<?= $demande['num_fiche'] ?>" data-toggle="modal" data-target="#modifierMontantModal">Modifier</button>
    <!-- Fenêtre modale -->
    <div class="modal fade" id="modifierMontantModal" tabindex="-1" role="dialog" aria-labelledby="modifierMontantModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
<button class="btn btn-info mb-2" onclick="ouvrirModal()" id="modifierMontantModalLabel">Modifier Fiche</button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Ajoutez ici le formulaire pour modifier le montant -->
                    <!-- Par exemple, un champ input -->
                    <label for="nouveauMontant">Nouveau Montant:</label>
                    <input type="text" id="nouveauMontant" name="nouveauMontant" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" onclick="soumettreModification()">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin de la fenêtre modale -->
    <a href="https://fidest.ci/logi/&_gestion/exportation/pdf/pdf_fiche.php?num_fiche=<?= $demande['num_fiche'] ?>" class="btn btn-primary mb-2">Voir</a>
</div>

    </div>
  </div>
</div>

<!-- Modal de confirmation de décaissement -->
<div class="modal fade" id="confirmDecaissementModal" tabindex="-1" role="dialog" aria-labelledby="confirmDecaissementModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDecaissementModalLabel">Confirmation de décaissement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir procéder au décaissement ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
        <a href="decaisserDemande.php?idFiche=<?= $demande['id_fiche'] ?>" class="btn btn-warning">Oui</a>
      </div>
    </div>
  </div>
</div>

<script>
  function confirmDecaissement() {
    $('#confirmDecaissementModal').modal('show');
  }
</script>


<!-- Modal pour la signature -->
<div class="modal fade" id="modalSignature" tabindex="-1" role="dialog" aria-labelledby="modalSignatureLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSignatureLabel">Signature du bénéficiaire</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="https://fidest.ci/logi/&_gestion/signature/<?php echo $demande['signature_beneficiaire']; ?>" class="img-fluid" alt="Signature du bénéficiaire">
      </div>
    </div>
  </div>
</div>

<!-- Modal pour l'image CNI -->
<div class="modal fade" id="modalCNI" tabindex="-1" role="dialog" aria-labelledby="modalCnilLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCNIlabel">Image CNI du bénéficiaire</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="https://fidest.ci/logi/img/<?php echo $demande['cni_beneficiaire']; ?>" class="img-fluid" alt="Image CNI du bénéficiaire">
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmation de report -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportModalLabel">Sélectionner une date de report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Formulaire pour sélectionner la date de report -->
        <form id="reportForm">
            <div class="form-group">
                <label for="reportDate">Date de report :</label>
                <input type="date" id="reportDate" name="reportDate" class="form-control">
            </div>
            <!-- Champ caché pour stocker l'ID de la fiche -->
            <input type="hidden" id="idFiche" name="idFiche" value="<?php echo $demande['id_fiche']; ?>">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary" id="submitReport">Reporter la demande</button>
      </div>
       </form>
    </div>
  </div>
</div>



<?php 
  }
} else {
  // Afficher un message d'erreur si les données n'ont pas été récupérées avec succès
  echo '<p class="text-center">Erreur lors de la récupération des données.</p>';
}
?>

    </div>
  </div>

 <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/jspdf.js"></script>
  <script>

    // Fonction pour filtrer les demandes en fonction du critère de recherche
function filterDemandes() {
  // Récupérer la valeur saisie dans le champ de recherche
  var searchTerm = $('#searchInput').val().toLowerCase();

  // Parcourir chaque demande
  $('.demande-card').each(function() {
    var $demandeCard = $(this);

    // Récupérer les informations de la demande
    var beneficiaire = $demandeCard.find('.card-text strong:contains("Bénéficiaire:")').text().toLowerCase();
    var numFiche = $demandeCard.find('.card-title').text().toLowerCase();
    var affectation = $demandeCard.find('.card-text strong:contains("Affectation:")').text().toLowerCase();
    var designation = $demandeCard.find('#motif_description').val().toLowerCase();

    // Vérifier si la demande correspond au critère de recherche
    if (beneficiaire.indexOf(searchTerm) > -1 || numFiche.indexOf(searchTerm) > -1 || affectation.indexOf(searchTerm) > -1 || designation.indexOf(searchTerm) > -1) {
      $demandeCard.show(); // Afficher la demande
    } else {
      $demandeCard.hide(); // Masquer la demande si elle ne correspond pas au critère de recherche
    }
  });
}

// Appeler la fonction de filtrage lorsque le contenu du champ de recherche change
$('#searchInput').on('input', filterDemandes);

function ouvrirModal() {
     var numFiche = $(this).data('num-fiche'); // Récupérer le numéro de la fiche à partir du bouton "Modifier" cliqué
    $('#modifierMontantModalLabel').text('Modifier Fiche ' + numFiche); // Afficher le numéro de la fiche dans le titre de la fenêtre modale
    $('#modifierMontantModal').modal('show'); // Afficher la fenêtre modale
}


function soumettreModification() {
    var nouveauMontant = $('#nouveauMontant').val(); // Récupérer la nouvelle valeur du montant
    var idFiche = $('.btn-modifier').data('id-fiche'); // Récupérer l'ID de la fiche à partir du bouton "Modifier" cliqué
    
    $.ajax({
        url: 'modifierMontant.php', // Chemin vers votre script PHP pour la modification
        method: 'POST',
        data: { idFiche: idFiche, nouveauMontant: nouveauMontant }, // Envoyer l'ID de la fiche et le nouveau montant
        success: function(response) {
            console.log(response.message); // Afficher le message de succès
            $('#modifierMontantModal').modal('hide'); // Cacher la fenêtre modale après la soumission
            window.location.reload(); // Recharger la page pour afficher les changements
        },
        error: function(xhr, status, error) {
            alert('Une erreur s\'est produite : ' + error); // Afficher les erreurs
        }
    });
}


    // Fonction pour exporter le contenu de la page en PDF
     function exportPDF() {
        // Créer une nouvelle instance de jsPDF
        var doc = new jsPDF();
    
        // Ajouter le titre au PDF
        doc.text('Liste des demandes de décaissement', 10, 10);
    
        // Parcourir chaque demande et ajouter ses détails au PDF
        $('.demande-card').each(function(index) {
            var $demandeCard = $(this);
            var numFiche = $demandeCard.find('.card-title').text();
            var beneficiaire = $demandeCard.find('.card-text strong:contains("Bénéficiaire:")').text();
            var montant = $demandeCard.find('.card-text strong:contains("Montant:")').text();
            var affectation = $demandeCard.find('.card-text strong:contains("Affectation:")').text();
            var motif = $demandeCard.find('.form-control').val();
    
            // Ajouter les détails de la demande au PDF
            doc.text('Fiche N°: ' + numFiche, 10, 20 + index * 10);
            doc.text('Bénéficiaire: ' + beneficiaire, 10, 30 + index * 10);
            doc.text('Montant: ' + montant, 10, 40 + index * 10);
            doc.text('Affectation: ' + affectation, 10, 50 + index * 10);
            doc.text('Motif/Description: ' + motif, 10, 60 + index * 10);
        });
    
        // Enregistrer le PDF
        doc.save('liste_demandes_decaissement.pdf');
    }


    // Associer la fonction d'exportation au clic sur le bouton
    $('#exportPDFButton').click(function() {
        console.log('Click sur boutton exportPDF');
        exportPDF();
    });
    
    
$(document).ready(function() {
    $('.btn-reporter').click(function(e) {
        e.preventDefault();
        $('#reportModal').modal('show'); // Afficher la modal de report
    });

    $('#reportForm').submit(function(e) {
        e.preventDefault();
        var idFiche = $('#idFiche').val();
        var reportDate = $('#reportDate').val();
        
        // Envoyer une requête Ajax pour mettre à jour la date de report dans la base de données
        $.ajax({
            url: 'reporterDemande.php', // Chemin vers le script PHP pour reporter la demande
            method: 'GET',
            data: { idFiche: idFiche, reportDate: reportDate },
            success: function(response) {
                // Afficher un message de succès ou recharger la page
                alert('La demande a été reportée avec succès.');
                window.location.reload();
            },
            error: function(xhr, status, error) {
                // Afficher un message d'erreur en cas d'échec de la requête Ajax
                alert('Une erreur s\'est produite lors du report de la demande : ' + error);
            }
        });
    });
});
  
</script>


</body>
</html>
