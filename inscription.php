<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Inscription</title>
</head>
<?php
    require('classes/user-pdo.php');
    
    $user = new user(NULL, NULL);
    
    // enregistrement d'un nouvel user
    $user->register();

?>
<body>
    <!--Le formulaire doit contenir l’ensemble des champs présents dans la table
    “utilisateurs” (sauf “id”) ainsi qu’une confirmation de mot de passe. Dès
    qu’un utilisateur remplit ce formulaire, les données sont insérées dans la
    base de données et l’utilisateur est redirigé vers la page de connexion.-->


    <header>
        <h1>Salle de conférence</h1>
        <section>
            <a href="index.php">Accueil</a>
            <a href="reservation-form.php">Réservez</a>
            <a href="planning.php">Planning</a>
            <?php //si l'utilisateur est connecté
            if (isset($_SESSION['login']))
                // echo les liens necessaire
                echo ('<a href="profil.php">Profil</a></section>
                <form action="index.php" method="post"><input type="submit" name="deconnexion" id="logout" value="Déconnexion"/></form>');
            //si l'utilisateur est connecté
            else if (!isset($_SESSION['login']))
                // echo les liens necessaire
                echo ('<a href="inscription.php">Inscription</a>
                <a href="connexion.php">Connexion</a></section>');
            ?>
    </header>


    <!--formulaire d'inscription-->
    <div class="center">
        <h1>Inscription</h1>

        <form action="inscription.php" method="POST">

            <div class="txt_field">
                <input type="text" name="login" autofocus required>
                <span></span>
                <label for="login">Login</label> <!--champs login dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="password" name="password" required>
                <span></span>
                <label for="password">Mot de passe</label> <!--champs password dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="password" name="confirm_pass" required>
                <span></span>
                <label for="confirm_pass">Confirmez votre mot de passe</label> <!--une confirmation de mot de passe-->
            </div>

            <input type="submit" name="inscription" value="S'inscrire" action="connexion.php"><!--mon bouton inscription-->

            <div class="signup_link">
                Déjà membre? <a href="connexion.php">Connexion</a> <br>
                <a href="index.php">Accueil</a>
            </div>
        </form>
    </div>
</body>
</html>