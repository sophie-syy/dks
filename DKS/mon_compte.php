<?php
require_once 'db.php';
session_start();

// Vérification si l'utilisateur est bien connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['user']['id'];

// Récupérer les informations de l'utilisateur depuis la base de données
$query = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$query->execute(['id' => $userId]);
$user = $query->fetch();

// Si le formulaire a été soumis pour modifier les informations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // On récupère les nouvelles données du formulaire
    $newPseudo = isset($_POST['pseudo']) ? $_POST['pseudo'] : $user['pseudo'];
    $newEmail = isset($_POST['email']) ? $_POST['email'] : $user['email'];
    
    // Si un nouveau mot de passe a été entré, on le hache
    $newPassword = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

    // Vérifier si l'email est déjà utilisé par un autre utilisateur
    $emailQuery = $pdo->prepare("SELECT * FROM users WHERE email = :email AND id != :id");
    $emailQuery->execute(['email' => $newEmail, 'id' => $userId]);
    if ($emailQuery->rowCount() > 0) {
        $error = "Email déjà utilisé par un autre utilisateur.";
    } else {
        // Mise à jour des informations de l'utilisateur dans la base de données
        $updateQuery = $pdo->prepare("UPDATE users SET pseudo = :pseudo, email = :email, password = :password WHERE id = :id");
        $updateQuery->execute([
            'pseudo' => $newPseudo,
            'email' => $newEmail,
            'password' => $newPassword,
            'id' => $userId
        ]);

        // Mettre à jour les informations dans la session
        $_SESSION['user']['pseudo'] = $newPseudo;
        $_SESSION['user']['email'] = $newEmail;
        $success = "Informations mises à jour avec succès.";
    }
}

// Si le formulaire de suppression de compte est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Supprimer les données associées à cet utilisateur (mots de passe, etc.)
    $deletePasswordsQuery = $pdo->prepare("DELETE FROM passwords WHERE user_id = :id");
    $deletePasswordsQuery->execute(['id' => $userId]);

    // Supprimer l'utilisateur de la table users
    $deleteUserQuery = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $deleteUserQuery->execute(['id' => $userId]);

    // Déconnecter l'utilisateur après la suppression
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte</title>
    <link rel="stylesheet" href="./css/mon_compte.css">
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
        
        <header>
            <div>
                <h1>Mon compte : <?php echo htmlspecialchars($user['pseudo'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>
            </div>
        </header>

        <div class="card">
            <h2>Mes informations</h2>

            <!-- Affichage du pseudo et de l'email -->
            <p><strong>Pseudo :</strong> <?php echo htmlspecialchars($user['pseudo'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>

            <!-- Formulaire pour modifier les informations -->
            <h2>Modifier mes informations</h2>
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="POST">
                <label for="pseudo">Pseudo:</label>
                <input type="text" id="pseudo" name="pseudo" value="<?php echo htmlspecialchars($user['pseudo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="password">Nouveau mot de passe:</label>
                <input type="password" id="password" name="password" placeholder="Laisser vide si pas de changement">

                <button type="submit" name="update">Mettre à jour</button>
            </form>

            <!-- Formulaire pour supprimer le compte -->
            <h2>Supprimer mon compte</h2>
            <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')">
                <button type="submit" name="delete">Supprimer mon compte</button>
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
