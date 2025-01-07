<?php

require_once 'OrangeSMS.php';

try {
    // Connexion à la base de données
    $con = new PDO('mysql:host=localhost;dbname=fidestci_app_db', 'fidestci_ulrich', '@Succes2019');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification du type de requête
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Récupération des données JSON de la requête
        $requestData = json_decode(file_get_contents('php://input'), true);

        // Vérification si les données nécessaires sont présentes
        if (isset($_GET['idFiche'])) {
            // Récupération de l'ID de la fiche à modifier depuis l'URL
            $idFiche = $_GET['idFiche'];
            
            // Mise à jour de la fiche dans la base de données
            $query = "UPDATE fiche SET etat_fiche=1, sauvegarder=0, decaisse=0 WHERE id_fiche = :id_fiche";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':id_fiche', $idFiche);
            $stmt->execute();
            
            // Générer un code OTP aléatoire de 6 chiffres
            $otp = rand(100000, 999999);
            
            //Vereifiere si la fiche a deja un otp
            $exit = $con->prepare('SELECT * FROM fiche WHERE id_fiche=:id_fiche');
            $exit->execute(array('id_fiche'=>$idFiche));
            $infExit = $exit->fetch();
            
          if ($infExit && !empty($infExit['otp'])) {
            // Si la fiche a déjà un OTP, redirige vers la page de confirmation
            header('Location: confirme_otp.php?id_fiche=' . $idFiche);
        } else {
            // Si la fiche n'a pas encore d'OTP, générez-en un nouveau
            // Mettre à jour le code OTP dans la base de données
            $updateOtpQuery = "UPDATE fiche SET otp=:otp WHERE id_fiche=:id_fiche";
            $stmt = $con->prepare($updateOtpQuery);
            $stmt->bindParam(':otp', $otp);
            $stmt->bindParam(':id_fiche', $idFiche);
            $stmt->execute();
            
            // Mettre à jour le code OTP dans la base de données
            $updateOtpQuery = "UPDATE fiche SET otp=:otp WHERE id_fiche=:id_fiche";
            $stmt = $con->prepare($updateOtpQuery);
            $stmt->bindParam(':otp', $otp);
            $stmt->bindParam(':id_fiche', $idFiche);
            $stmt->execute();

            // Récupérer le numéro de téléphone du bénéficiaire à partir de la table fiche
            $stmt = $con->prepare("SELECT tel_beneficiaire_fiche, beficiaire_fiche, montant_fiche FROM fiche WHERE id_fiche=:id_fiche");
            $stmt->execute(array('id_fiche' => $idFiche));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($row && isset($row['tel_beneficiaire_fiche'])) {
                $telephone_beneficiaire = $row['tel_beneficiaire_fiche'];
                $beneficiaire_fiche = $row['beficiaire_fiche'];
                $montant_fiche = $row['montant_fiche'];

                // Données pour l'envoi du SMS
                /*
                $apiKey = "IbILNr1bs1sCV1RNuvaB7amMDS9cUGG3";
                $apiToken = "bOdV1680020257";
                $senderId = "FIDEST";
                */
                /*
                $message = $beneficiaire_fiche.',
                votre somme de '.$montant_fiche.' a été décaissée. Veuillez transmettre votre OTP '.$otp.' pour finaliser. FIDEST-BANAMUR';
        */
                // Encodez le message pour inclure des caractères spéciaux dans l'URL
               // $encodedMessage = urlencode($message);

                // URL pour l'envoi du SMS
               // $url = 'https://panel.smsing.app/smsAPI?sendsms=null&apikey='.$apiKey.'&apitoken='.$apiToken.'&type=sms&from='.$senderId.'&to='.$telephone_beneficiaire.'&text='.$message;

                // Envoi du SMS
                /*
          $curl = curl_init();
    
     curl_setopt_array($curl, array(
       CURLOPT_URL => 'https://panel.smsing.app/smsAPI?sendsms=null&apikey=IbILNr1bs1sCV1RNuvaB7amMDS9cUGG3&apitoken=bOdV1680020257&type=sms&from='.$senderId.'&to=225'.$telephone_beneficiaire.'&text='.$encodedMessage,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'GET',
     ));
    
     $response = curl_exec($curl);
    
     curl_close($curl);
     */
     
         // Importation de la classe
        $clientId = 'Xb6Wgzi9iCWFAJakdSNPCpMGBx9ixxF0';
        $clientSecret = 'xOXZ4QTDf7bLfGk3';
        
        try {
            // Instanciation de la classe OrangeSMS
            $orangeSMS = new OrangeSMS($clientId, $clientSecret);
        
            // Format du numéro de téléphone sans ajouter de 'tel:' supplémentaire
            $recipientPhoneNumber = '+225' . $telephone_beneficiaire;
            $senderPhoneNumber = '+2250748367710';
        
            // Envoi d'un SMS
            $message = 'Votre somme de ' . $montant_fiche . ' a été décaissée. Veuillez transmettre votre OTP ' . $otp . ' pour finaliser. FIDEST-BANAMUR';
            $response = $orangeSMS->sendSMS('tel:' . $recipientPhoneNumber, 'tel:' . $senderPhoneNumber, $message);
            print_r($response);
        
            // Vérification du solde SMS
            $balance = $orangeSMS->getSMSBalance();
            print_r($balance);
        
            // Vérification de l'usage des SMS
            $usage = $orangeSMS->getSMSUsage();
            print_r($usage);
        
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
        //Fin sms NOTIF EXPEDITEUR

                if ($response === false) {
                    echo "Erreur lors de l'envoi du SMS.";
                } else {
                    echo "SMS envoyé avec succès";
                    header('Location: confirme_otp.php?id_fiche='.$idFiche);
                }
            } else {
                echo "Numéro de téléphone du bénéficiaire non trouvé dans la base de données.";
            }

            // Réponse de succès
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'OTP généré avec succès ! '));
            
        }
        } else {
            // Réponse d'erreur si des données sont manquantes
            http_response_code(400);
            echo json_encode(array('message' => 'Paramètres manquants'));
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
