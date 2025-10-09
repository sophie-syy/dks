<?php
require_once 'db.php';  
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

function generateStrongPassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
    return substr(str_shuffle($chars), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $website = $_POST['website'];
    $username = $_POST['username'];
    $password = generateStrongPassword();  

    $stmt = $pdo->prepare("INSERT INTO passwords (website, username, password, user_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$website, $username, $password, $_SESSION['user']['id']]);
}

$stmt = $pdo->prepare("SELECT * FROM passwords WHERE user_id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$passwords = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau des Mots de Passe - DKS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>DKS - Gestionnaire de Mots de Passe</h1>
    <a href="./logout.php">Se déconnecter</a>
    <a href="./acceuil.php">acceuil</a>
</header>

<main>
    <h2>Ajouter un mot de passe</h2>
    <form method="POST">
        <input type="text" name="website" placeholder="Nom du site web" required>
        <input type="text" name="username" placeholder="Pseudo" required>
        <button type="submit">Générer et Ajouter</button>
    </form>

    <h2>Tableau des Mots de Passe</h2>
    <table>
        <thead>
            <tr>
                <th>Site Web</th>
                <th>Pseudo</th>
                <th>Mot de Passe</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($passwords as $password): ?>
                <tr>
                    <td><?php echo htmlspecialchars($password['website']); ?></td>
                    <td><?php echo htmlspecialchars($password['username']); ?></td>
                    <td><?php echo htmlspecialchars($password['password']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

</body>
</html>
