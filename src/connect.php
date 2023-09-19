<?php
$DATA_NAME = "E_shop";
$DATA_USER = "root";
$DATA_PASS = "";


    session_start();

    try {
        $db = new PDO("mysql:host=localhost;dbname=$DATA_NAME", $DATA_USER, $DATA_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        $selectProducts = $db->query("SELECT * FROM Produits");
       

        if (isset($_SESSION['id_user'])) {
            $user_id = $_SESSION['id_user'];
        
            
        } else {
           
        }

        


    } catch(PDOException $e) {
        $db = NULL;
        echo ("Erreur: " . $e->getMessage());
    }
?>
        
        


