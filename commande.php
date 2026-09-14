<?php
session_start();
include("db.php");

/* Vérifier la connexion */
if (!isset($_SESSION['user_id'])) {
    header("Location: user/login.php");
    exit;
}

/* Vérifier si le panier est vide */
if (empty($_SESSION['panier'])) {
    header("Location: panier.php");
    exit;
}

$total_general = 0;
$produits = [];

foreach ($_SESSION['panier'] as $id => $qte) {

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

    $produits[] = [
        "nom" => $produit["nom"],
        "prix" => $produit["prix"],
        "qte" => $qte,
        "sous_total" => $sous_total
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Passer la commande - GameZone</title>

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

    <h1>Confirmer ma commande</h1>

    <div class="cart-container">

        <div class="card">

            <h2>Résumé de votre commande</h2>

            <?php foreach ($produits as $produit) { ?>

                <p>
                    <?php echo htmlspecialchars($produit["nom"]); ?>

                    -
                    Quantité :
                    <?php echo $produit["qte"]; ?>

                    -
                    <?php echo number_format(
                        $produit["sous_total"],
                        2
                    ); ?> DH
                </p>

            <?php } ?>

            <hr>

            <h2>
                Total :
                <?php echo number_format($total_general, 2); ?> DH
            </h2>

            <br>

            <form action="confirmer_commande.php" method="POST">

                <button type="submit" class="btn btn-success">
                    Confirmer la commande
                </button>

            </form>

            <br>

            <a href="panier.php" class="btn">
                Retour au panier
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