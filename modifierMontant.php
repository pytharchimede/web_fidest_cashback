<?php
try {
    // Connexion à la base de données
    $con = new PDO('mysql:host=localhost;dbname=fidestci_app_db', 'fidestci_ulrich', '@Succes2019');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification si l'ID de la fiche et le nouveau montant sont envoyés via POST
    if (isset($_POST['idFiche'], $_POST['nouveauMontant'])) {
        // Récupération de l'ID de la fiche et du nouveau montant
        $idFiche = $_POST['idFiche'];
        $nouveauMontant = $_POST['nouveauMontant'];
        
        // Mise à jour du montant dans la base de données
        $query = "UPDATE fiche SET montant_fiche = :nouveauMontant WHERE id_fiche = :id_fiche";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':id_fiche', $idFiche);
        $stmt->bindParam(':nouveauMontant', $nouveauMontant);
        $stmt->execute();
        
        // Réponse de succès
        echo json_encode(array('message' => 'Montant mis à jour avec succès'));
    } else {
        // Réponse d'erreur si les paramètres sont manquants
        http_response_code(400);
        echo json_encode(array('message' => 'Paramètres manquants'));
    }
} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    http_response_code(500);
    echo json_encode(array('message' => 'Erreur de base de données : ' . $e->getMessage()));
}
?>
