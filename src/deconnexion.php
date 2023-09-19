<?php
    include("connect.php");


    session_destroy();

    header("Location: connexion.php");
?>
