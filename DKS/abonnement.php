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

// Liste des plans de stockage
$plans = [
    ['name' => 'Gratuit', 'storage' => 40, 'price' => 0],
    ['name' => '80 Go', 'storage' => 80, 'price' => 2.99],
    ['name' => '100 Go', 'storage' => 100, 'price' => 3.99],
    ['name' => '1 To', 'storage' => 1024, 'price' => 9.99],
    ['name' => '5 To', 'storage' => 5120, 'price' => 19.99],
    ['name' => '10 To', 'storage' => 10240, 'price' => 29.99],
];

// Connexion à la base de données pour récupérer l'abonnement actuel
$sql = "SELECT * FROM abonnements WHERE user_id = :userId";
$stmt = $pdo->prepare($sql);
$stmt->execute(['userId' => $userId]);
$abonnementActuel = $stmt->fetch();

// Traitement de l’abonnement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plan'])) {
    $selectedPlan = $_POST['plan'];
    // Récupérer le prix et l'espace de stockage du plan sélectionné
    $plan = $plans[$selectedPlan];
    
    // Mettre à jour l'abonnement dans la base de données
    $sql = "INSERT INTO abonnements (user_id, plan_name, storage_size, price) VALUES (:userId, :planName, :storageSize, :price)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'userId' => $userId,
        'planName' => $plan['name'],
        'storageSize' => $plan['storage'],
        'price' => $plan['price']
    ]);
    header("Location: abonnement.php"); // Recharger la page
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonnement - Data Keep Safe</title>
    <link rel="stylesheet" href="./css/style.css">
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
        <h1>Gérez votre abonnement</h1>
        <p>Choisissez un plan de stockage adapté à vos besoins.</p>

        <h2>Plan actuel :</h2>
        <?php if ($abonnementActuel): ?>
            <p>Vous êtes actuellement abonné à <strong><?= htmlspecialchars($abonnementActuel['plan_name']) ?></strong> avec <strong><?= htmlspecialchars($abonnementActuel['storage_size']) ?> Go</strong> de stockage.</p>
        <?php else: ?>
            <p>Vous n'êtes actuellement pas abonné à un plan payant.</p>
        <?php endif; ?>

        <h2>Choisissez un plan :</h2>
        <form method="post">
            <table>
                <thead>
                    <tr>
                        <th>Nom du plan</th>
                        <th>Espace de stockage</th>
                        <th>Prix mensuel</th>
                        <th>Choisir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plans as $index => $plan): ?>
                        <tr>
                            <td><?= htmlspecialchars($plan['name']) ?></td>
                            <td><?= $plan['storage'] ?> Go</td>
                            <td><?= number_format($plan['price'], 2) ?> €</td>
                            <td>
                                <?php if ($abonnementActuel && $abonnementActuel['plan_name'] == $plan['name']): ?>
                                    <button type="button" disabled>Abonné</button>
                                <?php else: ?>
                                    <button type="submit" name="plan" value="<?= $index ?>">Choisir</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
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
