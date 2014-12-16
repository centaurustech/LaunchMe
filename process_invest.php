<?php
$title = 'Traitement de l\'investissement';
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
?>
<div class="container">
    <h1>Traitement de l'investissement</h1>

    <?php
    $errorMessage = null;
    $errorBegin = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ';
    $errorEnd = '</br>';

    if (isset($_POST['submit'])){
        include('db_inc.php');
        $db = new PDO($server . ':host=' . $host . ';dbname=' . $base, $user, $pass);
        $projectId = $db->quote($_POST['id']);
        $request1 = 'SELECT SommeNecessaire FROM projet WHERE ID='.$projectId.' ;';
        $goal = $db->query($request1);
        $goal = $goal->fetch();
        $goal = $goal['SommeNecessaire'];
        $request2 = 'SELECT SUM(Montant) AS MontantTotal FROM investissement WHERE ID_Projet='.$projectId.';';
        $totalAmount = $db->query($request2);
        $totalAmount = $totalAmount->fetch();
        $totalAmount = $totalAmount['MontantTotal'];
        //$request3 = 'SELECT COUNT(ID) FROM investissement WHERE ID_Projet='.$projectId.';';
        //$totalInvestNb = $db->query($request3);

        $request3 = 'SELECT COUNT(ID) AS Compte FROM investissement WHERE ID_Investisseur='.$_SESSION['id'].';';
        $investNb = $db->query($request3);
        $investNb = $investNb->fetch();
        $investNb = $investNb['Compte'];

        if (!empty($_SESSION['bank'])){
            if($investNb >= 1){
                $errorMessage = 'Vous ne pouvez pas investir plus d\'une fois sur un projet.';

            }

            if(empty($_POST['invest'])){
                $errorMessage = 'Vous devez spécifier un montant.';

            } else {
                $invest = $db->quote($_POST['invest']);

            }

            $estimatedAmount = $totalAmount + $_POST['invest'];
            if ($estimatedAmount > $goal) {
                $errorMessage = 'Votre investissement dépasse l\'objectif du projet.</br>Vous pouvez investir '.$goal - $totalAmount.' € ou moins.';

            }


        } else {
            $errorMessage = 'Vous devez renseigner vos coordonnées bancaires dans votre <a href="your_profile.php">profil</a>.';

        }

        if ($errorMessage == null) {
            $request4 = 'INSERT INTO investissement (ID_Investisseur, ID_Projet, Montant) VALUES ('.$_SESSION['id'].', '.$projectId.', '.$invest.')';
            $db->query($request4);

            echo '<div class="alert alert-success" role="alert">Votre investissement à bien été enregistré.</div>';
            include('footer.php');
            exit;

            header('Refresh: 3; URL=see_project.php?id='.$_POST['id'].'');

        } else {
            echo '<div class="alert alert-danger" role="alert">
             ' . $errorMessage . '
             </div>';
            header('Refresh: 3; URL=invest.php?id='.$_POST['id'].'');

        }
    }


    ?>

</div>

<?php
include('footer.php');
?>
