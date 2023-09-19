<?php

    include('./connect.php');
   

    if(!empty($_POST["form_email"])&& !empty($_POST['form_password'])) {
      
        $select = $db->prepare("SELECT user_email FROM users WHERE user_email=:user_email;");
        $select->bindParam(":user_email", $_POST["form_email"]);
        $select->execute();
        if(empty($select->fetch(PDO::FETCH_COLUMN))) {
          
            $insert = $db->prepare("INSERT INTO users(user_email, user_password)
                                    VALUES(:user_mail, :user_password);");
            $insert->bindParam(":user_mail", $_POST['form_email']);
            $user_password = password_hash($_POST['form_password'], PASSWORD_BCRYPT, array("cost" => 12));
            $insert->bindParam(":user_password", $user_password);
            if($insert->execute()) {
                die('<p style=”color: green;”>Inscription réussie.</p><a href="connexion.php">Se connecter.</a>');
            }
            die('<p style=”color: red;”>Inscription échouée.</p><a href="inscription.php">Réessayer.</a>');
        }
    }


?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>inscription.PHP</h1>
    <form method="post">
        <label for="form_email">Email:</label>
        <input type="text" name="form_email" placeholder="Ex: nomprenom@fournisseur.com">
        <label for="form_password">Mot de passe:</label>
        <input type="password" name="form_password" placeholder="1234">
        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>