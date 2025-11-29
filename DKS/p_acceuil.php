<?php
require_once 'f_db.php';
session_start();
$user = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Récupérer les derniers mots de passe de l'utilisateur
$stmt = $pdo->prepare("SELECT website, username, id FROM passwords WHERE user_id = ? ORDER BY id DESC LIMIT 5");
$stmt->execute([$user['id']]);
$passwords = $stmt->fetchAll();

// Récupérer le nombre total de mots de passe
$stmt_count = $pdo->prepare("SELECT COUNT(*) FROM passwords WHERE user_id = ?");
$stmt_count->execute([$user['id']]);
$total_passwords = $stmt_count->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="./css/acceuil.css">
</head>
<body>
<?php include __DIR__ . "/f_menu.php"; 
include __DIR__ . "/f_pub.php"; ?> 

<main class="main">
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?> !</h1>
    <p>Bienvenue sur votre coffre-fort numérique. Gérez et protégez tous vos mots de passe sur une plateforme sécurisée !</p>

    <div class="alert-info">
        <strong>Total de mots de passe enregistrés : </strong><?php echo $total_passwords; ?>
    </div>
    
    <div style="margin: 20px 0;">
        <p><a href="p_gestionmdp.php" class="btn">Ajouter un mot de passe</a></p>
        <p><a href="p_data.php" class="btn">Voir tous mes mots de passe</a></p>
        <p><a href="p_don.php" class="btn">Faire un don</a></p>
    </div>

    <section>
        <h2>Derniers mots de passe ajoutés</h2>
        <?php if (count($passwords) > 0): ?>
            <table border="1" cellpadding="5" style="border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Site</th>
                        <th>Nom d'utilisateur</th>
                        <th>Détail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($passwords as $pw): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pw['website']); ?></td>
                            <td><?php echo htmlspecialchars($pw['username']); ?></td>
                            <td><a href="p_gestionmdp.php?id=<?php echo $pw['id']; ?>">Voir</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun mot de passe enregistré pour l’instant.</p>
        <?php endif; ?>
    </section>
</main>

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