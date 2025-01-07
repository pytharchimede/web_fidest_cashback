<?php
session_start();
include('connex.php');

// Récupérer les valeurs saisies dans le formulaire
$identifiant = $_POST['identifiant'];
$mot_de_passe = $_POST['mot_de_passe'];

// Hasher le mot de passe (vous pouvez utiliser un autre algorithme si nécessaire)
$mot_de_passe_hash = hash("sha512", $mot_de_passe);

// Préparer et exécuter la requête SQL pour vérifier les identifiants dans la base de données
$requete = $con->prepare("SELECT * FROM utilisateur WHERE email_utilisateur = :identifiant AND motpass_utilisateur = :mot_de_passe AND valide_util = :valide");
$requete->execute(array('identifiant' => $identifiant, 'mot_de_passe' => $mot_de_passe_hash, 'valide' => '0'));
$membre = $requete->fetch();
$count = $requete->rowCount();

if ($count > 0) {
    // L'utilisateur est authentifié avec succès, vous pouvez définir des variables de session et rediriger vers une autre page
    $_SESSION['id_utilisateur'] = $membre['id_utilisateur'];
    $_SESSION['nom_utilisateur'] = $membre['nom_utilisateur'];
    $_SESSION['motpass_utilisateur'] = $membre['motpass_utilisateur'];

    // Redirection vers une page de succès ou tableau de bord par exemple
    header("Location: index.php");
    exit();
} else {
    // Échec de l'authentification, rediriger vers la page de connexion avec un message d'erreur
    $_SESSION['erreur_login'] = "Identifiant ou mot de passe incorrect";
    header("Location: authentification.php");
    exit();
}
?>
