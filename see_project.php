<?php
$title = 'Accueil';
include('header.php');

if (!isset($_SESSION['id'])) {
    ?>
    <div class="container">
        <div class="alert alert-warning" role="alert">
            Vous devez être connecté pour visualiser cette page.

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

$request2 = 'SELECT COUNT(ID) AS NbInvest FROM investissement WHERE ID_Projet='.$projectId.';';
$totalInvestNb = $db->query($request2);
$totalInvestNb = $totalInvestNb->fetch();
$totalInvestNb = $totalInvestNb['NbInvest'];

?>

<div class="container">
    <h1>Projet</h1>

    <div class="row">
        <h2><?php echo $name; ?></h2>

        <div class="col-lg-4">
            <h3>Objectif : <?php echo $goal; ?> €</h3>
        </div>
        <div class="col-lg-4">
            <h3>Contributeurs : <?php echo $totalInvestNb; ?></h3>
        </div>
        <div class="col-lg-4" style="text-align: right">
            <?php
            if ($_SESSION['accountType'] == 'investisseur') {
                $request3 = 'SELECT COUNT(ID) AS Compte FROM investissement WHERE ID_Investisseur=' . $_SESSION['id'] . ';';
                $investNb = $db->query($request3);
                $investNb = $investNb->fetch();
                $investNb = $investNb['Compte'];

                if ($investNb >= 1) {
                    ?>
                    <a class="btn btn-default" href="invest.php?id=<?php echo $_GET['id']; ?>&mode=modify" role="button"><span
                            class="glyphicon glyphicon-repeat"
                            aria-hidden="true"></span> Modifier l'investissement</a>
                <?php
                } else {
                    ?>
                    <a class="btn btn-default" href="invest.php?id=<?php echo $_GET['id']; ?>&mode=new"
                       role="button"><span
                            class="glyphicon glyphicon-plus"
                            aria-hidden="true"></span> Investir</a>
                <?php
                }
            } elseif ($_SESSION['accountType'] == 'initiateur' && $_SESSION['id'] == $creatorId) {
                ?>
                <a class="btn btn-default" href="supprProjet.php?id=<?php echo $_GET['id']; ?>&mode=new" role="button"><span
                        class="glyphicon glyphicon-plus"
                        aria-hidden="true"></span> Supprimer le projet</a>
            <?php
            }
            ?>
        </div>

    </div>
    <a class="btn btn-default" href="GenPDF.php?id=<?php echo $_GET['id']; ?>" role="button"><span
            class="glyphicon glyphicon-repeat"
            aria-hidden="true"></span> Génerer un PDF</a>
    <div class="row">
        </br>
        <?php
        $request1 = 'SELECT SUM(Montant) AS MontantTotal FROM investissement WHERE ID_Projet='.$projectId.';';
        $totalAmount = $db->query($request1);
        $totalAmount = $totalAmount->fetch();
        $totalAmount = $totalAmount['MontantTotal'];

        ?>
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                 style="width: <?php echo $totalAmount / $goal * 100; ?>%;">
                <?php echo $totalAmount; ?> €
            </div>
        </div>
    </div>
    <p>
        <?php echo $description; ?>

    </p>
    <nav>
        <ul class="pager">
            <li class="previous"><a href="projects.php"><span aria-hidden="true">&larr;</span> Retour</a></li>
        </ul>
    </nav>
</div>

<?php
include('footer.php');
?>
