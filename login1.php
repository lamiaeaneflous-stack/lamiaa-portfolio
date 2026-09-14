<?php
session_start();
include("../db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {

        $message = "Veuillez remplir tous les champs.";

    } else {

        $stmt = $pdo->prepare(
            "SELECT * FROM utilisateurs WHERE email = ?"
        );

        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if (
            $user &&
            password_verify($password, $user['password'])
        ) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: profil.php");
            exit();

        } else {

            $message = "Email ou mot de passe incorrect.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Connexion - GameZone</title>

    <link rel="stylesheet" href="../style1.css">
</head>

<body>

    <nav class="navbar">

        <div class="logo">
            GameZone
        </div>

        <div class="contact">
            Bienvenue sur GameZone
        </div>

        <div class="panier">
            <a href="../accueil.php">Accueil</a>
            <a href="../panier.php">Panier</a>
            <a href="register.php">Inscription</a>
        </div>

    </nav>

    <div class="form-container">

        <h2>Connexion</h2>

        <?php if (!empty($message)) { ?>

            <div class="error">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>

        <form method="POST">

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Votre email"
                    required
                >

            </div>

            <div class="form-group">

                <label for="password">
                    Mot de passe
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Votre mot de passe"
                    required
                >

            </div>

            <button type="submit">
                Se connecter
            </button>

        </form>

        <p style="text-align:center; margin-top:20px;">
            Vous n'avez pas de compte ?
            <a href="register.php">
                Créer un compte
            </a>
        </p>

    </div>

    <footer>

        <p>
            © 2026 GameZone - Tous droits réservés
        </p>

        <p>
            Contact : gamezone@gmail.com
        </p>

    </footer>

</body>
</html>