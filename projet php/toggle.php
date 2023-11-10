<?php
// toggle.php

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=projet_php;charset=utf8;', 'root', 'root');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    toggleActivation($id, $bdd);

    header("Location: index.php");
    exit();
} else {
    echo "ID non spécifié.";
}

function toggleActivation($id, $bdd) {
    $query = "SELECT actif FROM url WHERE id = :id";
    $requete = $bdd->prepare($query);
    $requete->bindParam(':id', $id, PDO::PARAM_INT);
    $requete->execute();
    $result = $requete->fetch(PDO::FETCH_ASSOC);

    $nouvelEtat = $result['actif'] == 1 ? 0 : 1;

    $queryUpdate = "UPDATE url SET actif = :actif WHERE id = :id";
    $requeteUpdate = $bdd->prepare($queryUpdate);
    $requeteUpdate->bindParam(':actif', $nouvelEtat, PDO::PARAM_INT);
    $requeteUpdate->bindParam(':id', $id, PDO::PARAM_INT);
    $requeteUpdate->execute();
}
?>
