<?php
$title = 'Inscription';
include('header.php');

if (!isset($_SESSION['id']) || $_SESSION['accountType'] == 'investisseur' ) {
    ?>
    <div class="container">
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

        <h1>Nouveau projet</h1>
        <?php
        $name;
        $picture;
        $description;
        $goal;
        $errorMessage = null;
        $errorBegin = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ';
        $errorEnd = '</br>';

        if (isset($_POST['submit'])) {
            include('db_inc.php');
            $db = new PDO($server . ':host=' . $host . ';dbname=' . $base, $user, $pass);

            if (empty($_POST['name'])) {
                $errorMessage .= $errorBegin . 'Vous devez renseigner un nom.' . $errorEnd;

            } else {
                $name = $db->quote($_POST['name']);

            }

            /*if (empty($_POST['picture'])) {
                $errorMessage .= $errorBegin . 'Vous devez mettre une illustration' . $errorEnd;

            } else {
                $surname = $db->quote($_POST['picture']);

            }*/

            if (empty($_POST['description'])) {
                $errorMessage .= $errorBegin . 'Vous devez mettre une description' . $errorEnd;

            } else {
                $description = $db->quote($_POST['description']);

            }

            if (empty($_POST['goal'])) {
                $errorMessage .= $errorBegin . 'Vous devez spécifier un objectif' . $errorEnd;

            } else {
                $goal = $db->quote($_POST['goal']);

            }


            if ($errorMessage == null) {
                $request = 'INSERT INTO projet (ID_Initiateur, Nom, Description, SommeNecessaire) VALUES ('.$_SESSION['id'].', '.$name.', '.$description.', '.$goal.');';
                $db->query($request);

                echo '<div class="alert alert-success" role="alert"> Votre projet a été crée !</div>';
                include('footer.php');
                exit;

            } else {
                echo '<div class="alert alert-danger" role="alert">
             ' . $errorMessage . '
             </div>';
            }
        }


        ?>
        <form role="form" action="new_project.php" method="post">
            <h2>Création d'un projet</h2>
            <div class="form-group">
                <label for="name">Nom :</label>
                <input type="text" class="form-control" name="name">
            </div>
            <div class="form-group">
                <label for="picture">Illustration :</label>
                <input type="file" class="filestyle" data-buttonText="Parcourir" name="picture">
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea class="form-control" name="description" rows="10"></textarea>
            </div>
            <div class="form-group">
                <label for="goal">Objectif :</label>
                <input type="number" class="form-control" name="goal">
            </div>
            <button type="submit" name="submit" class="btn btn-default">Envoyer</button>
        </form>
    </div>

<?php
include('footer.php');
?>