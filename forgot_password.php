
<?php
session_start();

require(__DIR__.'/config/db.php');
	// Permet d'inclure la librairie phpmailer grâce à composer
	// require(__DIR__.'/vendor/autoload.php');

	// 1. Vérifier que le form a bien été soumis
if(isset($_POST['action'])) {
		//2. Affecter une variable à l'email récupéré, (faire trim et htmlentities)
	$email = trim(htmlentities($_POST['email']));

		//3. Initialisation d'un tableau d'erreurs $errors
	$errors = [];
		// Tableau de messages notifications
	$notifications = [];

		//4. Check du champs email (pas vide, format email et inférieur à 60 caractères)
	if(empty($email) || (filter_var($email, FILTER_VALIDATE_EMAIL)) === false) {
		$errors['email'] = "Email incorrect";
	}
	elseif (strlen($email) > 60) {
		$errors['email'] = "Email trop long";
	}

		// S'il n'y a pas d'erreurs sur l'email
	if(empty($errors)) {
			// 5. Récupération de l'utilisateur dans la bdd
		$query = $pdo->prepare('SELECT * FROM gamers WHERE email = :email');
		$query->bindValue(':email', $email, PDO::PARAM_STR);
		$query->execute();
		$resultUser = $query->fetch();

		if($resultUser) {
				// 6. Génération du Token
			$token = md5(uniqid(mt_rand(), true));

				// 7. Date d'expiration du Token
			$expireToken = date("Y-m-d H:i:s", strtotime('+ 1 day'));

				// 8. Updater le user dans la bdd grâce à ces nouvelles informations
			$query = $pdo->prepare('UPDATE gamers 
				SET token = :token, expire_token = :expire_token, updated_at = NOW() 
				WHERE id = :id');
			$query->bindValue(':token', $token, PDO::PARAM_STR);
			$query->bindValue(':expire_token', $expireToken, PDO::PARAM_STR);
			$query->bindValue(':id', $resultUser['id'], PDO::PARAM_INT);
			$query->execute();

				// Equivalent à http://localhost/php/38/wf3_gamer/resetPassword.php?token=*****&email=*******
			$resetLink = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']).'/resetPassword.php?token='.$token.'&email='.$email;
				//mail('edwin.polycarpe@gmail.com', 'Forgot Password', $resetLink);

				// Instance de phpmailer
			$mail = new PHPMailer;

				// Paramètre envoi e-mail
			$mail->setFrom('meslem.bellal@gmail.com', 'Admin');
				$mail->addAddress($email); //  

				// Format HTML
				$mail->isHTML(true);

				// Sujet de l'email
				$mail->Subject = 'Mot de passe oublié ?';

				// Message de l'email
				$mail->Body    = '<p>Vous avez oublié votre mot de passe ? <br />
				<a href="'.$resetLink.'">Cliquez ici pour créer un nouveau mot de passe</a>
			</p>';

				// Envoi de l'email
			if($mail->send()) {
					// Echo de resetLink car l'envoie de mail ne fonctionne pas :(
				$notifications['email'] = "Un email vous a été envoyé vérifiez votre boite mail ! $resetLink";
			}
			else {
				$errors['email'] = "L'email n'a pas pu être envoyé. $resetLink";
			}


		}
		else {
			$errors['user'] = "l'utilisateur n'existe pas";	
		}
	}

}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Forgot password?</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="css/main.css">

	<script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>
<body>




<div class="jumbotron">
				<div class="container" id="header">

					<div id="imgLogo" >
						<img src="img/logo.png">  
					</div>

					<h1 id="gameloc">Mot de passe oublié?</h1>
					

				</div>
			</div>



	<div class="container-fluid">
		<div class="row">



			<?php if(!empty($errors)): ?>
				<div class="alert alert-danger">
					<?php foreach ($errors as $keyError => $error) : ?>
						<p><?php echo $error; ?></p>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if(!empty($notifications)): ?>
				<div class="alert alert-danger">
					<?php foreach ($notifications as $keyNotif => $notif) : ?>
						<p><?php echo $notif; ?></p>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="formConnexion col-md-4 col-md-offset-4">

				<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<div class="form-group">
						<label for="email">Email</label>
						<input type="text" class="form-control" id="email" name="email" placeholder="Email">
					</div>

					<button type="submit" name="action" class="btn btn-primary">Récuperez votre mot de passe</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>