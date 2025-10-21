<?php
require_once 'db.php';  // Connexion à la base de données
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $pseudo = trim($_POST['pseudo'] ?? '');
    $email = trim($_POST['email'] ?? '');  // Récupération de l'email
    $password = $_POST['password'] ?? '';

    // Vérifications des champs
    if ($nom === '' || $prenom === '' || $pseudo === '' || $email === '' || $password === '') {
        $errors[] = 'Tous les champs sont requis.';
    }

    if (strlen($pseudo) > 50) {
        $errors[] = 'Le pseudo est trop long (max 50 caractères).';
    }

    if (strlen($password) < 4) {
        $errors[] = 'Le mot de passe doit contenir au moins 4 caractères.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {  // Vérification de l'email
        $errors[] = 'L\'email n\'est pas valide.';
    }

    if (empty($errors)) {
        // Vérifier si le pseudo ou l'email existe déjà
        $stmt = $pdo->prepare('SELECT id FROM users WHERE pseudo = ? OR email = ? LIMIT 1');
        $stmt->execute([$pseudo, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Ce pseudo ou cet email est déjà utilisé.';
        } else {
            // Hachage du mot de passe
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertion dans la base de données
            $stmt = $pdo->prepare('INSERT INTO users (nom, prenom, pseudo, email, password) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$nom, $prenom, $pseudo, $email, $hash]);
            header('Location: index.php?registered=1');
            exit;
        }
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="./css/register.css">
  <meta charset="utf-8">
  <title>Inscription</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
  <div class="rectangle">
    <div class="en_ligne jus">
            <h1>Créer un compte</h1>
            <img class="_espace border" src="./img/logo.jpg" alt="image" width="80px" height="80px">
        </div>
    
    <?php if (!empty($errors)): ?>
      <div style="background:#ffe6e6;padding:1rem;border:1px solid #ffcccc;">
        <ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul>
      </div>
    <?php endif; ?>
    <form method="post" action="register.php" autocomplete="off">
      <label  >Nom <input  name="nom" required maxlength="100"></label>
      <label>Prénom <input  name="prenom" required maxlength="100"></label>
      <label>Pseudo <input  name="pseudo" required maxlength="50"></label>
      <label>Email <input  name="email" type="email" required maxlength="100"></label>
      <label>Mot de passe <input  name="password" type="password" required></label>
      <button class="bord" type="submit" >S'inscrire</button>
    </form>
    <p>Déjà inscrit ? <a href="index.php">Se connecter</a></p>
  </div>
</body>
</html>
