<?php

    session_start();
    require_once(__DIR__.'/config/db.php');
    require(__DIR__.'/functions.php');
    checkLoggedIn();

    if (isset($_POST['action'])) {
       
      // Déclaration de nos variables 
      $platform_id = trim(htmlentities($_POST['platform_id']));
      $title = trim(htmlentities($_POST['title']));
      $img_url = trim(htmlentities($_POST['img_url']));
      $published_time = trim(htmlentities($_POST['published_at']));
      $description = trim(htmlentities($_POST['description']));
      $game_time = trim(htmlentities($_POST['game_time']));

      // J'initialise le tableau d'erreurs

      $errors = [];

      // Transform string to object Datetime
        $published_at = DateTime::createFromFormat('d/m/Y', $published_time);

      // Check le champs platform_id
        if(empty($platform_id)){
          $errors['platform_id'] = 'La plateforme est invalide';
        }

      // Check le champs title
        if(empty($title) || !preg_match('/[a-zA-Z]*/',$title) || (strlen($title) > 55)) {
          $errors['title'] = 'Le titre est invalide';
        }

         // Check le champs img_url
 /*       if(empty($img_url) || !preg_match(preg_match('/^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?/i', $img_url))) {
          $errors['img_url'] = 'Le lien de l\'image est invalide';
        }*/

         // Check le champs published_at
        $dateDujour = date('Y-m-d H:i:s');
        
        

        if(empty($published_at) || ($published_at->format('Y-m-d H:i:s') > $dateDujour) ){
          $errors['published_at'] = 'La date de sortie est invalide';
        }

         // Check le champs description
        if(empty($description)){
          $errors['description'] = 'La description est invalide';
        }
         // Check le champs game_time
        if(empty($game_time) || is_int($game_time)){
          $errors['game_time'] = 'La durée de jeu est invalide';
        }

        if( empty($errors)){

           $query = $pdo->prepare('INSERT INTO games(title,url_img,description,published_at,game_time,is_available,created_at,updated_at,platform_id,owner_user_id) VALUES(:title,:url_img,:description,:published_at,:game_time,:is_available,NOW(),:platform_id,:owner_user_id)');
           $query->bindValue(':title',$title,PDO::PARAM_STR);
           $query->bindValue(':url_img',$url_img,PDO::PARAM_STR);
           $query->bindValue(':description',$description,PDO::PARAM_STR);
           $query->bindValue(':published_at',$published_at,PDO::PARAM_STR);
           $query->bindValue(':game_time',$game_time,PDO::PARAM_INT);
           $query->bindValue(':is_available',$is_available,PDO::PARAM_STR);
           $query->bindValue(':platform_id',$platform_id,PDO::PARAM_INT);
           $query->bindValue(':owner_user_id',$owner_user_id,PDO::PARAM_INT);

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
        <nav class="navbar navbar-inversed">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.php">GAMELOC</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                <li class="active"><a href="inscription.php">Inscription</a></li>
                <li><a href="connexion.php">Connexion</a></li>
              </ul>
              <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-primary">Chercher</button>
              </form>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Louer</a></li>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>

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
              <div class="form-group">
                  <label for="platform_id">Plateform :</label>
                  <select class="form-control" id="platform_id" name="platform_id">
                    <option selected value="0">TOUS</option>
                    <option value="1">PC</option>
                    <option value="2">XBOX ONE</option>
                    <option value="3">PS4</option>
                    <option value="4">Wii U</option>
                </select>
            </div>
              <div class="form-group <?php if(isset($errors['title'])) { echo "has-error" ;}?>">
                <label for="title">Titre</label>
                <input type="text" class="form-control" id="title" placeholder="title" name="title" required>
                  <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['title'])) : ?>
                      <?php echo $errors['title'];?>
                    <?php endif; ?>
                  </span>     
              </div>

              <div class="form-group <?php if(isset($errors['img_url'])) { echo "has-error" ;}?>">

                <label for="img_url">Image Url</label>

                <input type="text" class="form-control" id="img_url" placeholder="http://www.jeux.com/jeux.png" name="img_url" required>
                  <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['img_url'])) : ?>
                      <?php echo $errors['img_url'];?>
                    <?php endif; ?>
                  </span>
              </div>

              <div class="form-group <?php if(isset($errors['published_at'])) { echo "has-error" ;}?>">
                <div class="form-group">
                <label for="published_at">Date de sortie</label>
                <input type="text" class="form-control" id="published_at" placeholder="jj/mm/YYYY" name="published_at" required>
                <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['published_at'])) : ?>
                      <?php echo $errors['published_at'];?>
                    <?php endif; ?>
                  </span>
              </div>  

              </div>
              <div class="form-group <?php if(isset($errors['description'])) { echo "has-error" ;}?>">
                <label for="lastname">Description</label>
                <textarea id="textarea" class="form-control" id="description" name="description"></textarea>
                  <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['lastname'])) : ?>
                      <?php echo $errors['lastname'];?>
                    <?php endif; ?>
                  </span>
              </div>
              <div class="form-group <?php if(isset($errors['game_time'])) { echo "has-error" ;}?> ">
                <label for="game_time">Temps de jeux</label>
                <input type="text" class="form-control" id="game_time" placeholder="ex: 50 (heures)" name="game_time" required>
                 <span id="helpBlock2" class="help-block">
                    <?php if(isset($errors['firstname'])) : ?>
                      <?php echo $errors['firstname'];?>
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

