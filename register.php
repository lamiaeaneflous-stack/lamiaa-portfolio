<?php
session_start();
include("../db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (
        empty($nom) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {
        $message = "Tous les champs sont obligatoires.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide.";

    } elseif ($password !== $confirm_password) {
        $message = "Les mots de passe ne correspondent pas.";

    } elseif (strlen($password) < 6) {
        $message = "Le mot de passe doit contenir au moins 6 caractères.";

    } else {

        $check = $pdo->prepare(
            "SELECT id FROM utilisateurs WHERE email = ?"
        );

        $check->execute([$email]);

        if ($check->fetch()) {

            $message = "Cet email existe déjà.";

        } else {

            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare(
                "INSERT INTO utilisateurs (nom, email, password)
                 VALUES (?, ?, ?)"
            );

            $stmt->execute([
                $nom,
                $email,
                $password_hash
            ]);

            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inscription - GameZone</title>

    <link rel="stylesheet" href="../style1.css">
</head>

<body>

    <nav class="navbar">

        <div class="logo">
            GameZone
        </div>

        <div class="contact">
            Créez votre compte GameZone
        </div>

        <div class="panier">
            <a href="../accueil.php">Accueil</a>
            <a href="../panier.php">Panier</a>
            <a href="login1.php">Connexion</a>
        </div>

    </nav>

    <div class="form-container">

        <h2>Créer un compte</h2>

        <?php if (!empty($message)) { ?>

            <div class="error">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>

        <form method="POST">

            <div class="form-group">

                <label for="nom">
                    Nom complet
                </label>

                <input
                    type="text"
                    id="nom"
                    name="nom"
                    placeholder="Votre nom"
                    required
                >

            </div>

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="exemple@gmail.com"
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
                    placeholder="Minimum 6 caractères"
                    required
                >

            </div>

            <div class="form-group">

                <label for="confirm_password">
                    Confirmer le mot de passe
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Retapez votre mot de passe"
                    required
                >

            </div>

            <button type="submit">
                S'inscrire
            </button>

        </form>

        <p style="text-align:center; margin-top:20px;">
            Vous avez déjà un compte ?
            <a href="login.php">
                Se connecter
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