<h1>Page de don</h1><?php
require_once 'db.php';
session_start();
$user = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement du don
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];

    // Insérer les informations du don dans la base de données
    $query = "INSERT INTO dons (user_name, user_email) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_name, $user_email, $don_amount]);

    // Redirection ou message de confirmation
    $don_success = true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Faire un Don - Data Keep Safe</title>
    <link rel="stylesheet" href="./css/don.css">
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

    <div class="content">
        <div class="donation-form">
            <h1>Faire un Don</h1>

            <?php if (isset($don_success) && $don_success): ?>
                <div class="success-message">
                    <p>Merci pour votre don, <?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?> ! Votre contribution nous aide beaucoup.</p>
                </div>
            <?php endif; ?>

            <form action="don.php" method="POST">
                <div class="form-group">
                    <label for="user_name">Nom complet :</label>
                    <input type="text" id="user_name" name="user_name" required value="<?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?>">
                </div>

                <div class="form-group">
                    <label for="user_email">Email :</label>
                </div>

                <div class="form-group">
                    <label for="don_amount">Montant du don (en EUR) :</label>
                    <input type="number" id="don_amount" name="don_amount" required min="1" step="0.01" placeholder="Ex: 10.00" value="">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-donate">Faire un Don</button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Data Keep Safe. Tous droits réservés.</p>
    </footer>

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
