<?php
require_once 'db.php';
session_start();

// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION['user']) && !empty($_SESSION['user']['pseudo'])) {
    // Si l'utilisateur est déjà connecté, le redirige vers l'accueil
    header("Location: acceuil.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($pseudo === '' || $password === '') {
        $errors[] = 'Pseudo et mot de passe requis.';
    } else {
        // Préparation de la requête pour récupérer l'utilisateur
        $stmt = $pdo->prepare('SELECT id, pseudo, password, nom, prenom FROM users WHERE pseudo = ? LIMIT 1');
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Sécurise la session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'pseudo' => $user['pseudo'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom']
            ];
            // Redirige vers la page d'accueil après une connexion réussie
            header('Location: acceuil.php');
            exit;
        } else {
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
    <style>body{font-family:Arial;max-width:700px;margin:2rem auto;padding:1rem;}label{display:block;margin-top:.5rem;}input{padding:.5rem;width:100%;}</style>
</head>
<body>
    <h1>Connexion</h1>
    
    <?php if (isset($_GET['registered'])): ?>
        <div style="background:#e6ffea;padding:1rem;border:1px solid #cceacc;">Inscription réussie — connectez-vous.</div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div style="background:#ffe6e6;padding:1rem;border:1px solid #ffcccc;">
            <ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul>
        </div>
    <?php endif; ?>

    <form method="post" action="index.php" autocomplete="off">
        <label>Pseudo <input name="pseudo" required></label>
        <label>Mot de passe <input name="password" type="password" required></label>
        <button type="submit" style="margin-top:1rem;padding:.5rem 1rem;">Se connecter</button>
    </form>
    
    <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
</body>
</html>
