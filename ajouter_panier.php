<?php
session_start();

/*
    غير المستخدم المسجل يقدر يزيد للـ panier
*/
if (!isset($_SESSION['user_id'])) {
    header("Location: user/login.php");
    exit();
}

include("db.php");


/*
    التحقق من ID ديال المنتج
*/
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: accueil.php");
    exit();
}

$id = (int) $_GET['id'];


/*
    جلب المنتج من database
*/
$stmt = $pdo->prepare("
    SELECT *
    FROM articles
    WHERE id = ?
");

$stmt->execute([$id]);

$produit = $stmt->fetch(PDO::FETCH_ASSOC);


/*
    إلا المنتج ما كاينش
*/
if (!$produit) {
    header("Location: accueil.php");
    exit();
}


/*
    إنشاء panier إلا ما كانش موجود
*/
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}


/*
    الكمية الموجودة من نفس المنتج فـ panier
*/
$quantiteActuelle = 0;

if (isset($_SESSION['panier'][$id])) {
    $quantiteActuelle = (int) $_SESSION['panier'][$id];
}


/*
    التحقق من stock
*/
if ((int) $produit['stock'] <= 0) {
    header("Location: accueil.php?message=stock_insuffisant");
    exit();
}


/*
    منع تجاوز stock
*/
if ($quantiteActuelle >= (int) $produit['stock']) {
    header("Location: accueil.php?message=stock_insuffisant");
    exit();
}


/*
    إضافة المنتج للـ panier
*/
if (isset($_SESSION['panier'][$id])) {
    $_SESSION['panier'][$id]++;
} else {
    $_SESSION['panier'][$id] = 1;
}


/*
    إنقاص stock من database
*/
$update = $pdo->prepare("
    UPDATE articles
    SET stock = stock - 1
    WHERE id = ?
    AND stock > 0
");

$update->execute([$id]);


/*
    الرجوع للـ panier
*/
header("Location: panier.php");
exit();

?>