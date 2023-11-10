<?php
session_start();
$SESSION = array();
//on détruit toutes les sessions en cours
session_destroy();
header('Location: connexion.php');
?>