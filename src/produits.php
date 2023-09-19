<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Produits</title>
</head>
<body>
<header>
        <h1>E-choppe</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="#">Produits</a></li>
                <li><a href="panier.php">Panier</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="connexion.php">Connexion</a></li>
                <li><a href="inscription.php">Inscription</a></li>
                <li><a href="deconnexion.php">Se déconnecter</a></li>
            </ul>
        </nav>
    </header>

<main>

<?php

include 'connect.php';

function ajouterAuPanier($produit,$quantite = 1) {
    global $db;
    
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
    }

    
    $_SESSION['panier'][] = $produit;





    if ($quantite <= $produit['qte_stock_produit']) {
        // Ajouter le produit au panier avec la quantité spécifiée
        $produit_id = $produit['produit_id'];
        $affiche_produit = $produit['affiche_produit'];
        $name_produit = $produit['name_produit'];
        $prix_produit = $produit['prix_produit'];
        $qte_stock_produit = $produit['qte_stock_produit'];

        $produit['qte_stock_panier'] = $quantite;
        $_SESSION['panier'][] = $produit;

        // Mettre à jour la quantité en stock du produit
        $new_qte_stock_produit = $qte_stock_produit -= $quantite;
        $stmt = $db->prepare("UPDATE produits SET qte_stock_produit = ? WHERE id_produit = ?");
        $stmt->execute([$new_qte_stock_produit, $produit_id]);
    } else {
        echo "La quantité demandée dépasse la quantité en stock.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter']) && isset($_POST['quantite'])) {
    $produit_id = $_POST['produit_id'];
    $affiche_produit = $_POST ['affiche_produit'];
    $name_produit = $_POST['name_produit'];
    $prix_produit = $_POST['prix_produit'];
    $qte_stock_produit = $_POST['qte_stock_produit'];
    $quantite = isset($_POST['quantite']) ? intval($_POST['quantite']) : 1;

    ajouterAuPanier(array(
        'produit_id' => $produit_id,
        'affiche_produit' => $affiche_produit,
        'name_produit' => $name_produit,
        'prix_produit' => $prix_produit,
        'qte_ajoutee' => $quantite,
    ), $quantite);
}




while ($product = $selectProducts->fetch(PDO::FETCH_ASSOC)) {

    echo '<section class="product">';
    echo '<img src="../imgs/' . $product['affiche_produit'] . '" alt="' . $product['affiche_produit'] . '">';
    echo '<h2>' . $product['name_produit'] . '</h2>';
    echo '<p>' . $product['description_produit'] . '</p>';
    echo '<p>Prix : $' . $product['prix_produit'] . '</p>';
    echo '<p>Quantité en stock :' . $product['qte_stock_produit'] . '</p>';
    echo '<form method="POST" action="produits.php">';
    echo '<input type="hidden" name="produit_id" value="' . $product['id_produit'] . '">';
    echo '<input type="hidden" name="name_produit" value="' . $product['name_produit'] . '">';
    echo '<input type="hidden" name="affiche_produit" value="' . $product['affiche_produit'] . '">';
    echo '<input type="hidden" name="prix_produit" value="' . $product['prix_produit'] . '">';
    echo '<input type="hidden" name="qte_stock_produit" value="' . $product['qte_stock_produit'] . '">';
    echo '<input type="number" name="quantite" value="1" min="1" max="' . $product['qte_stock_produit'] . '">';
    echo '<button type="submit" name="ajouter">Ajouter au Panier</button>';
    echo '</form>';
    echo '</section>';
}

?>
    </main>

    <footer>
        <p>&copy; 2023 Mon Site E-Commerce</p>
    </footer>

</body>
</html>