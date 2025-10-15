<?php
require_once 'db.php';
session_start();
$user = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="./css/acceuil.css">
    <link rel="stylesheet" href="./global.css">
</head>
<body>
    <header>

        <nav>
            <?php if ($user): ?>
                <a class="non_suligner" href="mon_compte.php">Mon compte</a>
                <a class="non_suligner" href="./logout">deconnection</a>
            <?php endif; ?>
        </nav>

        <div>
            <div class="menu">
                <a class="non_suligner _espace" href="./gestionmdp.php">Jeux</a>
                <a class="non_suligner" href="./data.php">Met fichier</a>
                <a class="non_suligner" href="./gestionmdp.php">Gestion de mot de passe</a>
                <a class="non_suligner" href="./gestionmdp.php">Don</a>
                <a class="non_suligner espace_" href="./gestionmdp.php">Formation</a>
            </div>
        </div>
        
        <div>
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?>!</h1>
            <a href="./redirection_pub/index.php" target="_blank" rel="noopener noreferrer">
                <img src="./img-pub/images.jpeg" alt="PUB">
            </a>

        </div>

    </header>

    

    <div class="card">
        <h1>Test</h1>
    </div>
</body>
</html>
