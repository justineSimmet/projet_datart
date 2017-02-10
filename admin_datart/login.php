<?php

require_once('includes/config.php');
require_once('includes/functions.php');
require_once('classes/user.php');

?><!DOCTYPE html>
 <html lang="fr">
 <head>
 	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<title>Connexion à l'application DATART / Grand Angle</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
 	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
 	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css" />
 	<link rel="stylesheet" type="text/css" href="assets/css/style.css" />
 </head>
 <body>

</body>
</html>

<div class="container-fluid">

	<div class="row" id="login-container">
		
		<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12">

			<div class="row" id="login-img-container">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<img src="assets/images/datart-rvb.png" alt="Logotype de l'application DATART" class="img-responsive" />
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<img src="assets/images/grand-angle-rvb.png" alt="Logotype de la galerie Grand Angle" class="img-responsive" />
				</div>
			</div>

			<div class="row">
				<section class="col-lg-12 col-md-12 col-sm-12" id="login-form">
					<div class="alert-area"><!-- Zone de message d'alerte-->
						
					</div>

					<form action="login.php" method="POST" class="form-horizontal">
						<div class="form-group">
							<label for="login" class="control-label col-lg-2 col-md-2 col-sm-2">Login :</label>
							<div class="control-label col-lg-10 col-md-10 col-sm-10">
								<input type="text" class="form-control" id="login">
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="control-label col-lg-2 col-md-2 col-sm-2">Mot de passe :</label>
							<div class="control-label col-lg-10 col-md-10 col-sm-10">
								<input type="password" class="form-control" id="password">
							</div>
						</div>
						<button type="submit" class="btn btn-default pull-right">
							<span class="fa fa-arrow-circle-o-right"></span> Connexion
						</button>
					</form>

				</section>
			</div>
			
		</div>

	</div>
	
</div>

<script type="text/javascript" src="assets/js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>