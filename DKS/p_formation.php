<?php
require_once 'f_db.php';
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
<?php include __DIR__ . "/f_menu.php"; ?> 

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
