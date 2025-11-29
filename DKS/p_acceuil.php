<?php
require_once 'f_db.php';
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
    <?php include __DIR__ . "/f_menu.php"; ?> 

        <div class="page-container">
            <aside class="sidebar left">
                <a href="./redirection_pub/index.php" target="_blank" rel="noopener noreferrer">
                    <img src="./img-pub/images.jpeg" alt="PUB gauche" class="ad_img">
                </a>
                <a href="./redirection_pub/index.php" target="_blank" rel="noopener noreferrer">
                    <img src="./img-pub/pub_parfum.png" alt="PUB gauche 2" class="ad_img">
                </a>
            </aside>
     
            <main class="main">
                <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?>!</h1>
                <p>Voici la zone principale centrée entre les deux colonnes de publicité (255px chacune).</p>
            </main>

            <aside class="sidebar right">
                <a href="./redirection_pub/index.php" target="_blank" rel="noopener noreferrer">
                    <img src="./img-pub/images.jpeg" alt="PUB droite" class="ad_img">
                </a>
                <a href="./redirection_pub/index.php" target="_blank" rel="noopener noreferrer">
                    <img src="./img-pub/pub_parfum.png" alt="PUB droite 2" class="ad_img">
                </a>
            </aside>
        </div>

        <script>
            (function () {
            var taille = 980; 
            var menu = document.getElementById('menu');
            var menu2 = document.getElementById('menu2');

            function updateMenu() {
                if (!menu || !menu2) return;
                if (window.innerWidth > taille) {
                menu.style.display = 'flex';
                menu2.style.display = 'none';
                } else {
                menu.style.display = 'none';
                menu2.style.display = 'flex';
                }
            }

            window.addEventListener('DOMContentLoaded', updateMenu);
            window.addEventListener('resize', updateMenu);
            window.addEventListener('orientationchange', updateMenu);
            })();
        </script>
    </body>
</html>