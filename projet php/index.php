<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=projet_php;charset=utf8;', 'root', 'root');

if (isset($_POST['submit'])) {
    // Générer un raccourci unique
    $raccourcie = generateRandomString(10);
    $query_raccourcie = "SELECT lien_raccourcie FROM url";
    $requete = $bdd->prepare($query_raccourcie);
    $requete->execute();
    $raccourcie_saved = $requete->fetchAll(PDO::FETCH_ASSOC);
    $check = true;
    while ($check) {
        $raccourcie = generateRandomString(10);
        $check = false;
        foreach ($raccourcie_saved as $r) {
            if ($r['lien_raccourcie'] == $raccourcie) {
                $check = true;
                break;
            }
        }
    }

    $lien = $_POST["lien"];
    if (!str_contains($lien, "http")) {
        $lien = "http://" . $lien;
    }

    // Insertion d'un nouveau raccourci
    if (!empty($_POST['lien'])) {
        $query = "INSERT INTO url (user_id, lien, lien_raccourcie, nombre_clic, actif) VALUES (:id, :lien, :lien_raccourcie, 0, 1)";
        $requete = $bdd->prepare($query);
        $requete->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
        $requete->bindParam(':lien', $lien, PDO::PARAM_STR);
        $requete->bindParam(':lien_raccourcie', $raccourcie, PDO::PARAM_STR);
        $requete->execute();
    } else {
        $uploadDirectory = 'file/';
        $file = $_FILES['mon_fichier'];
        uploadFile($file, $uploadDirectory, $bdd, $raccourcie);
    }
}

$query = "SELECT id, lien, lien_raccourcie, nombre_clic, actif FROM url WHERE user_id = :id";
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
    <?php echo "Vous êtes connecté en tant que : " . $_SESSION['email']; ?>
    <br><br>
    Ajouter un raccourci :
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="text" name="lien"><br>
        <input type="file" name="mon_fichier", id="mon_fichier", accept=".png,.pdf,.jpg,.mp3,.txt,.doc,.mp4,.jpeg">
        <input type="submit" name="submit">
    </form>
    <br><br>
    <div>
        <table border="1">
            <tr>
                <th>Lien</th>
                <th>Raccourci</th>
                <th>Nombre Clic</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($resultats as $resultat) : ?>
                <tr>
                    <td><?php echo $resultat['lien']; ?></td>
                    <td><?php echo '<a href= ' . str_replace("index.php", "", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") . "url.php?u=" . $resultat['lien_raccourcie'] . '>' . str_replace("index.php", "", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") . "url.php?u=" . $resultat['lien_raccourcie'] . '</a>'; ?></td>
                    <td><?php echo $resultat['nombre_clic']; ?></td>
                    <td>
                        <?php
                        if (isset($resultat['id'])) {
                            echo '<a href="supprimer.php?id=' . $resultat['id'] . '">Supprimer</a> | ';

                            if ($resultat['actif'] == 1) {
                                echo '<a href="toggle.php?id=' . $resultat['id'] . '">Désactiver</a>';
                            } else {
                                echo '<a href="toggle.php?id=' . $resultat['id'] . '">Activer</a>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <a href="deconnexion.php">
        <button>Déconnexion</button>
    </a>
</body>

</html>

<?php
function generateRandomString($length = 10) {
    $randomBytes = random_bytes(ceil($length / 2));
    $randomString = bin2hex($randomBytes);
    return substr($randomString, 0, $length);
}

function uploadFile($file, $uploadDirectory, $bdd, $raccourcie)
{
  
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Erreur lors du téléchargement du fichier.";
        return false;
    }

   
    $fileName = uniqid() . '_' . $file['name'];

    
    $destination = $uploadDirectory . '/' . $fileName;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        echo "Erreur lors du déplacement du fichier vers le répertoire d'upload.";
        return false;
    }

    $lien = str_replace("index.php", $destination, "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

    if (!str_contains($lien, "file/")) {
        $lien = $lien.$destination;
    }

    $query = "INSERT INTO url (user_id, lien, lien_raccourcie, nombre_clic, actif) VALUES (:id, :lien, :lien_raccourcie, 0, 1)";
    $requete = $bdd->prepare($query);
    $requete->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $requete->bindParam(':lien', $lien, PDO::PARAM_STR);
    $requete->bindParam(':lien_raccourcie', $raccourcie, PDO::PARAM_STR);
    $requete->execute();

}
?>
