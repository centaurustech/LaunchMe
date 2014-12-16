<?php
$title = 'Inscription';
include('header.php');
?>

<div class="container">

    <h1>Inscription</h1>
    <?php
    $name;
    $surname;
    $mail;
    $password;
    $passwordConfirm;
    $errorMessage = null;
    $errorBegin = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ';
    $errorEnd = '</br>';

    if (isset($_POST['submit'])) {
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

        if (empty($_POST['password'])) {
            $errorMessage .= $errorBegin . 'Vous devez renseigner votre mot de passe.' . $errorEnd;

        } elseif (empty($_POST['passwordConfirm'])) {
            $errorMessage .= $errorBegin . 'Vous devez confirmer votre mot de passe.' . $errorEnd;


        } elseif ($_POST['password'] != $_POST['passwordConfirm']) {
            $errorMessage .= $errorBegin . 'Votre mot de passe ne correspond pas à la confirmation.' . $errorEnd;

        } else {
            $password = $db->quote(sha1($_POST['password']));

        }


        if ($errorMessage == null) {
            $request = null;
            $accountType = $_POST['accountType'];

            switch ($accountType) {
                case "initiateur" :
                    $request = 'INSERT INTO initiateur (Nom, Prenom, Mail, Mdp)  VALUES(' . $name . ', ' . $surname . ', ' . $mail . ', ' . $password . ');';
                    break;
                case "investisseur" :
                    $request = 'INSERT INTO investisseur (Nom, Prenom, Mail, Mdp) VALUES (' . $name . ', ' . $surname . ', ' . $mail . ', ' . $password . ');';
                    break;

            }
            $db->query($request);

            echo '<div class="alert alert-success" role="alert"> Votre compte a bien été crée !</br>Vous pouvez maintenant vous connecter avec vos identifiants.</div>';
            include('footer.php');
            exit;

        } else {
            echo '<div class="alert alert-danger" role="alert">
             ' . $errorMessage . '
             </div>';
        }
    }


    ?>
    <form role="form" action="register.php" method="post">
        <h2>Inscrivez-vous</h2>
        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="form-group">
            <label for="surname">Prénom :</label>
            <input type="text" class="form-control" name="surname">
        </div>
        <div class="form-group">
            <label for="accountType">Vous êtes un : </label>
            <select class="form-control" name="accountType">
                <option value="initiateur">Initiateur</option>
                <option value="investisseur">Investisseur</option>
            </select>
        </div>
        <div class="form-group">
            <label for="mail">Adresse mail :</label>
            <input type="mail" class="form-control" placeholder="nom.prenom@mail.com" name="mail">
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="form-group">
            <label for="password">Confirmez votre mot de passe :</label>
            <input type="password" class="form-control" name="passwordConfirm">
        </div>
        <button type="submit" name="submit" class="btn btn-default">Envoyer</button>
    </form>
</div>

<?php
include('footer.php');
?>