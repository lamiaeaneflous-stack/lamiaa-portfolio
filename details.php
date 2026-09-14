<?php
session_start();
include("db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: accueil.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);

$produit = $stmt->fetch();

if (!$produit) {
    header("Location: accueil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($produit['nom']); ?> - GameZone
    </title>

    <link rel="stylesheet" href="style1.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">

        <div class="logo">
            GameZone
        </div>

        <div class="contact">
            Appelez-nous : 06 84 29 60 50 |
            Email : gamezone@gmail.com
        </div>

        <div class="panier">
            <a href="accueil.php">Accueil</a>
            <a href="panier.php">Panier</a>
            <a href="user/login.php">Compte</a>
            <a href="admin/login.php">Admin</a>
        </div>

    </nav>

    <!-- DETAILS -->
    <div class="details-container">

        <img
            class="details-image"
            src="images/<?php echo htmlspecialchars($produit['image']); ?>"
            alt="<?php echo htmlspecialchars($produit['nom']); ?>"
        >

        <div class="details-info">

            <h2>
                <?php echo htmlspecialchars($produit['nom']); ?>
            </h2>

            <p>
                <?php echo nl2br(htmlspecialchars($produit['description'])); ?>
            </p>

            <p class="price">
                <?php echo number_format($produit['prix'], 2); ?> DH
            </p>

            <p>
                Stock disponible :
                <strong><?php echo $produit['stock']; ?></strong>
            </p>

            <?php if ($produit['stock'] > 0) { ?>

                <form action="ajouter_panier.php" method="GET">

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo $produit['id']; ?>"
                    >

                    <div class="form-group">

                        <label for="qte">
                            Quantité :
                        </label>

                        <input
                            type="number"
                            id="qte"
                            name="qte"
                            min="1"
                            max="<?php echo $produit['stock']; ?>"
                            value="1"
                            required
                        >

                    </div>

                    <button type="submit">
                        Ajouter au panier
                    </button>

                </form>

            <?php } else { ?>

                <p class="error">
                    Ce produit est épuisé.
                </p>

            <?php } ?>

            <br>

            <a class="btn btn-dark" href="accueil.php">
                Retour aux produits
            </a>

        </div>

    </div>

    <!-- FOOTER -->
    <footer>

        <p>
            © 2026 GameZone - Tous droits réservés
        </p>

        <p>
            Contact : gamezone@gmail.com |
            06 84 29 60 50
        </p>

    </footer>

</body>
</html>