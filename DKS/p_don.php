<?php
require_once 'f_db.php';
session_start();
$user = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement du don
    $don_amount = isset($_POST['don_amount']) ? $_POST['don_amount'] : null;
    $user_id = $_SESSION['user']['id'];

    if ($don_amount && $user_id) {
        // Insérer le don dans la base de données
        $query = "INSERT INTO dons (user_id, don_amount) VALUES (?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id, $don_amount]);

        // Redirection ou message de confirmation
        $don_success = true;
    } else {
        $don_error = "Veuillez entrer un montant valide.";
    }
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
<?php include __DIR__ . "/f_menu.php";
include __DIR__ . "/f_pub.php";?>

    <div class="content">
        <div class="donation-form">
            <h1>Faire un Don</h1>

            <?php if (isset($don_success) && $don_success): ?>
                <div class="success-message">
                    <p>Merci pour votre don, <?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?> ! Votre contribution nous aide beaucoup.</p>
                </div>
            <?php elseif (isset($don_error)): ?>
                <div class="error-message"><?= htmlspecialchars($don_error); ?></div>
            <?php endif; ?>

            <form action="p_don.php" method="POST">
                <div class="form-group">
                    <label for="don_amount">Montant du don (en EUR) :</label>
                    <input type="number" id="don_amount" name="don_amount" required min="1" step="0.01" placeholder="Ex : 10.00" value="">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-donate">Faire un Don</button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . "/f_pub2.php";  ?>

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