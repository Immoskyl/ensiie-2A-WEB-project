<?php
session_start();
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1.0">
  <title>recette</title>
  <link href="http://fonts.googleapis.com/css?family=Montserrat:400|Kite+One:400,400" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/standardize.css">
  <link rel="stylesheet" href="css/recette-grid.css">
  <link rel="stylesheet" href="css/recette.css">
</head>
<body class="body page-recette clearfix">
<header class="cuisine clearfix">
    <div class="logo"></div>
    <p class="accroche">L'association des fins gourmets de l'ENSIIE !</p>
    <?php
    if(!isset($_SESSION['membre_id']))
    {
        ?>
        <div onClick="window.location.href='./membres/connexion.php';" class="connexion connexion-1 clearfix">
            <p class="connexion">Connexion</p>
        </div>
        <div onClick="window.location.href='./membres/inscription.php';" class="inscription inscription-1 clearfix">
            <p class="inscription">Inscription</p>
        </div>
        <?php
    }
    else
    {
        ?>
        <div onClick="window.location.href='./membres/moncompte.php';" class="connexion connexion-1 clearfix">
            <p class="connexion">Mon compte</p>
        </div>
        <div onClick="window.location.href='./membres/deconnexion.php';" class="connexion connexion-1 clearfix">
            <p class="connexion">Deconnexion</p>
        </div>
        <?php
    }
    ?>



    <nav class="navigation clearfix">
        <div onClick="window.location='index.php';" class="accueil accueil-1 clearfix">
            <p class="accueil">Accueil</p>
        </div>
        <div onClick="window.location='menu.php';" class="menu menu-1 clearfix">
            <p class="menu">Menu</p>
        </div>
        <div onClick="window.location='reservation.php';" class="reservation reservation-1 clearfix">
            <p class="reservation">Réservation</p>
        </div>
        <div onClick="window.location='catalogue.php';" class="recettes recettes-1 clearfix">
            <p class="recettes">Recettes</p>
        </div>
    </nav>
</header>

    <?php



    $dbName = getenv('DB_NAME');
    $dbUser = getenv('DB_USER');
    $dbPassword = getenv('DB_PASSWORD');
    $db = new PDO("pgsql:host=postgres user=$dbUser dbname=$dbName password=$dbPassword");

    $reponse = $db->query('SELECT titre, nom,  ingredients, instructions, membre_pseudo FROM recette LEFT JOIN membres ON id_author=membre_id JOIN type ON type.id=recette.id_type');


    while ($donnees = $reponse->fetch()) {

      ?>

  <section class="recette clearfix">

      <div class="titre titre-1 clearfix">
        <div class="tit"></div>
          <p class="titre"><?php echo $donnees['titre']; ?> :</p>
          <p class="type">Type : <?php echo $donnees['nom']; ?></p>
          <p class="author">Auteur : <?php echo $donnees['membre_pseudo']; ?></p>
      </div>


      <div class="ingredients ingredients-1 clearfix">
        <div class="liste clearfix">
          <p class="ingredients">Ingrédients :</p>
          <p class="preparation"><?php echo $donnees['ingredients']; ?></p>
        </div>
      </div>

      <div class="container clearfix">
        <p class="preparation">Recette :</p>
        <p class="preparation"><?php echo $donnees['instructions']; ?></p>
      </div>

  </section>
        <?php
    }
    ?>
  <footer class="cuisine cuisine-2 clearfix">
    <p class="adresse">1, square de la Résistance</p>
    <p class="code_postal">91000 Evry</p>
  </footer>
</body>
</html>
