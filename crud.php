<?php
session_start();
include("../db.php");

/* Protection Admin */
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

/* Récupérer les produits */
$sql = $pdo->query(
    "SELECT * FROM articles ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - GameZone</title>

    <link rel="stylesheet" href="../style1.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">

        <div class="logo">
            GameZone Admin
        </div>

        <div class="contact">
            Bienvenue,
            <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
        </div>

        <div class="panier">
            <a href="../accueil.php">Boutique</a>
            <a href="../panier.php">Panier</a>
            <a href="ajouter.php">Ajouter</a>
            <a href="logout.php">Déconnexion</a>
        </div>

    </nav>

    <!-- TITRE -->
    <h1>Dashboard Admin</h1>

    <div class="admin-container">

        <!-- ACTIONS -->
        <div style="margin-bottom: 25px;">

            <a class="btn btn-success" href="ajouter.php">
                + Ajouter un produit
            </a>

        </div>

        <!-- TABLE -->
        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Description</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if ($sql->rowCount() > 0) { ?>

                        <?php while ($row = $sql->fetch()) { ?>

                            <tr>

                                <td>
                                    <?php echo $row['id']; ?>
                                </td>

                                <td>

                                    <img
                                        src="../images/<?php echo htmlspecialchars($row['image']); ?>"
                                        alt="<?php echo htmlspecialchars($row['nom']); ?>"
                                    >

                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['nom']); ?>
                                </td>

                                <td>
                                    <?php echo number_format($row['prix'], 2); ?>
                                    DH
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </td>

                                <td>

                                    <?php if ($row['stock'] > 0) { ?>

                                        <span style="color: green; font-weight: bold;">
                                            <?php echo $row['stock']; ?>
                                        </span>

                                    <?php } else { ?>

                                        <span style="color: red; font-weight: bold;">
                                            Épuisé
                                        </span>

                                    <?php } ?>

                                </td>

                                <td>

                                    <a
                                        class="btn"
                                        href="modifier.php?id=<?php echo $row['id']; ?>"
                                    >
                                        Modifier
                                    </a>

                                    <a
                                        class="btn btn-danger"
                                        href="supprimer.php?id=<?php echo $row['id']; ?>"
                                        onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?');"
                                    >
                                        Supprimer
                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>

                            <td colspan="7" style="text-align: center;">
                                Aucun produit disponible.
                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- FOOTER -->
    <footer>

        <p>
            © 2026 GameZone - Administration
        </p>

        <p>
            Connecté en tant que :
            <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
        </p>

    </footer>

</body>
</html>