<?php
session_start();
//si bouton deconnexion appuyé alors destroy
if(isset($_POST['deconnexion'])) {
    session_destroy();
    header ('location:connexion.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Résevation</title>
</head>
<body class="index">
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
    <main>
        <article>
            <h2>Rooftop Grenelle</h2>
            <img src="img/rooftop_paris_terrasse_vue_seine.jpg">
            <p>Le RoofTop Grenelle offre un splendide panorama sur la Tour Eiffel, la Seine, le Trocadero.
            Nous y accueillons vos réunions de 25 à 50 personnes et vos soirées d'entreprises et événements privés
            jusqu'à 90 personnes.</p>
            <a href="reservation-form.php">Reserver cette Salle</a> <a href="planning.php">Consultez le Planning</a>
            <h4>Événements adaptés pour ce lieu</h4>
            <p>Le RoofTop Grenelle vous ouvrira ses portes avec une splendide vue sur la Tour Eiffel, la Seine et le
                Trocadéro. <br>
                Vous pourrez y organiser vos dîners privés, soirées d'entreprise, cocktails, workshops, conférence de
                presse, soirées dansante.</p>
            <h4>Avantages compétitifs</h4>
            <p>Prestation restauration avec une cuisine haut de gamme incluant le traiteur, la technique.</p>
            <h4>Accessibilité au lieu</h4>
            <p>Metro Bir Hakeim - Ligne 6 <br>
                Rer C - Champs de Mars <br>
                Parking privé au 2 Bd de Grenelle - 75 015 Paris</p>
            <h3>Activités proposées</h3>
            <button>Oenologie</button> <button>Karaoké</button>
            <h3>Installations accessibles</h3>
            <section><button>Piste de dance</button> <button>Espace détente</button> <button>Rooftop</button></section>
            <button>Parking sur Place</button>
            <h3>Equipements disponibles</h3>
            <section><button>Wifi</button> <button>Micro</button> <button>Equipement son</button> <button>Vestiaire</button>
            <button>Videoprojecteur</button> <button>Paperboard</button> <button>Ecran LCD</button></section>
        </article>
    </main>
    <footer>
        <br>
        <p>Réalisé par Marie</p>
        <br>
    </footer>
</body>
</html>