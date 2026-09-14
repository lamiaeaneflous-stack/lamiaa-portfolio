<?php
session_start();
include("db.php");

$sql = $pdo->query("SELECT * FROM articles ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameZone - Accueil</title>
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

    <!-- TITRE -->
    <h1>Gaming Products</h1>

    <!-- PRODUITS -->
    <div class="container">

        <?php while ($row = $sql->fetch()) { ?>

            <div class="card">

                <img
                    src="images/<?php echo htmlspecialchars($row['image']); ?>"
                    alt="<?php echo htmlspecialchars($row['nom']); ?>"
                >

                <h3>
                    <?php echo htmlspecialchars($row['nom']); ?>
                </h3>

                <p class="price">
                    <?php echo number_format($row['prix'], 2); ?> DH
                </p>

                <p>
                    <?php echo htmlspecialchars($row['description']); ?>
                </p>

                <!-- STOCK -->
                <?php if ($row['stock'] > 0) { ?>

                    <p class="stock">
                        Stock disponible :
                        <strong>
                            <?php echo $row['stock']; ?>
                        </strong>
                    </p>

                <?php } else { ?>

                    <p class="stock-out">
                        Produit épuisé
                    </p>

                <?php } ?>

                <a
                    class="btn"
                    href="details.php?id=<?php echo $row['id']; ?>"
                >
                    Details
                </a>

                <!-- AJOUTER AU PANIER -->
                <?php if ($row['stock'] > 0) { ?>

                    <a
                        class="btn btn-success"
                        href="ajouter_panier.php?id=<?php echo $row['id']; ?>&qte=1"
                    >
                        Ajouter au panier
                    </a>

                <?php } else { ?>

                    <p class="error">
                        Ce produit n'est plus disponible.
                    </p>

                <?php } ?>

            </div>

        <?php } ?>

    </div>

    <!-- FOOTER -->
    <footer>

        <p>
            © 2026 GameZone - Tous droits réservés
        </p>

        <p>
            Contact : gamezone@gmail.com |
            Téléphone : 06 84 29 60 50
        </p>

        <p>
            <a href="accueil.php">Accueil</a>
            <a href="panier.php">Panier</a>
            <a href="user/login.php">Connexion</a>
        </p>

    </footer>

</body>
</html>