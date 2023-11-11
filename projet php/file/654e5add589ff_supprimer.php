<?php

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=projet_php;charset=utf8;', 'root', 'root');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    supprimerUrl($id, $bdd);

    header("Location: index.php");
    exit();
} else {
    echo "ID non spécifié.";
}

function supprimerUrl($id, $bdd) {
    $query = "DELETE FROM url WHERE id = :id";
    $requete = $bdd->prepare($query);
    $requete->bindParam(':id', $id, PDO::PARAM_INT);
    $requete->execute();
    }
?>
