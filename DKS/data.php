<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['user']) || empty($_SESSION['user']['pseudo'])) {
    header("Location: index.php"); 
    exit();
}

$user = $_SESSION['user']; 
$userId = $user['id']; 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier'])) {
  
    $fileName = $_FILES['fichier']['name'];
    $fileTmp = $_FILES['fichier']['tmp_name'];
    $fileSize = $_FILES['fichier']['size'];
    $fileError = $_FILES['fichier']['error'];

 
    $uploadDir = 'database/' . $user['pseudo'] . '/';

    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            die("Impossible de créer le dossier utilisateur ! Vérifie les permissions.");
        }
    }

    if ($fileError === 0) {
        $destination = $uploadDir . basename($fileName);
    
        if (move_uploaded_file($fileTmp, $destination)) {
            echo "Le fichier a été téléchargé avec succès !<br>";
        } else {
            echo "Erreur lors du téléchargement du fichier.<br>";
        }
    } else {
        echo "Erreur lors du téléchargement du fichier : " . $fileError . "<br>";
    }
}

$uploadedFiles = [];
if (file_exists('database/' . $user['pseudo'])) {
    $uploadedFiles = array_diff(scandir('database/' . $user['pseudo']), array('.', '..'));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Utilisateur - Gestion des Fichiers</title>
</head>
<body>

<h1>Bienvenue, <?= htmlspecialchars($user['nom']) ?> !</h1>
<p>Voici votre espace de gestion de fichiers.</p>

<h2>Importer un fichier</h2>
<form method="post" enctype="multipart/form-data">
    <label for="fichier">Choisir un fichier à importer :</label>
    <input type="file" name="fichier" id="fichier" required>
    <button type="submit">Importer</button>
</form>

<h2>Vos fichiers</h2>
<?php if (count($uploadedFiles) > 0): ?>
    <ul>
        <?php foreach ($uploadedFiles as $file): ?>
            <li>
                <a href="<?= 'database/' . $user['pseudo'] . '/' . $file ?>" target="_blank"><?= htmlspecialchars($file) ?></a>
                <a href="delete.php?file=<?= urlencode($file) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')">Supprimer</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun fichier téléchargé pour le moment.</p>
<?php endif; ?>
<a href="./acceuil.php">acceuil</a>
<a href="deconnexion.php">Se déconnecter</a>

</body>
</html>
