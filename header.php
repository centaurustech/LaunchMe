<?php
session_start();

if (isset($_SESSION['lastActivity']) && (time() - $_SESSION['lastActivity'] > 900)) {
    session_unset();
    session_destroy();
} else {
    $_SESSION['lastActivity'] = time();

}
?>

<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/html">
<head>
    <link rel="icon" href="favicon.ico">

    <title>LaunchMe - <?php echo $title; ?></title>
    <meta charset="UTF-8">

    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="dist/css/custom-theme.css" rel="stylesheet">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="dist/js/jquery.js"></script>
    <script type="text/javascript" src="dist/js/bootstrap-filestyle.min.js"> </script>

</head>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img style="max-width: 30px; margin-top: -3px;" src="images/logo.png"></a>
            <a class="navbar-brand" href="index.php">LaunchMe</a>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Accueil</a></li>
                <?php
                if (isset($_SESSION['id'])) {
                    ?>
                    <li><a href="projects.php">Projets</a></li>
                <?php
                }
                ?>
                <li><a href="#about">A propos</a></li>
                <li><a href="#contact">Contact</a></li>

            </ul>

            <?php
            if (isset($_SESSION['id'])) {
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="your_profile.php"><?php echo $_SESSION['surname'] . ' ' . $_SESSION['name'] ?></a></li>
                    <li><a href="disconnect.php"><span class="glyphicon glyphicon-off"></span></a></li>
                </ul>
            <?php
            } else {
                ?>
                <form class="navbar-form navbar-right" role="search">
                    <a class="btn btn-default" href="login.php" role="button">Connexion</a>
                    <a class="btn btn-default" href="register.php" role="button">Inscription</a>
                </form>

            <?php
            }
            ?>
        </div>

    </div>

</div>

<body>