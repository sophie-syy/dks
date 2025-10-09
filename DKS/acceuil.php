
<?php
require_once 'db.php';
session_start();
$user = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="./css/acceuil.css">
    <meta charset="UTF-8">   

</head>
<body>
    <header>
        <div>
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?>!</h1>
            <title>Accueil</title>
        </div>
        <div>
            <nav>
            <?php if ($user): ?>
            <a href="mon_compte.php">Mon compte</a> 
            <?php endif; ?>
        </nav> 
        </div>

    </header>
    <div class="menu">
    <p>test</p>
    <a href="./data.php">"met fichier </a>
    <a href="./gestionmdp.php">"gestion de mot de passe</a>
    
    </div>
    <div class="card">
        <h1>test</h1>
    </div>

    
</body>
</html>

