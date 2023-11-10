<?php

function generateRandomString($length = 10) {
    $randomBytes = random_bytes(ceil($length / 2));
    $randomString = bin2hex($randomBytes);
    return substr($randomString, 0, $length);
}

session_start();
if(!$_SESSION['mdp']){
    header('Location: connexion.php');
}
$bdd = new PDO('mysql:host=localhost;dbname=projet_php;charset=utf8;','root', 'root');
if(isset($_POST['submit'])){

    // Generer un raccourcie unique
    $raccourcie = generateRandomString(10);
    $query_raccourcie = "SELECT lien_raccourcie FROM url";
    $requete = $bdd->prepare($query_raccourcie);
    $requete->execute();
    $raccourcie_saved = $requete->fetchAll(PDO::FETCH_ASSOC);
    $check = false;
    while($check) {
        $raccourcie = generateRandomString(10);
        $check = false;
        foreach ($raccoourcie_saved as $r) {
            if ($r == $raccourcie) {
                $check = true;
                break;
            }
        }
    }
    $lien = $_POST["lien"];
    if (!str_contains($lien, "http")) {
        $lien = "http://".$lien;
    }

    // Insertion d'un nouveau raccourcie
    if(!empty($_POST['lien'])) {
        $query = "INSERT INTO url (user_id, lien, lien_raccourcie, nombre_clic) VALUES (:id, :lien, :lien_raccourcie, 0)";
    }
    $requete = $bdd->prepare($query);
    $requete->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $requete->bindParam(':lien', $lien, PDO::PARAM_STR);
    $requete->bindParam(':lien_raccourcie', $raccourcie, PDO::PARAM_STR);
    $requete->execute();
}
$query = "SELECT lien, lien_raccourcie, nombre_clic FROM url WHERE user_id =:id";
$requete = $bdd->prepare($query);
$requete->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$requete->execute();
$resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Accueil</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php echo "Vous êtes connecté en tant que : ".$_SESSION['email'];?>
        <br><br>
        Ajouter un raccourcie:
        <form method="POST" action="">
            <input type="lien" name="lien"><br>
            <input type="submit" name="submit">
        </form>
        <br><br>
        <div>
            <table border="1">
            <tr>
                <th>Lien</th>
                <th>Raccourcie</th>
                <th>Nombre Clic</th>
            </tr>
            <?php foreach ($resultats as $lien) : ?>
                <tr>
                    <td><?php echo $lien['lien']; ?></td>
                    <td><?php echo str_replace("index.php", "url.php/?u=", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]").$lien['lien_raccourcie']; ?></td>
                    <td><?php echo $lien['nombre_clic']; ?></td>
                </tr>
            <?php endforeach; ?>
        </div>
        <a href="deconnexion.php">
            <button>Déconnexion</button>
        </a>
    </table>
    </body>
</html>



