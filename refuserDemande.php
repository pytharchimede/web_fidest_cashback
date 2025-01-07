<?php
try {
    // Connexion à la base de données
    $con = new PDO('mysql:host=localhost;dbname=fidestci_app_db', 'fidestci_ulrich', '@Succes2019');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification si l'ID de la fiche est passé en paramètre
    if (isset($_GET['idFiche'])) {
        // Récupération de l'ID de la fiche à modifier depuis les paramètres de l'URL
        $idFiche = $_GET['idFiche'];
        
        // Mise à jour de la fiche dans la base de données
        $query = "UPDATE fiche SET etat_fiche=2, sauvegarder=0, decaisse=0, secur_refus='dgfidest' WHERE id_fiche = :id_fiche";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':id_fiche', $idFiche);
        $stmt->execute();
        
        // Réponse de succès
        echo json_encode(array('message' => 'Fiche mise à jour avec succès'));
    } else {
        // Réponse d'erreur si l'ID de la fiche est manquant
        http_response_code(400);
        echo json_encode(array('message' => 'Paramètre manquant : idFiche'));
    }
} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    http_response_code(500);
    echo json_encode(array('message' => 'Erreur de base de données : ' . $e->getMessage()));
}

header('Location: index.php');
?>
