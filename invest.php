<?php
$title = 'Inscription';
include('header.php');

if (!isset($_SESSION['id']) || $_SESSION['accountType'] == 'initiateur' ) {
    ?>
    <div class="container" xmlns="http://www.w3.org/1999/html">
        <div class="alert alert-warning" role="alert">
            Vous ne pouvez pas accéder à cette page.
        </div>
    </div>

    <?php
    include('footer.php');
    exit;
}


include('db_inc.php');
$db = new PDO($server . ':host=' . $host . ';dbname=' . $base, $user, $pass);
$projectId = $db->quote($_GET['id']);
$request = 'SELECT * FROM projet WHERE ID=' . $projectId . ';';
$result = $db->query($request);

if ($result = $result->fetch()) {
    $name = $result['Nom'];
    $description = $result['Description'];
    $goal = $result['SommeNecessaire'];
    $creatorId = $result['ID_Initiateur'];
} else {
    ?>
    <div class="container">
        <div class="alert alert-warning" role="alert">
            Le projet que vous essayez de visualiser n'existe pas.

        </div>
    </div>

    <?php
    include('footer.php');
    exit;
}
?>

<div class="container">
    <h1>Investir</h1>
    <p>
        Vous vous apprétez à investir sur le projet <?php echo $name; ?>.
    </p>
    <form role="form" action="process_invest.php" method="post">
        <div class="form-group">
            <label for="invest">Combien souhaitez vous investir ?</label>
            <input type="number" class="form-control" name="invest">
        </div>
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
        <button type="submit" name="submit" class="btn btn-default">Envoyer</button>

    </form>
    <nav>
        <ul class="pager">
            <li class="previous"><a href="projects.php"><span aria-hidden="true">&larr;</span> Retour</a></li>
        </ul>
    </nav>
</div>

<?php
include('footer.php');
?>
