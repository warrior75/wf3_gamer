<?php 
session_start(); 
require(__DIR__.'/config/db.php'); 

// Vérifier que le button submit a été cliqué

if (isset($_POST['action'])) {
	$email = trim(htmlentities($_POST['email']));
	$password = trim(htmlentities($_POST['password']));

	// Initialisation d'un tableau d'erreurs
	$errors = array();

	// 1. récupération de l'utilisateur dans la bdd grâce à son email

	$query = $pdo -> prepare('SELECT email,password FROM gamers WHERE email = :email');
	$query -> bindValue('email',$email,PDO::PARAM_STR);
	$query -> execute();
	$userInfos = $query -> fetch();

	if ($userInfos){
		
		//password_verify est compatible >= PHP 5.5
		if (password_verify($password,$userInfos['password'])) {
			
			//On stocke le user en session mais on retire le password avant
			unset($userInfos['password']);
			$_SESSION['gamers']=$userInfos;
			header('Location: catalogue.php');
			die();
		}
		else{
			$errors['password']="Mot de passe invalide";
		}
	} else {
		$errors['email']="Aucun utilisateur avec cet adresse mail";
	}
	
	$_SESSION['loginErrors'] = $errors;
	header("Location:connexion.php");
	die();

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

        <h1 id="gameloc">Gameloc</h1>
        <p>Connexion</p>

      </div>
    </div>
    <div class="formConnexion col-md-4 col-md-offset-4">
    		

    	

    	

	    	<form method="POST" action="#">
			  <div class="form-group">
			    <label for="exampleInputEmail1">Email</label>
			    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
			  </div>
			  <?php if(isset($_SESSION['loginErrors'])): ?>
				  <?php if(isset($_SESSION['loginErrors']['email'])) : ?> 
	    			<span id="helpBlock2" class="help-block"> 
	    				<?php echo ($_SESSION['loginErrors']['email']);?> 
	    				<?php unset($_SESSION['loginErrors']['email']); ?> 
	    			</span>
		    		<?php endif;?>
		    		<?php unset($_SESSION['loginErrors']);?>
    		<?php endif; ?>
			  <div class="form-group">
			    <label for="exampleInputPassword1">Password</label>
			    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
			  </div>


			<?php if(isset($_SESSION['loginErrors'])): ?>
    		<?php if(isset($_SESSION['loginErrors']['password'])) : ?> 
    			<span id="helpBlock2" class="help-block">
    				<?php echo ($_SESSION['loginErrors']['password']);?> 
    				<?php unset($_SESSION['loginErrors']['password']); ?> 
    			</span>
    		<?php endif;?>
			  	<!-- il faut supprimer les erreurs une fois affichées sinon elles vont rester -->
    		<?php		unset($_SESSION['loginErrors']);?>
    		<?php endif; ?>
			  <button type="submit" name="action" class="btn btn-primary">Valider</button>
               <div class="form-group">
                    <p class="help-block"><a href="forgotPassword.php">Mot de passe oublié?</a></p>
                </div>
			</form>
			
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
