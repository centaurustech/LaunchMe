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

if ($_SESSION['accountType'] == 'investisseur') {
    $allTabState;
    $mineTabState;

    if (isset($_GET['view'])) {
        switch ($_GET['view']) {
            case 'mine':
                $mineTabState = 'active';
                break;
            default:
                $allTabState = 'active';
                break;
        }
    } else {
        $allTabState = 'active';
    }
}

include('db_inc.php');
$db = new PDO($server . ':host=' . $host . ';dbname=' . $base, $user, $pass);

?>

<div class="container">
    <h1>Liste des projets</h1>
    <?php
    if ($_SESSION['accountType'] == 'investisseur') {

        ?>
    <ul class="nav nav-tabs">
        <li role="presentation" class="<?php echo $allTabState; ?>"><a href="projects.php?view=all">Tous les projets</a>
        </li>
        <li role="presentation" class="<?php echo $mineTabState; ?>"><a href="projects.php?view=mine">Mes investissements</a>
        </li>
    </ul>
    </br>
    <?php
    } else {
        ?>
        <a class="btn btn-default" href="new_project.php" role="button"><span class="glyphicon glyphicon-plus"
                                                                          aria-hidden="true"></span> Nouveau projet</a>
    </br>
    </br>
    <?php
    }
    ?>
    <table class="table">
        <tr>
            <td><b>Nom</b></td>
            <td><b>Objectif</b></td>
            <td><b>Montant actuel</b></td>
            <td><b>Contributeurs</b></td>
        </tr>
        <?php
        $request = null;

        if ($_SESSION['accountType'] == 'investisseur') {
            if (isset($_GET['view'])) {
                switch ($_GET['view']) {
                    case 'mine':
                        $request = 'SELECT * FROM projet P, investissement I WHERE P.ID=I.ID_Projet AND I.ID_Investisseur=' . $_SESSION['id'] . ';';
                        break;
                    default:
                        $request = 'SELECT * FROM projet ;';
                        break;
                }
            } else {
                $request = 'SELECT * FROM projet ;';
            }
        } elseif ($_SESSION['accountType'] == 'initiateur') {
            $request = 'SELECT * FROM projet WHERE ID_Initiateur=' . $_SESSION['id'] . ';';
        }

        $result = $db->query($request);
        if ($array = $result->fetchAll()) {
            foreach ($array as $line) {
                echo '<tr><td><a class="table" href="see_project.php?id=' . $line['ID'] . '">' . $line['Nom'] . '</a></td>';
                echo '<td>' . $line['SommeNecessaire'] . '</td>';
                echo '<td>TBA</td>';
                echo '<td>TBA</td></tr>';
            }
        }
        ?>
    </table>

</div>

<?php
include('footer.php');
?>
