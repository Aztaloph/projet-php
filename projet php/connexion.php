<?php 
session_start();
// connexion avec la base de données en localhost
$bdd = new PDO('mysql:host=localhost;dbname=projet_php;charset=utf8;','root', 'root');
if(isset($_POST['submit'])){
    // si les champs ne sont pas vide
    if(!empty($_POST['email']) AND !empty($_POST['mdp'])){
        // on récupère les valeurs des champs
        $email = htmlspecialchars($_POST['email']);
        $mdp = $_POST['mdp'];

        // on récupère dans la base de données l'utilisateur ayant un email qui match
        $recupUser = $bdd->prepare('SELECT * FROM users WHERE email = ?');
        $recupUser->execute(array($email));

        // si on trouve un utilisateur avec cet email
        if($recupUser->rowCount() > 0){
            $user = $recupUser->fetch();
            // on vérifie le mot de passe avec password_verify
            if(password_verify($mdp, $user['mdp'])){
                // si le mot de passe correspond, on démarre la session
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $user['id'];
                $_SESSION['mdp'] = $user['mdp'];
                header('location: index.php');
            } else {
                echo "Login incorrect";
            }
        }
    }else{
        echo "Veuillez compléter tous les champs";
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Connexion</title>
        <meta charset="utf-8">
    </head>
    <body>
        <form method="POST" action="">
            <input type="email" name="email"><br>
            <input type="password" name="mdp"><br><br>
            <input type="submit" name="submit">
        </form>
        <a href="inscription.php">
            <button>Inscription</button>
        </a>
    </body>
</html>