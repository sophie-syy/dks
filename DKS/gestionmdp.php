<?php
require_once 'db.php';  
session_start();
$user = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;

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
        <link rel="stylesheet" href="./css/gestionmdp.css">
        </head>
    <body>
        <div class="menu">
        <div class="en_ligne">
            <img src="./img/logo.jpg" alt="image" width="80px">
            <div class="logo">Data Keep Safe</div>
        </div>
        <div>
            <a class="non_suligner _espace" href="./acceuil.php">Acceuil</a>
            <a class="non_suligner _espace" href="./gestionmdp.php">Jeux</a>
            <a class="non_suligner _espace" href="./data.php">Met fichier</a>
            <a class="non_suligner _espace" href="./gestionmdp.php">Gestion de mot de passe</a>
            <a class="non_suligner _espace" href="./gestionmdp.php">Don</a>
            <a class="non_suligner _espace espace_" href="./gestionmdp.php">Formation</a>
        </div>

        <li class="dropdown espace_">
            <a class="non_suligner" href="./">&#9776;</a>
            <ul class="submenu">
            <?php if ($user): ?>
                <li><a class="non_suligner _espace" href="mon_compte.php">Mon compte</a></li>
                <li><a class="non_suligner _espace" href="./logout">Deconnection</a></li>
            <?php endif; ?>
            </ul>
        </li>
    </div> 
    <nav class="barre"></nav>

    <header>
        <h1>DKS - Gestionnaire de Mots de Passe</h1>
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
