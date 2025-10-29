<?php
require_once 'f_db.php';
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user']) || empty($_SESSION['user']['pseudo'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$userPseudo = $user['pseudo'];
$uploadDir = 'database/' . $userPseudo . '/';

// Vérifie si le fichier existe et appartient à l'utilisateur
if (isset($_GET['file'])) {
    $file = basename($_GET['file']);
    $filePath = $uploadDir . $file;

    if (file_exists($filePath)) {
        // Envoie le fichier au navigateur
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        readfile($filePath);
        exit;
    } else {
        echo "Fichier introuvable.";
    }
} else {
    echo "Aucun fichier spécifié.";
}
?>
