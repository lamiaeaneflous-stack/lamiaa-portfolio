<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = trim($_POST['nom']);
    $prix = $_POST['prix'];
    $description = trim($_POST['description']);
    $stock = $_POST['stock'];

    if (
        empty($nom) ||
        empty($prix) ||
        empty($description) ||
        $stock === ""
    ) {
        $message = "Tous les champs sont obligatoires.";

    } elseif (!is_numeric($prix) || $prix <= 0) {
        $message = "Prix invalide.";

    } elseif (!is_numeric($stock) || $stock < 0) {
        $message = "Stock invalide.";

    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        $message = "Veuillez choisir une image.";

    } else {

        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];

        $extension = strtolower(
            pathinfo($image_name, PATHINFO_EXTENSION)
        );

        $extensions_autorisees = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];

        if (!in_array($extension, $extensions_autorisees)) {

            $message = "Format d'image non autorisé.";

        } elseif ($image_size > 5 * 1024 * 1024) {

            $message = "L'image ne doit pas dépasser 5 MB.";

        } else {

            $nouveau_nom = uniqid("produit_", true) . "." . $extension;

            $destination = "../images/" . $nouveau_nom;

            if (move_uploaded_file($image_tmp, $destination)) {

                $stmt = $pdo->prepare(
                    "INSERT INTO articles
                    (nom, prix, image, description, stock)
                    VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->execute([
                    $nom,
                    $prix,
                    $nouveau_nom,
                    $description,
                    $stock
                ]);

                header("Location: crud.php");
                exit();

            } else {

                $message = "Erreur lors de l'upload de l'image.";

            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ajouter Produit - GameZone</title>

    <link rel="stylesheet" href="../style1.css">
</head>

<body>

    <nav class="navbar">

        <div class="logo">
            GameZone Admin
        </div>

        <div class="panier">
            <a href="crud.php">Dashboard</a>
            <a href="../accueil.php">Boutique</a>
            <a href="logout.php">Déconnexion</a>
        </div>

    </nav>

    <div class="form-container">

        <h2>Ajouter un produit</h2>

        <?php if (!empty($message)) { ?>

            <div class="error">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">

                <label for="nom">
                    Nom du produit
                </label>

                <input
                    type="text"
                    id="nom"
                    name="nom"
                    required
                >

            </div>

            <div class="form-group">

                <label for="prix">
                    Prix en DH
                </label>

                <input
                    type="number"
                    id="prix"
                    name="prix"
                    step="0.01"
                    min="0"
                    required
                >

            </div>

            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    required
                ></textarea>

            </div>

            <div class="form-group">

                <label for="stock">
                    Stock
                </label>

                <input
                    type="number"
                    id="stock"
                    name="stock"
                    min="0"
                    required
                >

            </div>

            <div class="form-group">

                <label for="image">
                    Image
                </label>

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                    required
                >

            </div>

            <button type="submit">
                Ajouter
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