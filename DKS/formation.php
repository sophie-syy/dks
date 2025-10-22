<?php
require_once 'db.php';
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user']) || empty($_SESSION['user']['pseudo'])) {
    header("Location: index.php"); 
    exit();
}

$user = $_SESSION['user'];
$userId = $user['id'];
$userPseudo = $user['pseudo'];
$uploadDir = 'database/' . $userPseudo . '/';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formation - DKS</title>
    <link rel="stylesheet" href="./css/formation.css">
</head>
<body>
    <div class="menu">
            <div class="en_ligne">
                <img src="./img/logo.jpg" alt="image" width="80">
                <div class="logo">Data Keep Safe</div>
            </div>
            <div class="menu1">
                <a class="non_suligner _espace" href="./acceuil.php">Accueil</a>
                <a class="non_suligner _espace" href="./abonnement.php">Abonnement</a>
                <a class="non_suligner _espace" href="./data.php">Met fichier</a>
                <a class="non_suligner _espace" href="./gestionmdp.php">Gestion de mot de passe</a>
                <a class="non_suligner _espace" href="./don.php">Don</a>
                <a class="non_suligner _espace espace_" href="./formation.php">Formation</a>
            </div>
            <div class="menu1 dropdown espace_">
                <a class="non_suligner" href="./acceuil.php">&#9776;</a>
                <ul class="submenu">
                <?php if ($user): ?>
                    <li><a class="non_suligner _espace" href="mon_compte.php">Mon compte</a></li>
                    <li><a class="non_suligner _espace" href="./logout">Déconnexion</a></li>
                <?php endif; ?>
                </ul>
            </div>

            <div class="menu2 dropdown espace_">
                <a class="non_suligner" href="./acceuil.php">&#9776;</a>
                <ul class="submenu">
                <?php if ($user): ?>
                    <li><a class="non_suligner _espace" href="./acceuil.php">Accueil</a></li>
                    <li><a class="non_suligner _espace" href="./abonnement.php">Abonnement</a></li>
                    <li><a class="non_suligner _espace" href="./data.php">Met fichier</a></li>
                    <li><a class="non_suligner _espace" href="./gestionmdp.php">Gestion de mot de passe</a></li>
                    <li><a class="non_suligner _espace" href="./don.php">Don</a></li>
                    <li><a class="non_suligner _espace espace_" href="./formation.php">Formation</a></li>
                    <li><a class="non_suligner _espace" href="mon_compte.php">Mon compte</a></li>
                    <li><a class="non_suligner _espace" href="./logout">Déconnexion</a></li>
                <?php endif; ?>
                </ul>
            </div>
        </div>
        <nav class="barre"></nav>

    <div class="_espace">
        <header>
                <h1>DKS - Formation</h1>
        </header>
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
