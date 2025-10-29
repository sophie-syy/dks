<?php
session_start();

// Vider toutes les variables de session
$_SESSION = [];

// Supprimer le cookie de session s'il existe
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// Détruire la session côté serveur
session_destroy();

header('Location: ./index.php');
exit;
?>
