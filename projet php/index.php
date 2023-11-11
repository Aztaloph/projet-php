<?php
session_start();
if(!$_SESSION['mdp']){
    header('Location: connexion.php');
}

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
        <a href="deconnexion.php">
            <button>Déconnexion</button>
        </a><br><br>
        <a href="inscription.php">
            <button>Inscription</button>
        </a>
    </body>
</html>



