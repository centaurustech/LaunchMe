<?php
$title = 'Accueil';
include('header.php');
?>

<div class="container">
    <h1>Au revoir <?php echo $_SESSION['surname'].' '.$_SESSION['name']; ?> !</h1>

    <p class="lead">Merci de votre visite et à bientôt sur notre plateforme de financement !</br>
    Vous allez être redirigé vers la page <a href="index.php">d'accueil.</a></p>

</div>

<?php
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header('Refresh: 3; URL=index.php');
?>

<?php
include('footer.php');
?>
