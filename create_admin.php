<?php
include("../db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (
        empty($username) ||
        empty($password) ||
        empty($confirm_password)
    ) {
        $message = "Tous les champs sont obligatoires.";

    } elseif ($password !== $confirm_password) {
        $message = "Les mots de passe ne correspondent pas.";

    } elseif (strlen($password) < 8) {
        $message = "Le mot de passe doit contenir au moins 8 caractères.";

    } else {

        $check = $pdo->prepare(
            "SELECT id FROM admins WHERE username = ?"
        );

        $check->execute([$username]);

        if ($check->fetch()) {

            $message = "Ce nom d'utilisateur existe déjà.";

        } else {

            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare(
                "INSERT INTO admins (username, password)
                 VALUES (?, ?)"
            );

            $stmt->execute([
                $username,
                $password_hash
            ]);

            $message = "Admin créé avec succès. Supprime maintenant ce fichier.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Créer Admin - GameZone</title>

    <link rel="stylesheet" href="../style1.css">
</head>

<body>

    <nav class="navbar">

        <div class="logo">
            GameZone Admin
        </div>

        <div class="panier">
            <a href="../accueil.php">Accueil</a>
        </div>

    </nav>

    <div class="form-container">

        <h2>Créer un compte Admin</h2>

        <?php if (!empty($message)) { ?>

            <div class="success">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>

        <form method="POST">

            <div class="form-group">

                <label for="username">
                    Nom d'utilisateur
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
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
                    minlength="8"
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
                    minlength="8"
                    required
                >

            </div>

            <button type="submit">
                Créer Admin
            </button>

        </form>

    </div>

</body>
</html>