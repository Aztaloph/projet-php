<?php 
session_start();
// connexion avec la base de données en localhost
$bdd = new PDO('mysql:host=localhost;dbname=projet_php;charset=utf8;','root', 'root');
if(isset($_POST['submit'])){
    // si les champs ne sont pas vide
    if(!empty($_POST['email']) AND !empty($_POST['mdp'])){
        $email = htmlspecialchars($_POST['email']);
        $mdp = $_POST['mdp'];
        // on insert les logins de l'utilisateur dans la abse de données
        $insertUser = $bdd->prepare('INSERT INTO users(email, mdp)VALUES(?, ?)');
        $insertUser->execute(array($email, $mdp));
        // on récupère l'utilisateur dans la bdd
        $recupUser = $bdd->prepare('SELECT * FROM users WHERE email = ? AND mdp = ?');
        $recupUser->execute(array($email, $mdp));
        // s'il existe on crée sa session
        if($recupUser->rowCount() >0){
        $_SESSION['email'] = $email;
        $_SESSION['mdp'] = $mdp;
        $_SESSION['id'] = $recupUser->fetch()['id'];
        }
        // et on redirige vers la page d'acceuil
        header('location: index.php');
    }else{
        echo "Veuillez completez tous les champs";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Inscription</title>
        <meta charset="utf-8">
    </head>
    <body>
        <form method="POST" action="">
            <input type="email" name="email"><br>
            <input type="password" name="mdp"><br><br>
            <input type="submit" name="submit">
        </form>
    </body>
</html>