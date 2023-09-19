<?php
include 'connect.php';

function supprimerDuPanier($indice) {
    if (isset($_SESSION['panier'][$indice])) {
        unset($_SESSION['panier'][$indice]);
        $_SESSION['panier'] = array_values($_SESSION['panier']); 
    }
}

$user_id = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
    $indice = $_POST['indice_produit'];
    supprimerDuPanier($indice);
}

// Assurez-vous d'avoir une session utilisateur valide avant de continuer
if (!isset($_SESSION['id_user'])) {
    // Redirigez l'utilisateur vers une page de connexion ou effectuez une autre action appropriée.
    header("Location: connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider_panier'])) {
    foreach ($_SESSION['panier'] as $indice => $produit) {
        $produit_id = $produit['produit_id'];
        $name_produit = $produit['name_produit'];
        $prix_produit = $produit['prix_produit']; // Assurez-vous d'initialiser $prix_produit
    
        $qte_stock_produit = isset($produit['qte_stock_produit']) ? $produit['qte_stock_produit'] : 1;
    
        // Insérer le produit dans la table panier avec la quantité spécifiée
        $insertPanier = $db->prepare("INSERT INTO panier (id_produit, name_produit, prix_produit, qte_stock_panier, id_user) VALUES (?, ?, ?, ?, ?)");
        $insertPanier->execute([$produit_id, $name_produit, $prix_produit, $qte_stock_produit, $_SESSION['id_user']]);
    
        // Mettre à jour le stock de produits
        $updateStock = $db->prepare("UPDATE produits SET qte_stock_produit = qte_stock_produit - ? WHERE id_produit = ?");
        $updateStock->execute([$qte_stock_produit, $produit_id]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vider_panier'])) {
    unset($_SESSION['panier']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vider_historique_panier'])) {
    $deleteHistoriquePanier = $db->prepare("DELETE FROM panier");
    $deleteHistoriquePanier->execute();
    
    header("Location: panier.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Panier</title>
</head>
<body>
    <header>
        <h1>E-choppe</h1>
        <nav>
        <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="produits.php">Produits</a></li>
                <li><a href="#">Panier</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="connexion.php">Connexion</a></li>
                <li><a href="inscription.php">Inscription</a></li>
                <li><a href="deconnexion.php">Se déconnecter</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="panier">
            <h2>Votre Panier</h2>
            <?php
            if (!empty($_SESSION['panier'])) {
                echo '<form method="POST" action="panier.php">';
                echo '<button type="submit" name="vider_panier">Vider le Panier</button>';
                echo '<button type="submit" name="valider_panier">Valider le Panier</button>';
                echo '<button type="submit" name="vider_historique_panier">Vider historique du panier</button>';
                echo '</form>';
                echo '<ul>';
                foreach ($_SESSION['panier'] as $indice => $produit) {
                    
                    echo '<li>'; 
                    echo '<img src="../imgs/' . $produit['affiche_produit'] . '" alt="' . $produit['name_produit'] . '" style="max-width: 100px;">';
                    echo  $produit['name_produit'] . ' - $' . $produit['prix_produit'] . ' ';
                    echo '<form method="POST" action="panier.php" style="display: inline;">';
                    echo '<input type="hidden" name="indice_produit" value="' . $indice . '">';
                    echo '<button type="submit" name="supprimer">Supprimer</button>';
                    echo '</form>';
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Votre panier est vide.</p>';
            }
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Mon Site E-Commerce</p>
    </footer>
</body>
</html>
