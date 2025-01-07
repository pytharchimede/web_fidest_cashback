<?php
try {
    // Connexion à la base de données
    $con = new PDO('mysql:host=localhost;dbname=fidestci_app_db', 'fidestci_ulrich', '@Succes2019');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification du type de requête
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération du OTP saisi depuis le formulaire
        $otpSaisi = $_POST['otp'];

        // Récupération de l'ID de la fiche depuis la requête GET
        $idFiche = $_GET['id_fiche'];

        // Requête SQL pour récupérer le OTP correspondant à la fiche spécifiée
        $stmt = $con->prepare("SELECT otp FROM fiche WHERE id_fiche = :id_fiche");
        $stmt->bindParam(':id_fiche', $idFiche);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // OTP récupéré de la base de données
            $otpBDD = $row['otp'];

            // Comparaison du OTP saisi avec celui récupéré de la base de données
            if ($otpSaisi == $otpBDD) {
                // Le OTP saisi correspond au OTP de la fiche dans la base de données
                echo json_encode(array('message' => 'Le OTP est valide'));
            } else {
                // Le OTP saisi ne correspond pas au OTP de la fiche dans la base de données
                echo json_encode(array('message' => 'Le OTP est invalide'));
            }
        } else {
            // La fiche spécifiée n'existe pas dans la base de données
            echo json_encode(array('message' => 'La fiche spécifiée n\'existe pas'));
        }
    } else {
        // Réponse d'erreur pour les autres types de requêtes
        http_response_code(405);
        echo json_encode(array('message' => 'Méthode non autorisée'));
    }
} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    http_response_code(500);
    echo json_encode(array('message' => 'Erreur de base de données : ' . $e->getMessage()));
}
?>
