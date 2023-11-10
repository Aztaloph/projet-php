<?php
$bdd = new PDO('mysql:host=localhost;dbname=projet_php;charset=utf8;', 'root', 'root');

$query = "SELECT lien, nombre_clic FROM url WHERE lien_raccourcie = :r";
$requete = $bdd->prepare($query);
$requete->bindParam(':r', $_GET['u'], PDO::PARAM_STR);
$requete->execute();

if ($requete->rowCount() == 0) {
    echo "Pas de lien trouvé";
    exit();
}

$lien = $requete->fetch();
$l = $lien["lien"];
$nombreClic = $lien["nombre_clic"];

// Incrémentation du compteur
$nombreClic++;
$queryIncrementation = "UPDATE url SET nombre_clic = :nombre_clic WHERE lien_raccourcie = :r";
$requeteIncrementation = $bdd->prepare($queryIncrementation);
$requeteIncrementation->bindParam(':nombre_clic', $nombreClic, PDO::PARAM_INT);
$requeteIncrementation->bindParam(':r', $_GET['u'], PDO::PARAM_STR);
$requeteIncrementation->execute();

// Redirection vers le lien original
header("Location: $l");
exit();
?>
