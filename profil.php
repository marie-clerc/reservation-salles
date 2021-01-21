<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Inscription</title>
</head>
<?php
// ouverture de la session
session_start();
// Vérifiez si l'utilisateur est connecté, sinon redirige-le vers la page de connexion
if (isset($_SESSION['id'])) {

    require('classes/user-pdo.php');

    $user = new user(NULL, NULL);

    // connexion user
    $user->connect();
    $user->updateProfil();
}
else {
    header ('location: connexion.php');
}


?>
<body>
    <!--Cette page possède un formulaire permettant à l’utilisateur de modifier son
    login et son mot de passe.-->


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


    <!--formulaire de modification des informations du user-->
    <div class="center">
        <h1>Modifier mon Profil</h1>

        <form action="profil.php" method="post">
        
            <div class="txt_field">
                <input type="text" name="login" value="<?php echo $_SESSION['login'];?>">
                <span></span>
                <label for="login">Login</label> <!--champs login dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="password" name="password" placeholder="entrez votre mdp">
                <span></span>
                <label for="password">Votre mot de passe actuel</label> <!--champs password dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="password" name="new_pass">
                <span></span>
                <label for="new_pass">Nouveau mot de passe</label>
            </div>

            <div class="txt_field">
                <input type="password" name="confirm_new_pass">
                <span></span>
                <label for="confirm_new_pass">Confirmez votre nouveau mot de passe</label>
            </div>

            <input type="submit" name="update" value="Mettre à jour"> <!--mon bouton update-->

            <div class="signup_link">
                <a href="index.php">Accueil</a>
            </div>

        </form>
    </div>
</body>
</html>