<?php
require_once 'db.php';  
session_start();
$user = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Génération de mot de passe fort
function generateStrongPassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
    return substr(str_shuffle($chars), 0, $length);
}

// Ajouter un mot de passe (généré)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $website = $_POST['website'];
    $username = $_POST['username'];
    $password = generateStrongPassword();  

    $stmt = $pdo->prepare("INSERT INTO passwords (website, username, password, user_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$website, $username, $password, $_SESSION['user']['id']]);
}

// Modifier un mot de passe existant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $website = $_POST['website'];
    $username = $_POST['username'];
    $custom_password = $_POST['custom_password'] ?? '';
    $password = $custom_password ? $custom_password : generateStrongPassword();  

    $stmt = $pdo->prepare("UPDATE passwords SET website = ?, username = ?, password = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$website, $username, $password, $id, $_SESSION['user']['id']]);

    // Redirection pour éviter le double envoi du formulaire
    header("Location: gestionmdp.php");
    exit;
}

// Récupérer tous les mots de passe de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM passwords WHERE user_id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$passwords = $stmt->fetchAll();

// Afficher la page HTML
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
                <a class="non_suligner _espace" href="./abonnement.php">abonnement</a>
                <a class="non_suligner _espace" href="./data.php">Met fichier</a>
                <a class="non_suligner _espace" href="./gestionmdp.php">Gestion de mot de passe</a>
                <a class="non_suligner _espace" href="./don.php">Don</a>
                <a class="non_suligner _espace espace_" href="./gestionmdp.php">Formation</a>
            </div>

        <li class="dropdown espace_">
            <a class="non_suligner" href="./gestionmdp.php">&#9776;</a>
            <ul class="submenu">
            <?php if ($user): ?>
                <li><a class="non_suligner _espace" href="mon_compte.php">Mon compte</a></li>
                <li><a class="non_suligner _espace" href="./logout">Deconnexion</a></li>
            <?php endif; ?>
            </ul>
        </li>
    </div> 
    <nav class="barre"></nav>

    <div class="_espace">
        <header>
            <h1>DKS - Gestionnaire de Mots de Passe</h1>
        </header>

        <main>
            <!-- Ajouter un mot de passe -->
            <h2>Ajouter un mot de passe</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <input type="text" name="website" placeholder="Nom du site web" required>
                <input type="text" name="username" placeholder="Pseudo" required>
                <button type="submit">Générer et Ajouter</button>
            </form>

            <!-- Tableau des Mots de Passe -->
            <h2>Tableau des Mots de Passe</h2>
            <table>
                <thead>
                    <tr>
                        <th>Site Web</th>
                        <th>Pseudo</th>
                        <th>Mot de Passe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($passwords as $password): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($password['website']); ?></td>
                            <td><?php echo htmlspecialchars($password['username']); ?></td>
                            <td><?php echo htmlspecialchars($password['password']); ?></td>
                            <td>
                                <!-- Bouton modifier -->
                                <form method="GET" style="display:inline;">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?php echo $password['id']; ?>">
                                    <input type="submit" value="Modifier" />
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Formulaire de modification (apparaît lorsque l'on clique sur "Modifier") -->
            <?php if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])): ?>
                <h2>Modifier le mot de passe</h2>
                <?php 
                // Récupérer l'élément sélectionné pour modification
                $id = $_GET['id'];
                $stmt = $pdo->prepare("SELECT * FROM passwords WHERE id = ? AND user_id = ?");
                $stmt->execute([$id, $_SESSION['user']['id']]);
                $password_to_edit = $stmt->fetch();
                ?>

                <?php if ($password_to_edit): ?>
                    <form method="POST">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?php echo $password_to_edit['id']; ?>">
                        
                        <input type="text" name="website" placeholder="Nom du site web" value="<?php echo htmlspecialchars($password_to_edit['website']); ?>" required>
                        <input type="text" name="username" placeholder="Pseudo" value="<?php echo htmlspecialchars($password_to_edit['username']); ?>" required>
                        <input type="text" name="custom_password" placeholder="Mot de Passe (Laissez vide pour générer)" value="<?php echo htmlspecialchars($password_to_edit['password']); ?>">
                        <button type="submit">Mettre à jour</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>

        </main>
    </div>                    
</body>
</html>
