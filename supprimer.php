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
    "SELECT image FROM articles WHERE id = ?"
);

$stmt->execute([$id]);

$produit = $stmt->fetch();

if ($produit) {

    $delete = $pdo->prepare(
        "DELETE FROM articles WHERE id = ?"
    );

    $delete->execute([$id]);

    $image_path = "../images/" . $produit['image'];

    if (
        file_exists($image_path) &&
        is_file($image_path)
    ) {
        unlink($image_path);
    }
}

header("Location: crud.php");
exit();
?>