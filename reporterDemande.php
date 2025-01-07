<?php
try {
    // Vérification si l'ID de la fiche et la date de report sont passés en paramètre
    if (isset($_GET['idFiche']) && isset($_GET['reportDate'])) {
        // Récupération de l'ID de la fiche à reporter et la nouvelle date de report
        $idFiche = $_GET['idFiche'];
        $reportDate = $_GET['reportDate'];
        
        var_dump($idFiche, $reportDate);
        
        // Connexion à la base de données
        $con = new PDO('mysql:host=localhost;dbname=fidestci_app_db', 'fidestci_ulrich', '@Succes2019');
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Mise à jour de la date de report dans la base de données
        $query = "UPDATE fiche SET etat_fiche=0, sauvegarder=0, decaisse=0, approuve=1, secur_valid='', date_decaissement = :report_date WHERE id_fiche = :id_fiche";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':id_fiche', $idFiche);
        $stmt->bindParam(':report_date', $reportDate);
        $stmt->execute();
        
        
        
        // Réponse de succès
        echo json_encode(array('message' => 'Date de report mise à jour avec succès'));
    } else {
        // Réponse d'erreur si l'ID de la fiche ou la date de report sont manquants
        http_response_code(400);
        echo json_encode(array('message' => 'Paramètres manquants : idFiche et reportDate'));
    }
} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    http_response_code(500);
    echo json_encode(array('message' => 'Erreur de base de données : ' . $e->getMessage()));
}
?>
