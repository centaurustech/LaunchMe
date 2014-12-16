<?php
$title = 'Mon profil';
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

$name;
$surname;
$mail;
$password;
$passwordConfirm;
$bank;
$errorMessage = null;
$errorBegin = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ';
$errorEnd = '</br>';

?>
<div class="container">
    <h1>Votre profil</h1>

    <form role="form" action="your_profile.php" method="post">
        <h2>Vos informations personelles</h2>
        <?php
        if (isset($_POST['submitChange'])) {
            include('db_inc.php');
            $db = new PDO($server . ':host=' . $host . ';dbname=' . $base, $user, $pass);

            if (empty($_POST['name'])) {
                $errorMessage .= $errorBegin . 'Vous devez renseigner votre nom.' . $errorEnd;

            } else {
                $name = $db->quote($_POST['name']);

            }

            if (empty($_POST['surname'])) {
                $errorMessage .= $errorBegin . 'Vous devez renseigner votre prénom.' . $errorEnd;

            } else {
                $surname = $db->quote($_POST['surname']);

            }

            if (empty($_POST['mail'])) {
                $errorMessage .= $errorBegin . 'Vous devez renseigner votre adresse mail.' . $errorEnd;

            } elseif (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                $errorMessage .= $errorBegin . 'Votre adresse mail est invalide.' . $errorEnd;

            } else {
                $mail = $db->quote($_POST['mail']);

            }


            if ($errorMessage == null) {
                $request = null;
                $accountType = $_SESSION['accountType'];

                switch ($accountType) {
                    case "initiateur" :
                        $request = 'UPDATE initiateur SET Nom=' . $name . ', Prenom=' . $surname . ', Mail=' . $mail . ' WHERE ID=' . $_SESSION['id'] . ';';
                        $db->query($request);
                        $request = 'SELECT * FROM initiateur WHERE ID=' . $_SESSION['id'] . ';';
                        $results = $db->query($request);
                        break;
                    case "investisseur" :
                        $request = 'UPDATE investisseur SET Nom=' . $name . ', Prenom=' . $surname . ', Mail=' . $mail . ' WHERE ID=' . $_SESSION['id'] . ';';
                        $db->query($request);
                        $request = 'SELECT * FROM initiateur WHERE ID=' . $_SESSION['id'] . ';';
                        $results = $db->query($request);
                        break;

                }
                if ($result = $results->fetch()) {
                    $_SESSION['name'] = $result['Nom'];
                    $_SESSION['surname'] = $result['Prenom'];
                    $_SESSION['mail'] = $result['Mail'];
                }
                echo '<div class="alert alert-success" role="alert">Vos informations ont bien été modifiées.</div>';

            } else {
                echo '<div class="alert alert-danger" role="alert">
             ' . $errorMessage . '
             </div>';
            }

        }
        ?>
        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" class="form-control" name="name" value="<?php echo $_SESSION['name']; ?>">
        </div>
        <div class="form-group">
            <label for="surname">Prénom :</label>
            <input type="text" class="form-control" name="surname" value="<?php echo $_SESSION['surname']; ?>">
        </div>
        <div class="form-group">
            <label for="mail">Adresse mail :</label>
            <input type="mail" class="form-control" placeholder="nom.prenom@mail.com" name="mail"
                   value="<?php echo $_SESSION['mail']; ?>">
        </div>
        <button type="submit" name="submitChange" class="btn btn-default">Modifier</button>
    </form>
    <hr/>

    <h2>Modifier votre mot de passe</h2>
    <?php
    include('db_inc.php');
    $db = new PDO($server . ':host=' . $host . ';dbname=' . $base, $user, $pass);

    if (isset($_POST['submitPassword'])) {
        if (empty($_POST['password'])) {
            $errorMessage .= $errorBegin . 'Vous devez renseigner votre nouveau mot de passe.' . $errorEnd;

        } elseif (empty($_POST['passwordConfirm'])) {
            $errorMessage .= $errorBegin . 'Vous devez confirmer votre nouveau mot de passe.' . $errorEnd;

        } elseif ($_POST['password'] != $_POST['passwordConfirm']) {
            $errorMessage .= $errorBegin . 'Votre nouveau mot de passe ne correspond pas à la confirmation.' . $errorEnd;

        } else {
            $password = $db->quote($_POST['password']);

        }

        if ($errorMessage == null) {
            $request = null;
            $accountType = $_SESSION['accountType'];

            switch ($accountType) {
                case "initiateur" :
                    $request = 'UPDATE initiateur SET Mdp=' . $password . ' WHERE ID=' . $_SESSION['id'] . ';';
                    break;
                case "investisseur" :
                    $request = 'UPDATE investisseur SET Mdp=' . $password . ' WHERE ID=' . $_SESSION['id'] . ';';
                    break;

            }
            $db->query($request);

            echo '<div class="alert alert-success" role="alert">Votre mot de passe à bien été modifié.</div>';

        } else {
            echo '<div class="alert alert-danger" role="alert">
             ' . $errorMessage . '
             </div>';
        }
    }
    ?>
    <form role="form" action="your_profile.php" method="post">
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="form-group">
            <label for="password">Confirmez votre mot de passe :</label>
            <input type="password" class="form-control" name="passwordConfirm">
        </div>
        <button type="submit" name="submitPassword" class="btn btn-default">Modifier</button>
    </form>
    <?php
    if ($_SESSION['accountType'] == 'investisseur') {

        ?>
        <hr/>

        <h2>Modifier vos coordonnées bancaires</h2>
        <?php
        if (isset($_POST['submitBank'])) {
            if (empty($_POST['bank'])) {
                $errorMessage .= $errorBegin . 'Vous devez renseigner vos coordonnées bancaires.' . $errorEnd;

            } else {
                $bank = $db->quote($_POST['bank']);
            }

            if ($errorMessage == null) {
                $request = 'UPDATE investisseur SET CoordonnéeBanque=' . $bank . ' WHERE ID=' . $_SESSION['id'] . ';';

                $db->query($request);

                $_SESSION['bank'] = $_POST['bank'];


                echo '<div class="alert alert-success" role="alert">Vots coordonnées bancaires ont bien été modifiées.</div>';

            } else {
                echo '<div class="alert alert-danger" role="alert">
             ' . $errorMessage . '
             </div>';
            }
        }
        ?>
        <form role="form" action="your_profile.php" method="post">
            <div class="form-group">
                <label for="bank">Coordonnées bancaires :</label>
                <input type="text" class="form-control" name="bank" value="<?php echo $_SESSION['bank']; ?>">
            </div>
            <button type="submit" name="submitBank" class="btn btn-default">Modifier</button>
        </form>
    <?php
    }
    ?>
</div>



<?php
include('footer.php');
?>
