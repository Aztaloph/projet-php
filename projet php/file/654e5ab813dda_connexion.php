<?php 
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=projet_php;charset=utf8;','root', 'root');
if(isset($_POST['submit'])){
    if(!empty($_POST['email']) AND !empty($_POST['mdp'])){
        $email = htmlspecialchars($_POST['email']);
        $mdp = $_POST['mdp'];

        $recupUser = $bdd->prepare('SELECT * FROM users WHERE email = ? AND mdp = ?');
        $recupUser->execute(array($email, $mdp));

        if($recupUser->rowCount() > 0){
            $_SESSION['email'] = $email;
            $_SESSION['mdp'] = $mdp;
            $_SESSION['id'] = $recupUser->fetch()['id'];
            header('location: index.php');
        }else{
            echo "email ou mot de passe incorrect";
        }
    }else{
        echo "Veuillez completez tous les champs";
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
    </body>
</html>