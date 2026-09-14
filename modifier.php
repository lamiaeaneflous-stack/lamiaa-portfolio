<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: crud.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare(
    "SELECT * FROM articles WHERE id = ?"
);

$stmt->execute([$id]);

$produit = $stmt->fetch();

if (!$produit) {
    header("Location: crud.php");
    exit();
}

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

    } else {

        $image_finale = $produit['image'];

        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === 0
        ) {

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

                    $image_finale = $nouveau_nom;

                } else {

                    $message = "Erreur lors de l'upload.";

                }
            }
        }

        if (empty($message)) {

            $update = $pdo->prepare(
                "UPDATE articles
                 SET nom = ?, prix = ?, image = ?,
                     description = ?, stock = ?
                 WHERE id = ?"
            );

            $update->execute([
                $nom,
                $prix,
                $image_finale,
                $description,
                $stock,
                $id
            ]);

            header("Location: crud.php");
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

    <title>Modifier Produit - GameZone</title>

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

        <h2>Modifier le produit</h2>

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
                    value="<?php echo htmlspecialchars($produit['nom']); ?>"
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
                    value="<?php echo $produit['prix']; ?>"
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
                ><?php echo htmlspecialchars($produit['description']); ?></textarea>

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
                    value="<?php echo $produit['stock']; ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>
                    Image actuelle
                </label>

                <img
                    src="../images/<?php echo htmlspecialchars($produit['image']); ?>"
                    alt="Image produit"
                    style="width:150px; height:120px; object-fit:contain;"
                >

            </div>

            <div class="form-group">

                <label for="image">
                    Nouvelle image facultative
                </label>

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                >

            </div>

            <button type="submit">
                Enregistrer les modifications
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