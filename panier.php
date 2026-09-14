<?php
session_start();
include("db.php");

/* Vérifier si l'utilisateur est connecté */
if (!isset($_SESSION['user_id'])) {
    header("Location: user/login.php");
    exit;
}

$total_general = 0;

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mon panier - GameZone</title>

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

    <h1>Mon panier</h1>

    <div class="cart-container">

        <?php if (empty($_SESSION['panier'])) { ?>

            <div class="card">

                <h2>Votre panier est vide</h2>

                <br>

                <a class="btn" href="accueil.php">
                    Continuer mes achats
                </a>

            </div>

        <?php } else { ?>

            <?php foreach ($_SESSION['panier'] as $id => $qte) { ?>

                <?php
                $id = (int) $id;

                $stmt = $pdo->prepare(
                    "SELECT * FROM articles WHERE id = ?"
                );

                $stmt->execute([$id]);

                $produit = $stmt->fetch();

                if (!$produit) {
                    continue;
                }

                $sous_total = $produit['prix'] * $qte;
                $total_general += $sous_total;
                ?>

                <div class="cart-item">

                    <img
                        src="images/<?php echo htmlspecialchars($produit['image']); ?>"
                        alt="<?php echo htmlspecialchars($produit['nom']); ?>"
                    >

                    <div class="cart-info">

                        <h3>
                            <?php echo htmlspecialchars($produit['nom']); ?>
                        </h3>

                        <p>
                            Prix :
                            <?php echo number_format($produit['prix'], 2); ?> DH
                        </p>

                        <p>
                            Quantité :
                            <?php echo $qte; ?>
                        </p>

                        <p>
                            Sous-total :
                            <strong>
                                <?php echo number_format($sous_total, 2); ?> DH
                            </strong>
                        </p>

                    </div>

                    <div class="cart-actions">

                        <a
                            class="btn btn-danger"
                            href="supprimer_panier.php?id=<?php echo $id; ?>"
                        >
                            Supprimer
                        </a>

                    </div>

                </div>

            <?php } ?>

            <div class="total-box">

                <h2>
                    Total :
                    <?php echo number_format($total_general, 2); ?> DH
                </h2>

                <a class="btn" href="accueil.php">
                    Continuer mes achats
                </a>

                <a class="btn btn-danger" href="vider_panier.php">
                    Vider le panier
                </a>

                <a class="btn btn-success" href="commande.php">
                    Passer la commande
                </a>

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
            06 84 29 60 50
        </p>

    </footer>

</body>
</html>