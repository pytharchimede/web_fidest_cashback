<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">
    <div class="container-fluid h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-lg-4 col-md-6 col-sm-8 mt-5">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center">Connexion</h2>
                        <form action="login.php" method="POST">
                            <div class="form-group">
                                <label for="identifiant">Identifiant (email) :</label>
                                <input type="email" id="identifiant" name="identifiant" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="mot_de_passe">Mot de passe :</label>
                                <input type="password" id="mot_de_passe" name="mot_de_passe" required class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                        </form>
                        <?php
                        session_start();
                        if(isset($_SESSION['erreur_login'])) {
                            echo '<p class="text-danger text-center mt-3">' . $_SESSION['erreur_login'] . '</p>';
                            unset($_SESSION['erreur_login']);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
