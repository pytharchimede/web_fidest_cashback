<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de OTP</title>
    <!-- Inclure SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Styles CSS pour le formulaire */
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 300px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #007bff;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        #countdown {
            font-size: 18px;
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Confirmation de OTP</h1>
        <form id="otpForm" action="">
            <input type="text" id="otpInput" name="otp" placeholder="Entrez votre OTP" autofocus>
            <input type="submit" id="submitBtn" value="Valider">
        </form>
        <div id="countdown"></div>
    </div>

    <script>

        document.getElementById('otpForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêcher la soumission du formulaire par défaut

    // Récupérer la valeur du champ de saisie du OTP
    var otpValue = document.getElementById('otpInput').value;

    // Récupérer l'ID de la fiche à partir de la requête GET
    var urlParams = new URLSearchParams(window.location.search);
    var idFiche = urlParams.get('id_fiche');

    // Appeler une fonction de vérification du OTP avec l'ID de la fiche
    verifyOTP(otpValue, idFiche);
});

        // Modification de la fonction verifyOTP pour inclure l'ID de la fiche dans la requête AJAX
function verifyOTP(otp, idFiche) {
    // Envoi des données du formulaire via AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'verifOtp.php?id_fiche=' + idFiche, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    console.log('Fiche Numero : '+idFiche);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Traitement de la réponse
            var response = JSON.parse(xhr.responseText);
            if (response.message === 'Le OTP est valide') {
                // Affichage d'une Sweet Alert en cas de succès
                Swal.fire({
                    title: 'Confirmation de OTP',
                    text: 'Le OTP est valide.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });

                // Démarrer le compte à rebours de 5 secondes
                startCountdown();
            } else {
                // Affichage d'une Sweet Alert en cas d'échec
                Swal.fire({
                    title: 'Confirmation de OTP',
                    text: 'Le OTP est invalide.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } else {
            // Affichage d'une Sweet Alert en cas d'erreur de connexion
            Swal.fire({
                title: 'Erreur',
                text: 'Une erreur est survenue lors de la vérification du OTP.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    };
    xhr.onerror = function() {
        // Affichage d'une Sweet Alert en cas d'erreur de connexion
        Swal.fire({
            title: 'Erreur',
            text: 'Une erreur est survenue lors de la vérification du OTP.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    };
    xhr.send('otp=' + encodeURIComponent(otp));
}


        function startCountdown() {
    var seconds = 5;
    var countdownElement = document.getElementById('countdown');
    var idFiche = <?php echo $_GET['id_fiche']; ?>; // Récupération de l'ID de la fiche

    var countdownInterval = setInterval(function() {
        if (seconds > 0) {
            countdownElement.innerText = 'Redirection dans ' + seconds + ' secondes...';
            seconds--;
        } else {
            clearInterval(countdownInterval);
            // Redirection vers index.php après le compte à rebours
            window.location.href = 'index.php';
        }
    }, 1000);

    // Appel de verifyOTP avec l'ID de la fiche
    verifyOTP('otp', idFiche);
}

    </script>
</body>
</html>
