<?php
    session_start();
    require_once(__DIR__.'/config/db.php');
    require(__DIR__.'/functions.php');
   
   $nbGamesRent="";

    /*1. Faire une requête pour récupérer le nombre total de jeux */
    $query=$pdo->prepare('SELECT COUNT(*) AS total FROM games');

    $query->execute();
    $countGames=$query->fetch();
    $totalGames=$countGames['total'];
    /*print_r($totalGames);*/

    /*2. Détermination du nombre de pages en fonction des informations et de la page active*/
    $limitGames = 5;
    $nbPagesGames = ceil($totalGames/$limitGames); // valeur qui change en fonction du nombre de jeux inscrits par les joueurs

    // Nous récupérons la page active en GET


    if(isset($_GET['page']) && !empty($_GET['page'])) {
        $pageActiveGamer = $_GET['page'];
      }
      else {
        $pageActiveGamer = 1;
      }

    /*4. Définir la requête permettant de récupérer les 5 premiers résultat et définir la variable de limit offset*/

    $offsetGames = ($pageActiveGamer - 1) * $limitGames;


/*    $query=$pdo->prepare('SELECT * FROM games LIMIT :limit OFFSET :offset');
    $query->bindValue(':limit',$limitGames, PDO::PARAM_INT);
    $query->bindValue(':offset',$offsetGames,PDO::PARAM_INT);
    $query->execute();

    $resultGame2 = $query->fetchAll();
*/
 
    checkLoggedIn();

            if(isset($_POST['action'])) {
                // Afficher le catalogue entier
               
                $search = $_POST['search'];
                $platform_id = $_POST['plateform'];
                if ($platform_id != 0) {                         
                     $query = $pdo -> prepare('SELECT games.*,plateforme.name as plateforme_name FROM games 
                                              INNER JOIN plateforme ON platform_id = plateforme.id 
                                              WHERE title LIKE :title AND platform_id = :platform_id LIMIT :limit OFFSET :offset');

                    $query -> bindValue(':title','%'.$search.'%',PDO::PARAM_STR);
                    $query -> bindValue(':platform_id',$platform_id,PDO::PARAM_STR);
                    $query->bindValue(':limit',$limitGames, PDO::PARAM_INT);
                    $query->bindValue(':offset',$offsetGames,PDO::PARAM_INT);
                    $query -> execute();
                    $resultGame = $query -> fetchAll();

                } else {
                    $query = $pdo -> prepare('SELECT games.*,plateforme.name as plateforme_name FROM games 
                                              INNER JOIN plateforme ON platform_id = plateforme.id 
                                              WHERE title LIKE :title LIMIT :limit OFFSET :offset');
                    $query -> bindValue(':title','%'.$search.'%',PDO::PARAM_STR);
                    $query->bindValue(':limit',$limitGames, PDO::PARAM_INT);
                    $query->bindValue(':offset',$offsetGames,PDO::PARAM_INT);
                    $query -> execute();
                    $resultGame = $query -> fetchAll();
                }
            } else {
                     $query = $pdo -> prepare('SELECT games.*,plateforme.name as plateforme_name FROM games 
                                              INNER JOIN plateforme ON platform_id = plateforme.id LIMIT :limit OFFSET :offset');
                    $query->bindValue(':limit',$limitGames, PDO::PARAM_INT);
                    $query->bindValue(':offset',$offsetGames,PDO::PARAM_INT);
                    $query -> execute();
                    $resultGame = $query -> fetchAll();
            }
    
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js" lang=""> <!--<![endif]-->
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
                <li><a href="add_games.php">Ajoutez un jeu !</a></li>
                <li><a href="admin.php"><?php if($_SESSION['gamers']['role'] == 'admin') echo "Admin";?></a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><?php echo "Bonjour ".$_SESSION['gamers']['firstname']." !"; ?></a></li>
                <li><a href="panier.php" >Panier <i class="glyphicon glyphicon-shopping-cart" ></i> <?php echo $nbGamesRent; ?> </a></li>           
                <li><a href="logout.php">Déconnexion</a></li>
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

              <h2 id="gameloc">Cataloc'</h2>

              

          </div>
      </div>
      <div class="container">
        <div class="row"> 
            <div class="col-md-3 jumbotron" id="recherche">
                <form method="POST" action="#"> 
                  <div class="form-group">
                    <label for="search">Rechercher :</label>
                    <input type="text" name="search" class="form-control" placeholder="search" value="<?php if(isset($_POST['search'])) echo $_POST['search']; ?>">
                </div>
                <div class="form-group">
                  <label for="plateform">Plateform :</label>         

                  <select class="form-control" id="plateform" name="plateform" value="">
                    <option selected value="0">TOUS</option>
                    <option value="1">PC</option>
                    <option value="2">XBOX ONE</option>
                    <option value="3">PS4</option>
                    <option value="4">Wii U</option>
                </select>
            </div>

            <div class="checkbox">
                <label>
                  <input type="checkbox" name="disponible" <?php if(isset($_POST['disponible'])) echo "checked"; ?>> Disponible Immédiatement
              </label>
          </div>
          <button type="submit" name="action" class="btn btn-primary">Rechercher</button>
      </form>

  </div>
         <div class="col-md-9" id="catalogue">
            <?php if (isset($_POST['action'])): ?>
                
          <!-- Afficher le nombre de résultat trouvés, sinon en bas, un message d'alerte s'affiche -->        
            
            <?php 
            $nbResultGames= count($resultGame);
            if ($nbResultGames > 1) {
              echo '<strong>'.$nbResultGames.' '.'resultats trouvés!'.'<strong>'.'<br>';
            }
            elseif ($nbResultGames = 1) {
              echo '<strong>'.$nbResultGames.' '.'resultat trouvé!'.'<strong>'.'<br>';
            }
?>

            <!-- 5. Afficher le résultat de la recherche par page-->

                <?php foreach($resultGame as $key => $game): ?>
                <div class="fiche">
                    <img src="<?php echo $game['url_img']; ?>" height="170" width="120">
                    <h5>Titre :<?php echo $game['title'] ?></h5>
                    <h5>Plateforme :<?php echo $game['plateforme_name'] ?></h5>
                    <!-- <p><?php echo $game['description'] ?></p> -->

            <!-- 6.Désactiver le bouter "Louer" quand le jeu est indisponible, soit en "false" -->

                    <?php if ($game['is_available']): ?>
                        <a href='panier.php?game_id=<?php echo $game['id'];?>&titre=<?php echo urlencode($game['title']);?>&qteJeu=1'><button type="submit" name="action" class="btn btn-success">Louer</button></a>
                    <?php else: ?>
                        <a><button type="submit" name="action" class="btn btn-danger" disabled >Indisponible</button></a>
                    <?php endif; ?>
                
                </div>
                <?php endforeach; ?>
            <?php else : ?>
              <?php if(empty($resultGame)):?>
                <div class="alert alert-info">
                    <p>Aucun jeu ne correspond à votre recherche</p>
                </div>
              <?php endif;?>
            <?php endif; ?>

            

                  
           <!-- 7. Pagination du bas de la page -->
                    <div>                    
                      <ul class="pagination">
                      <!-- 8. mettre la pagination suivant et prédedent -->
                            <?php if($pageActiveGamer > 1): ?>
                            <li>
                                <a href="catalogue.php?page= <?php echo $pageActiveGamer-1; ?> " aria-label="Previous">
                                    <span aria-hidden="true">Précédent</span>
                                </a>
                            </li>
                          <?php endif; ?>

                      <!-- 3. Construire la pagination pour n nombres de pages $pagesGames -->

                        
                    
                            <?php for ($i=1; $i <= $nbPagesGames ; $i++): ?>
                           <li class="<?php if ($i == $pageActiveGamer ){echo 'active';}?>"><a href="catalogue.php?page=<?php echo $i; ?>"> <?php echo $i; ?></a></li>

                             <?php endfor; ?>

                            <?php if($pageActiveGamer < $nbPagesGames):?>
                            <li>
                                <!-- le lien pointe vers le numéro de la page courante +1 récupéré en GET -->
                                <a href="catalogue.php?page= <?php echo $pageActiveGamer+1; ?> " aria-label="Next"> 
                                    <span aria-hidden="true">Suivant</span>
                                </a>
                            </li>
                          <?php endif; ?>
                      </ul>
                    </div>
            
 

       
        <div>
          <footer>
              <p>&copy; Mohand, Nadia , Bilel, Cesario</p>
          </footer>
        </div>
        

        </div>
    </div>
        

</div>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

<script src="js/vendor/bootstrap.min.js"></script>

<script src="js/main.js"></script>
</body>
</html>
