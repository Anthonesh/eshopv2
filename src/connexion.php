<?php

include('./connect.php');

if(!empty($_POST["form_email"]) && !empty($_POST["form_password"])) {
    $select = $db->prepare("SELECT * FROM USERS WHERE user_email=:user_email;");
    $select->bindParam(":user_email", $_POST["form_email"]);
    $select->execute();
    if($select->rowCount() === 1) {
        $user = $select->fetch(PDO::FETCH_ASSOC);
        if(password_verify($_POST["form_password"], $user['user_password'])) {
        $_SESSION['user'] = $user;
        header("Location: index.php");
    }
    } else {
        unset($_SESSION['user']);
    }
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>connexion</h1>
    <form method="post">
        <label for="form_email">Email:</label>
        <input type="text" name="form_email" id="form_email" placeholder="Ex: nomprenom@fournisseur.com">
        <label for="form_password">Mot de passe:</label>
        <input type="password" name="form_password" id="form_password" placeholder="1234">
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>