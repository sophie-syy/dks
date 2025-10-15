<?php
require_once 'db.php';
session_start();
$user = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="./css/acceuil.css">
</head>
<body>
    <header>
        <div>
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?>!</h1>
            <a href="./redirection_pub/index.php" target="_blank" rel="noopener noreferrer">
                <img src="./img-pub/images.jpeg" alt="PUB">
            </a>

        </div>

    </header>

    <nav>
        <a href="./mon_compte.php">Mon compte</a>
    </nav>

    <div class="menu">
        <a href="./abonnement.php">abonnement</a>
        <a href="./data.php">Met fichier</a>
        <a href="./gestionmdp.php">Gestion de mot de passe</a>
        <a href="./gestionmdp.php">Don</a>
        <a href="./gestionmdp.php">Formation</a>
        <a href="./logout.php">deconection</a>
    </div>

    <div class="card">
        <h1>Test</h1>
    </div>
</body>
</html>
