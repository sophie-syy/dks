<div class="menu">
    <div class="en_ligne">
        <img src="./img/logo.jpg" alt="image" width="80">
        <div class="logo">Data Keep Safe</div>
    </div>
    <div class="menu1">
        <a class="non_suligner _espace" href="./p_acceuil.php">Accueil</a>
        <a class="non_suligner _espace" href="./p_abonnement.php">Abonnement</a>
        <a class="non_suligner _espace" href="./p_data.php">Met fichier</a>
        <a class="non_suligner _espace" href="./p_gestionmdp.php">Gestion de mot de passe</a>
        <a class="non_suligner _espace" href="./p_don.php">Don</a>
        <a class="non_suligner _espace espace_" href="./p_formation.php">Formation</a>
    </div>
    <div class="menu1 dropdown espace_">
        <a class="non_suligner" href="./p_acceuil.php">&#9776;</a>
        <ul class="submenu">
        <?php if ($user): ?>
            <li><a class="non_suligner _espace" href="p_mon_compte.php">Mon compte</a></li>
            <li><a class="non_suligner _espace" href="./f_logout">Déconnexion</a></li>
        <?php endif; ?>
        </ul>
    </div>

    <div class="menu2 dropdown espace_">
        <a class="non_suligner" href="./p_acceuil.php">&#9776;</a>
        <ul class="submenu">
        <?php if ($user): ?>
            <li><a class="non_suligner _espace" href="./p_acceuil.php">Accueil</a></li>
            <li><a class="non_suligner _espace" href="./p_abonnement.php">Abonnement</a></li>
            <li><a class="non_suligner _espace" href="./p_data.php">Met fichier</a></li>
            <li><a class="non_suligner _espace" href="./p_gestionmdp.php">Gestion de mot de passe</a></li>
            <li><a class="non_suligner _espace" href="./p_don.php">Don</a></li>
            <li><a class="non_suligner _espace espace_" href="./p_formation.php">Formation</a></li>                <li><a class="non_suligner _espace" href="mon_compte.php">Mon compte</a></li>
            <li><a class="non_suligner _espace" href="./f_logout">Déconnexion</a></li>
        <?php endif; ?>
        </ul>
    </div>
</div>
<nav class="barre"></nav>