<?php
try{
$pdo = new PDO("mysql:host=localhost;dbname=eboutique","root","");
}catch(PDOException $e){
echo "Erreur connexion";
}
?>