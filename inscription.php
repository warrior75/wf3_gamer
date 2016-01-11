<?php

session_start();

require(__DIR__.'/config/db.php');

if(isset($_POST['action'])){


// Déclaration de nos variables 

$email = trim(htmlentities($_POST['email']));
$password = trim(htmlentities($_POST['password']));
$confirmPassword = trim(htmlentities($_POST['confirmPassword']));
$lastname = trim(htmlentities($_POST['lastname']));
$firstname = trim(htmlentities($_POST['firstname']));
$address = trim(htmlentities($_POST['address']));
$cp = trim(htmlentities($_POST['cp']));
$town = trim(htmlentities($_POST['town']));
$tel = trim(htmlentities($_POST['tel']));
$address = trim(htmlentities($_POST['address']));



// Initalisation d'un tableau d'erreurs
$errors = [];

  // Check le champs email
  if(empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false){
    $errors['email'] = 'L\'adresse n\'est pas au bon format';
  }
  else {
      // Je vérifie que l'email existe pas déjà dans ma bdd
      $query = $pdo->prepare('SELECT email FROM gamers WHERE email = :email');
      $query->bindValue(':email', $email, PDO::PARAM_STR);
      $query->execute();
      // Je récupère le résultat sql
      $resultEmail = $query->fetch();

      if($resultEmail['email']) {
        $errors['email'] = 'L\'email existe déjà';
      } 


  }

    if($password != $confirmPassword) {
      $errors['password'] = "Les mots de passe ne sont pas les mêmes";
    }
    elseif(strlen($password) < 6) {
      $errors['password'] = "Mot de passe trop court.";
    } else {
      // Le password contient au moins une lettre ?
      $containsLetter = preg_match('/[a-zA-Z]/', $password);
      // Le password contient au moins un chiffre ?
      $containsDigit  = preg_match('/\d/', $password);
      // Le password contient au moins un autre caractère ?
      $containsSpecial= preg_match('/[^a-zA-Z\d]/', $password);

        // Si une des conditions n'est pas remplie ... erreur
        if(!$containsLetter || !$containsDigit || !$containsSpecial) {
          $errors['password'] = "Choisissez un meilleur mot de passe avec au moins un 6 caractères avec une lettre, un chiffre et caractère spécial.";
        }
    }

      // Vérifie si le nom ne contient que des lettres
      if(!preg_match('/[a-zA-Z]*/',$lastname)){
        $errors['lastname'] = "Le nom ne doit contenir que des lettres ";
      }

      // Vérifie si le nom ne contient que des lettres
      if(!preg_match('/[a-zA-Z]*/',$firstname)){
        $errors['firstname'] = "Le prénom ne doit contenir que des lettres ";
      }

      // Vérification du code postal
      if(!ctype_digit($cp) || strlen($cp) != 5 ){
        $errors['cp'] = "Le code postal doit contenir 5 chiffres";
      }

      // Vérification qu'il vient de paris
      if(strtolower($town) != "paris" ){
        $errors['town'] ="Vous devez habiter Paris"; 
      }

      if(!preg_match('/^0[0-9]{9}/',$tel)){
        $errors['tel'] = "Le numéro de téléphone n'est pas correct";
      }


      // S'il n'y a pas d'erreurs, on enregistre l'utilisateur dans la base de données
      if( empty($errors)){

           $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
           $query = $pdo->prepare('INSERT INTO gamers(email,password,firstname,lastname,adresse,zipcode,town,phone,created_at,role) VALUES(:email,:password,:firstname,:lastname,:adresse,:zipcode,:town,:phone,NOW(),"member")');
           $query->bindValue(':email',$email,PDO::PARAM_STR);
           $query->bindValue(':password',$hashedPassword,PDO::PARAM_STR);
           $query->bindValue(':firstname',$firstname,PDO::PARAM_STR);
           $query->bindValue(':lastname',$lastname,PDO::PARAM_STR);
           $query->bindValue(':adresse',$address,PDO::PARAM_STR);
           $query->bindValue(':zipcode',$cp,PDO::PARAM_INT);
           $query->bindValue(':town',$town,PDO::PARAM_STR);
           $query->bindValue(':phone',$tel,PDO::PARAM_INT);
           $query->execute();

           // Geocode de l'adresse
          

              // L'utilisateur a t-il été bien inséré en bdd ?
              if($query->rowCount() > 0) {
                // Récupération de l'utilisateur depuis la bdd 
                // pour l'affecter à une variable de session
                $query = $pdo->prepare('SELECT * FROM gamers WHERE id = :id');
                $query->bindValue(':id', $pdo->lastInsertId(), PDO::PARAM_INT);
                $query->execute();
                $resultUser = $query->fetch();

                // On stocke le user en session et on retire le password avant 
                unset($resultUser['password']);
                $_SESSION['user'] = $resultUser;

                // On redirige l'utilisateur vers la page protégé profile.php
                header("Location: catalogue.php");
                die();
              }
   
      }


print_r($errors);
}
    



?>





<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
               
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->


    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container" id="header">

        <div id="imgLogo">
          <img src="img/logo.png">  
        </div>

        <h1 id="gameloc">Gameloc</h1>
        <p>Inscription</p>

      </div>
    </div>
    <div class="formInscription col-md-4 col-md-offset-4">
       
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
              <div class="form-group <?php if(isset($errors['email'])) { echo "has-error" ;}?>">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
                  <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['email'])) : ?>
                      <?php echo $errors['email'];?>
                    <?php endif; ?>
                  </span>     
              </div>

              <div class="form-group <?php if(isset($errors['password'])) { echo "has-error" ;}?>">

                <label for="password">Mot de Passe</label>

                <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                  <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['password'])) : ?>
                      <?php echo $errors['password'];?>
                    <?php endif; ?>
                  </span>
              </div>

              <div class="form-group">
                <label for="confirmPassword">Confirmer mot de passe</label>
                <input type="password" class="form-control" id="confirmPassword" placeholder="confirm Password" name="confirmPassword" required>

              </div>
              <div class="form-group <?php if(isset($errors['lastname'])) { echo "has-error" ;}?>">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control" id="lastname" placeholder="lastname" name="lastname" required>
                  <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['lastname'])) : ?>
                      <?php echo $errors['lastname'];?>
                    <?php endif; ?>
                  </span>
              </div>
              <div class="form-group <?php if(isset($errors['firstname'])) { echo "has-error" ;}?> ">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control" id="firstname" placeholder="firstname" name="firstname" required>
                 <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['firstname'])) : ?>
                      <?php echo $errors['firstname'];?>
                    <?php endif; ?>
                  </span>
              </div>
              <div class="form-group <?php if(isset($errors['address'])) { echo "has-error" ;}?>">
                <label for="address">Adresse</label>
                <input type="text" class="form-control" id="address" placeholder="address" name="address" required>
                 <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['address'])) : ?>
                      <?php echo $errors['address'];?>
                    <?php endif; ?>
                  </span>
              </div>
              <div class="form-group <?php if(isset($errors['cp'])) { echo "has-error" ;}?>">
                <label for="codePostal">Code Postal</label>
                <input type="text" class="form-control" id="codePostal" placeholder="codePostal" name="cp" required>
                 <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['cp'])) : ?>
                      <?php echo $errors['cp'];?>
                    <?php endif; ?>
                  </span>
              </div>
              <div class="form-group <?php if(isset($errors['town'])) { echo "has-error" ;}?>">
                <label for="town">Ville</label>
                <input type="text" class="form-control" id="town" placeholder="Ville" name="town" required>
                 <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['town'])) : ?>
                      <?php echo $errors['town'];?>
                    <?php endif; ?>
                  </span>
              </div>
              <div class="form-group <?php if(isset($errors['tel'])) { echo "has-error" ;}?>">
                <label for="tel">Téléphone</label>
                <input type="tel" class="form-control" id="tel" placeholder="telephone" name="tel" required>
                 <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['tel'])) : ?>
                      <?php echo $errors['tel'];?>
                    <?php endif; ?>
                  </span>
              </div>
              
              <button type="submit" name="action" class="btn btn-primary">Valider</button>
            </form>
            
            <footer>
                <p>&copy; Mohand, Nadia , Bilel</p>
            </footer>
        </div>
    
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>
    </body>

