<?php
session_start();
include("../db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {

        $message = "Veuillez remplir tous les champs.";

    } else {

        $stmt = $pdo->prepare(
            "SELECT * FROM admins WHERE username = ?"
        );

        $stmt->execute([$username]);

        $admin = $stmt->fetch();

        if (
            $admin &&
            password_verify($password, $admin['password'])
        ) {

            session_regenerate_id(true);

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            header("Location: crud.php");
            exit();

        } else {

            $message = "Nom d'utilisateur ou mot de passe incorrect.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Connexion Admin - GameZone</title>

    <link rel="stylesheet" href="../style1.css">
</head>

<body>

    <nav class="navbar">

        <div class="logo">
            GameZone Admin
        </div>

        <div class="panier">
            <a href="../accueil.php">Boutique</a>
            <a href="../panier.php">Panier</a>
        </div>

    </nav>

    <div class="form-container">

        <h2>Connexion Admin</h2>

        <?php if (!empty($message)) { ?>

            <div class="error">
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
                    required
                >

            </div>

            <button type="submit">
                Se connecter
            </button>

        </form>

    </div>

    <footer>

        <p>
            © 2026 GameZone - Administration
        </p>

    </footer>

</body>
</html>