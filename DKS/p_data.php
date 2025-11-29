<?php
require_once 'f_db.php';
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user']) || empty($_SESSION['user']['pseudo'])) {
    header("Location: index.php"); 
    exit();
}

$user = $_SESSION['user'];
$userId = $user['id'];
$userPseudo = $user['pseudo'];
$uploadDir = 'database/' . $userPseudo . '/';

// Fonction pour calculer la taille totale d'un dossier
function folderSize($dir) {
    $size = 0;
    foreach (scandir($dir) as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        $size += is_file($path) ? filesize($path) : folderSize($path);
    }
    return $size;
}

// Limite de stockage : 40 Go (en octets)
$maxStorageBytes = 40 * 1024 * 1024 * 1024;
$currentStorageBytes = folderSize($uploadDir);

// Créer le dossier utilisateur s'il n'existe pas
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        die("Erreur : Impossible de créer le dossier utilisateur.");
    }
}

// Traitement de l'upload du fichier
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier'])) {
    $file = $_FILES['fichier'];
    $fileName = basename($file['name']);
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Vérification des erreurs de téléchargement
    if ($fileError === UPLOAD_ERR_INI_SIZE || $fileError === UPLOAD_ERR_FORM_SIZE) {
        $message = "Le fichier est trop volumineux. La taille maximale autorisée est de 40 Go.";
    } elseif ($fileError === 0) {
        // Vérification de la limite de 40 Go
        if (($currentStorageBytes + $fileSize) > $maxStorageBytes) {
            $message = "Espace insuffisant. Vous avez dépassé la limite de 40 Go.";
        } else {
            $destination = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmp, $destination)) {
                $message = "Fichier téléchargé avec succès.";
                $currentStorageBytes += $fileSize;
            } else {
                $message = "Erreur lors du téléchargement du fichier.";
            }
        }
    } else {
        $message = "Erreur lors du téléchargement du fichier. Code d'erreur : $fileError";
    }
}

// Liste des fichiers téléchargés
$uploadedFiles = [];
if (file_exists($uploadDir)) {
    $uploadedFiles = array_diff(scandir($uploadDir), ['.', '..']);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Utilisateur - Fichiers</title>
    <link rel="stylesheet" href="./css/acceuil.css">
</head>
<body>
<?php include __DIR__ . "/f_menu.php"; 
include __DIR__ . "/f_pub.php";?> 

    <main class="main">
        <h1>Bienvenue, <?= htmlspecialchars($user['nom']) ?> !</h1>
        <p>Voici votre espace personnel de gestion de fichiers.</p>

        <h2>Importer un fichier</h2>
        <?php if ($message): ?>
            <p><strong><?= htmlspecialchars($message) ?></strong></p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="fichier">Choisissez un fichier :</label>
            <input type="file" name="fichier" id="fichier" required>
            <button type="submit">Importer</button>
        </form>

        <?php
            $usedGB = round($currentStorageBytes / (1024 * 1024 * 1024), 2);
            $maxGB = 40;
        ?>
        <p>Espace utilisé : <?= $usedGB ?> Go / <?= $maxGB ?> Go</p>

        <h2>Vos fichiers</h2>
        <?php if (count($uploadedFiles) > 0): ?>
            <ul>
                <?php foreach ($uploadedFiles as $file): ?>
                    <li>
                        <!-- Afficher le lien vers le fichier avec un chemin relatif -->
                        <a href="f_download.php?file=<?= urlencode($file) ?>" target="_blank"><?= htmlspecialchars($file) ?></a>
                        <a href="f_delete.php?file=<?= urlencode($file) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')">Supprimer</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun fichier pour le moment.</p>
        <?php endif; ?>
    </main>
    <?php include __DIR__ . "/f_pub2.php";  ?>  

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
