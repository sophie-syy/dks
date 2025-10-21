<?php
require_once 'db.php';
session_start();

// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION['user']) && !empty($_SESSION['user']['pseudo'])) {
    // Si l'utilisateur est déjà connecté, on le redirige vers l'accueil
    header("Location: acceuil.php");
    exit();
}

$errors = [];

// Si le formulaire de connexion est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo'] ?? '');
    $password = $_POST['password'] ?? '';

    // Vérification des champs requis
    if ($pseudo === '' || $password === '') {
        $errors[] = 'Pseudo et mot de passe sont requis.';
    } else {
        // Préparation de la requête pour récupérer l'utilisateur
        $stmt = $pdo->prepare('SELECT id, pseudo, password, nom, prenom FROM users WHERE pseudo = ? LIMIT 1');
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch();

        // Vérification des identifiants
        if ($user && password_verify($password, $user['password'])) {
            // Récupérer les informations de l'utilisateur et sécuriser la session
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'pseudo' => $user['pseudo'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom']
            ];

            // Rediriger vers l'accueil après une connexion réussie
            header('Location: acceuil.php');
            exit;
        } else {
            // Ajout d'un message d'erreur si les identifiants sont incorrects
            $errors[] = 'Identifiants invalides.';
        }
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="./css/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

    <div class="rectangle">
        <div class="en_ligne jus">
            <h1>Connexion</h1>
            <img class="border" src="./img/logo.jpg" alt="image" width="80px">
        </div>
        
        <!-- Message de succès après l'inscription -->
        <?php if (isset($_GET['registered'])): ?>
            <div class="success">Inscription réussie — connectez-vous.</div>
        <?php endif; ?>

        <!-- Affichage des erreurs -->
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach($errors as $e): ?>
                        <li><?php echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form method="POST" action="index.php" autocomplete="off">
            <label for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" required>
            
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>

            <button class="bord" type="submit">Se connecter</button>
        </form>

        <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>

    </div>

</body>
</html>
