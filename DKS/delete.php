<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['user']) || empty($_SESSION['user']['pseudo'])) {
    header("Location: index.php"); 
    exit();
}

$user = $_SESSION['user']; 
$userId = $user['id']; 

if (isset($_GET['file'])) {
    $fileName = $_GET['file'];
    $filePath = 'database/' . $user['pseudo'] . '/' . $fileName;

    if (file_exists($filePath) && is_file($filePath)) {
        if (unlink($filePath)) {
            header("Location: data.php");
            exit();
        } else {
            echo "Erreur lors de la suppression du fichier.";
        }
    } else {
        echo "Le fichier n'existe pas.";
    }
} else {
    echo "Aucun fichier spécifié pour suppression.";
}
?>
