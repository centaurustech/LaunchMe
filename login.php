<?php
$title = 'Connexion';
include('header.php');
?>
<div class="container">
    <h1>
        Connexion
    </h1>
    <?php
    $mail;
    $password;
    $errorMessage = null;
    $errorBegin = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ';
    $errorEnd = '</br>';

    if (isset($_POST['submit'])) {
        include('db_inc.php');
        $db = new PDO("$server:host=$host;dbname=$base", $user, $pass);

        if (empty($_POST['mail'])) {
            $errorMessage .= $errorBegin . 'Vous devez renseigner votre adresse mail.' . $errorEnd;
        } else {
            $mail = $db->quote($_POST['mail']);
        }

        if (empty($_POST['password'])) {
            $errorMessage .= $errorBegin . 'Vous devez renseigner votre mot de passe.' . $errorEnd;
        } else {
            $password = $db->quote(sha1($_POST['password']));
            echo $password;

        }

        if ($errorMessage == null) {
            $request = null;
            $accountType = $_POST['accountType'];

            switch ($accountType) {
                case "initiateur" :
                    $request = 'SELECT * FROM initiateur WHERE Mail = ' . $mail . ' AND Mdp = ' . $password . ';';
                    break;
                case "investisseur" :
                    $request = 'SELECT * FROM investisseur WHERE Mail = ' . $mail . ' AND Mdp = ' . $password . ';';
                    break;

            }

            $results = $db->query($request);

            if ($result = $results->fetch()){
                $_SESSION['id'] = $result['ID'];
                $_SESSION['name'] = $result['Nom'];
                $_SESSION['surname'] = $result['Prenom'];
                $_SESSION['mail'] = $result['Mail'];
                $_SESSION['accountType'] = $accountType;
                $_SESSION['lastActivity'] = time();

                if($accountType == 'investisseur') {
                    $_SESSION['bank'] = $result['CoordonnéeBanque'];
                }

                header('location: index.php');
            } else {
                echo '<div class="alert alert-danger" role="alert">'.$errorBegin.'Vos identifiants ne correspondent pas. Veuillez réessayer.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">
             ' . $errorMessage . '
             </div>';
        }

    }
    ?>

    <form class="form" role="form" action="login.php" method="post">
        <h2 class="form-signin-heading">Connectez vous</h2>

        <div class="form-group">
            <label for="mail" class="sr-only">Adresse mail</label>

            <input type="email" id="inputEmail" class="form-control" placeholder="Adresse mail" name="mail">
        </div>

        <div class="form-group">
            <label for="inputPassword" class="sr-only">Mot de passe</label>

            <input type="password" id="inputPassword" class="form-control" placeholder="Mot de passe"
                   name="password">
        </div>

        <div class="form-group">
            <label for="accountType">Vous êtes un : </label>
            <select class="form-control" name="accountType">
                <option value="initiateur">Initiateur</option>
                <option value="investisseur">Investisseur</option>
            </select>
        </div>

        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Connexion</button>

    </form>


</div>
<!-- /.container -->

<?php
include('footer.php');
?>

</body>
</html>