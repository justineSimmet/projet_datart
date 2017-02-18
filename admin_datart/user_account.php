<?php

require_once('classes/user.php');
require_once('includes/include.php');

$locationTitle = 'Votre compte utilisateur';
$actionResultat = '';

// Si l'utilisateur a enregistré une nouvelle adresse email mais pas de nouveau mot de passe
// On vérifie que la nouvelle adresse e-mail soit différente de celle enregistrée avant un update.
if(isset($_POST['email']) && !isset($_POST['new-password'])){
	if ($_POST['email'] != $currentUser->getEmailAdress()) {
		$currentUser->setEmailAdress($_POST['email']);
		$update = $currentUser->synchroDb('');
		if ($update) {
			$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-password">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>'.$currentUser->getPublicName().' '.$currentUser->getPublicSurname().', vous venez de mettre à jour votre adresse email.</strong>
			</div>';
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> Votre compte\'a pas été mis à jour.
			</div>';
		}
	}
	else{
		$actionResultat = '<div class="alert alert-danger alert-dismissable">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Vous n\'avez effectué aucune modification.</strong> Votre compte\'a pas été mis à jour.
		</div>';
	}
}
// Si l'utilisateur a enregistré son mot de passe actuel et un nouveau
// On vérifie que la nouvelle adresse e-mail soit différente de celle enregistrée avant un update.
elseif (isset($_POST['old-password']) && isset($_POST['new-password'])) {
	$oldPassword = $_POST['old-password'];
	$newPassword = $_POST['new-password'];
	$currentUser->setEmailAdress($_POST['email']);
	$update = $currentUser->synchroDb($oldPassword, $newPassword);
	if ($update) {
		$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-password">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>'.$currentUser->getPublicName().' '.$currentUser->getPublicSurname().', vous venez de mettre à jour votre compte.</strong>
		</div>';
	}
	else{
		$actionResultat = '<div class="alert alert-danger alert-dismissable">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Erreur !</strong> Votre compte\'a pas été mis à jour.
		</div>';
	}
}

include('header.php');

?>

<div class="row" id="alert-area">
	<?= !empty($actionResultat)?$actionResultat:''; ?>
</div>

<div class="row">
	
	<section class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
		
	<?php
		$currentUser->form($_SERVER['PHP_SELF'], 'Modifier', 'Modifier votre compte :', 'update');
	?>

	</section>

</div>


<?php
include('footer.php');