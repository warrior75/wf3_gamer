<?php
    session_start();
    require_once(__DIR__.'/config/db.php');
    $pagesGames= 0;
    print_r($_SESSION['gamers']);
    if (!isset($_SESSION['gamers'])) {
        header('Location: index.php');
        die();
    }
            if(!isset($_POST['action'])) {
                // Afficher le catalogue entier
                $query = $pdo->query('SELECT count(id) as total  FROM games');
                $nbGames = $query->fetch();
                $totalGames = $nbGames['total'];
                // 2. trouver la focntion qui arrondi une décimale à son entier supérieur et utiliser la fonction
                $limiteGames = 5;
                $pagesGames = ceil($totalGames / $limiteGames); // valeur 5
                // 6. récupérer la variable page envoyé en GET et l'affecter à $pageGames
                    if (isset($_GET['page'])&& ($_GET['page']>0) && ($_GET['page']<=$pagesGames)) {
                    $pageActive = $_GET['page'];
                    } else {
                        $pageActive = 1;
                    }
                    $debut = ($pageActive-1)*$limiteGames;
                    // 4. COnstruire la requête SQL pour récupérer les 5 premiers Games
                    /*echo '<pre>';
                    print_r($contenu_page);
                    echo '</pre>';*/
                    $query = $pdo->prepare('SELECT * FROM games LIMIT :limit  OFFSET :offset');
                    $query -> bindValue(':limit',$limiteGames,PDO::PARAM_INT);
                    $query -> bindValue(':offset',$debut,PDO::PARAM_INT);
                    $query -> execute();
                    $resultGame = $query->fetchAll();
            } else {

                $search = $_POST['search'];
                print_r($_POST);
                $plateform_id = $_POST['plateform'];
                if ($plateform_id != 0) {                         
                    $query = $pdo -> prepare('SELECT * FROM games WHERE title LIKE :title AND plateform_id =:plateform_id');
                    $query -> bindValue(':title','%'.$search.'%',PDO::PARAM_STR);
                    $query -> bindValue(':plateform_id',$plateform_id,PDO::PARAM_INT);
                    $query -> execute();
                    $resultGame = $query -> fetchAll();
                } else {
                    $query = $pdo -> prepare('SELECT * FROM games WHERE title LIKE :title');
                    $query -> bindValue(':title','%'.$search.'%',PDO::PARAM_STR);
                    $query -> execute();
                    $resultGame = $query -> fetchAll();
                }
            }
            
    
?>
<!doctype html>
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

              <h2 id="gameloc">Cataloc'</h2>

              

          </div>
      </div>
      <div class="container">
        <div class="row"> 
            <div class="col-md-3 jumbotron" id="recherche">
                <form method="POST" action="#"> 
                  <div class="form-group">
                    <label for="search">Rechercher :</label>
                    <input type="text" name="search" class="form-control" placeholder="search">
                </div>
                <div class="form-group">
                  <label for="plateform">Plateform :</label>
                  <select class="form-control" id="plateform" name="plateform">
                    <option selected value="0">TOUS</option>
                    <option value="1">PC</option>
                    <option value="2">XBOX ONE</option>
                    <option value="3">PS4</option>
                    <option value="4">Wii U</option>
                </select>
            </div>

            <div class="checkbox">
                <label>
                  <input type="checkbox" name="disponible"> Disponible Immédiatement
              </label>
          </div>
          <button type="submit" name="action" class="btn btn-primary">Rechercher</button>
      </form>

  </div>
  <div class="col-md-9" id="catalogue">
            <?php if (!empty($resultGame)): ?>
                
            
            <!-- 2. Dynamiser avec php -->
                <?php foreach($resultGame as $key => $game): ?>
                    <div class="fiche">
                    <img src="<?php echo $game['img']; ?>" height="170" width="120">
                    <h5><?php echo $game['title'] ?></h5>
                    <!-- <p><?php echo $game['description'] ?></p> -->
                    <a href="#"><button type="submit" name="action" class="btn btn-success">Louer</button></a>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-info">
                    <p>Aucun jeu ne correspond à votre recherche</p>
                </div>
            <?php endif; ?>
            <?php if ($pagesGames): ?>
                  
        <!-- pagination du bas de la page -->
                    <div>                    
                      <ul class="pagination">
                      <!-- 8. mettre la pagination suivant et prédedent -->
                            <li>
                                <a href="catalogue.php?page= <?php echo $pageActive-1; ?> " aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                      <!-- 3. Construire la pagination pour n nombres de pages $pagesGames -->
                        
                    
                            <?php for ($i=1; $i <= $pagesGames ; $i++): ?>
                           <li class="<?php if ($i == $pageActive ){echo 'active';}?>"><a href="catalogue.php?page=<?php echo $i; ?>"> <?php echo $i; ?></a></li>
                             <?php endfor; ?>
                            <li>
                                <!-- le lien pointe vers le numéro de la page courante +1 récupéré en GET -->
                                <a href="catalogue.php?page= <?php echo $pageActive+1; ?> " aria-label="Next"> 
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                      </ul>
                    </div>
            <?php endif ?>

       
       
        

        </div>
    </div>
        

</div>
<div>
    <footer>
        <p>&copy; Mohand, Nadia , Bilel, Cesario</p>
    </footer>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

<script src="js/vendor/bootstrap.min.js"></script>

<script src="js/main.js"></script>
</body>
</html>
