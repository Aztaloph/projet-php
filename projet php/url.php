<?php
    $bdd = new PDO('mysql:host=localhost;dbname=projet_php;charset=utf8;','root', 'root');
    $query = "SELECT lien FROM url WHERE lien_raccourcie = :r";
    $requete = $bdd->prepare($query);
    $requete->bindParam(':r', $_GET['u'], PDO::PARAM_INT);
    $requete->execute();
    if ($requete->rowCount() == 0) {
        echo "Pas de lien trrouver";
        exit();
    }
    $lien = $requete->fetch();
    $l = $lien["lien"];
    header("Location: $l");
    exit();
?>