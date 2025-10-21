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
</head>
<body>
    <header>
        <div class="menu">
            <div class="en_ligne">
                <img src="./img/logo.jpg" alt="image" width="80px">
                <div class="logo">Data Keep Safe</div>
            </div>
            <div>
                <a class="non_suligner _espace" href="./acceuil.php">Acceuil</a>
                <a class="non_suligner _espace" href="./abonnement.php">abonnement</a>
                <a class="non_suligner _espace" href="./data.php">Met fichier</a>
                <a class="non_suligner _espace" href="./gestionmdp.php">Gestion de mot de passe</a>
                <a class="non_suligner _espace" href="./don.php">Don</a>
                <a class="non_suligner _espace espace_" href="./gestionmdp.php">Formation</a>
            </div>

            <li class="dropdown espace_">
                <a class="non_suligner" href="./acceuil.php">&#9776;</a>
                <ul class="submenu">
                <?php if ($user): ?>
                    <li><a class="non_suligner _espace" href="mon_compte.php">Mon compte</a></li>
                    <li><a class="non_suligner _espace" href="./logout">Deconnection</a></li>
                <?php endif; ?>
                </ul>
            </li>
        </div> 
        <nav class="barre"></nav>

        <div class="en_ligne">
            <div>
                <a href="./redirection_pub/index.php" target="_blank" rel="noopener noreferrer">
                    <img src="./img-pub/images.jpeg" alt="PUB">
                </a>
            </div>
            <div class="_espace">
                <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?>!</h1>
            </div> 
        </div>
        

    </header>

    


</body>
</html>
