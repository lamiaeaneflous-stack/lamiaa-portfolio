<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login1.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mon profil - GameZone</title>

    <link rel="stylesheet" href="../style1.css">
</head>

<body>

    <nav class="navbar">

        <div class="logo">
            GameZone
        </div>

        <div class="contact">
            Mon espace personnel
        </div>

        <div class="panier">
            <a href="../accueil.php">Accueil</a>
            <a href="../panier.php">Panier</a>
            <a href="logout.php">Déconnexion</a>
        </div>

    </nav>

    <div class="form-container">

        <h2>Mon profil</h2>

        <div class="form-group">

            <label>Nom complet</label>

            <input
                type="text"
                value="<?php echo htmlspecialchars($_SESSION['user_nom']); ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Email</label>

            <input
                type="email"
                value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>"
                readonly
            >

        </div>

        <a class="btn btn-dark" href="../accueil.php">
            Retour à l'accueil
        </a>

        <a class="btn btn-danger" href="logout.php">
            Déconnexion
        </a>

    </div>

    <footer>

        <p>
            © 2026 GameZone - Tous droits réservés
        </p>

    </footer>

</body>
</html>