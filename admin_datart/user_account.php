<?php

require_once('classes/user.php');
require_once('includes/include.php');

$locationTitle = 'Votre compte utilisateur';
$actionResultat = '';


if(isset($_POST['email'])){
	if ($_POST['email'] != $currentUser->getEmailAdress()) {
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE){
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<p><strong>Erreur !</strong> Vous devez entrer une adresse e-mail valide.</p>
				</div>';
		}
		elseif( empty(trim($_POST['email'])) ){
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<p><strong>Erreur !</strong> Vous devez entrer une adresse e-mail valide.</p>
				</div>';
		}
		else{
			if(!empty(trim($_POST['old-password'])) ){
				if( !empty(trim($_POST['new-password'])) && strlen($_POST['new-password']) >= 8 ){
						$oldPassword = $_POST['old-password'];
						$newPassword = $_POST['new-password'];
						$currentUser->setEmailAdress($_POST['email']);
						$update = $currentUser->synchroDb($oldPassword, $newPassword);
						if ($update) {
							$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-password">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<p><strong>'.$currentUser->getPublicName().' '.$currentUser->getPublicSurname().', vous venez de mettre à jour votre compte.</strong></p>
							</div>';
						}
						else{
							$actionResultat = '<div class="alert alert-danger alert-dismissable">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<p><strong>Erreur !</strong> Votre compte\'a pas été mis à jour. Assurez-vous que votre ancien mot de passe est correct. Si l\'erreur persiste, contactez un administrateur.</p>
							</div>'; 
						}
				}
				else{
					$actionResultat = '<div class="alert alert-danger alert-dismissable">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<p><strong>Erreur !</strong> Votre nouveau mot de passe ne peut pas être vide et doit comporter au moins 8 caractères.</p>
						</div>'; 
				}
			}
			else{
				$currentUser->setEmailAdress($_POST['email']);
				$update = $currentUser->synchroDb('','');
				if ($update) {
					$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-password">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<p><strong>'.$currentUser->getPublicName().' '.$currentUser->getPublicSurname().', vous venez de mettre à jour votre adresse email.</strong></p>
					</div>';
				}
				else{
					$actionResultat = '<div class="alert alert-danger alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<p><strong>Erreur !</strong> Votre adresse email n\'a pas été mise à jour suite à un problème.</p>
					</div>';
				}
			}
		}
	}
	else{
		if(!empty(trim($_POST['old-password'])) ){
			if( !empty(trim($_POST['new-password'])) && strlen($_POST['new-password']) >= 8 ){
					$oldPassword = $_POST['old-password'];
					$newPassword = $_POST['new-password'];
					$currentUser->setEmailAdress($_POST['email']);
					$update = $currentUser->synchroDb($oldPassword, $newPassword);
					if ($update) {
						$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-password">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<p><strong>'.$currentUser->getPublicName().' '.$currentUser->getPublicSurname().', vous venez de mettre à jour votre mot de passe.</strong></p>
						</div>';
					}
					else{
						$actionResultat = '<div class="alert alert-danger alert-dismissable">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<p><strong>Erreur !</strong> Votre compte\'a pas été mis à jour. Assurez-vous que votre ancien mot de passe est correct. Si l\'erreur persiste, contactez un administrateur.</p>
						</div>'; 
					}
			}
			else{
				$actionResultat = '<div class="alert alert-danger alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<p><strong>Erreur !</strong> Votre nouveau mot de passe ne peut pas être vide et doit comporter au moins 8 caractères.</p>
					</div>'; 
			}
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<p><strong>Erreur !</strong> Vous n\'avez modifié aucune donnée de votre compte.</p>
				</div>'; 
		}
	}
}


include('header.php');

?>

<div class="row" id="alert-area">
	<div class="col-sm-12 text-center">
		<?= !empty($actionResultat)?$actionResultat:''; ?>
	</div>
</div>

<div class="row">
	
	<section class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
		
	<?php
		$currentUser->form(URL_ADMIN.'user_account.php', 'Modifier', 'Modifier votre compte :', 'update');
	?>

	</section>

</div>


<?php
include('footer.php');