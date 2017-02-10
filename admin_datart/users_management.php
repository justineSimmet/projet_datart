<?php

require_once('includes/include.php');
require_once('classes/user.php');

if (isset($_POST['targetUser'])) {
	$targetUser = new User($_POST['targetUser']);
};

$actionResultat = '';
//TEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEST
//INSERTIONS EN BASE DE DONNEE DU FORMULAIRE
if(isset($_POST['public_name'])){
	//Pour être plus précis qu'avec un simple isset $targetUser, je teste si l'id renvoyé par le formulaire n'est pas vide. Si c'est le cas, je fais un update.
	if(!empty($_POST['id'])){
		$targetUser = new User($_POST['id']);
		$targetUser->setPublicName($_POST['public_name']);
		$targetUser->setPublicSurname($_POST['public_surname']);
		$targetUser->setFunction($_POST['function']);
		$targetUser->setLogin($_POST['login']);
		$targetUser->setEmailAdress($_POST['email']);
		$targetUser->setStatus($_POST['status']);
		$updateUser = $targetUser->synchroDb('', '');
		var_dump($updateUser);
		
			if ($updateUser) {
				$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-edited">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Félicitation</strong> L\'utilisateur a bien été modifié.
				</div>';
			}
			else{
				$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur</strong> L\'utilisateur n\'a pas été modifié.
				</div>';
		    }
	}
	//Si l'id est vide, je mets en place un insert.
	else{
	$newUser = new User();
	$newUser->setPublicName($_POST['public_name']);
	$newUser->setPublicSurname($_POST['public_surname']);
	$newUser->setFunction($_POST['function']);
	$newUser->setLogin($_POST['login']);
	$newUser->setEmailAdress($_POST['email']);
	$newUser->setStatus($_POST['status']);
	$addUser = $newUser->synchroDb('','');
		if ($addUser) {
			$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-added">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Félicitation</strong> L\'utilisateur a bien été enregistré. Merci de patienter, un e-mail de confirmation est en cours d\'envoi.
			</div>';
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur</strong> L\'utilisateur n\'a pas été enregistré.
			</div>';
		};
	};
};


include('header.php');

?>

<div class="row" id="alert-area">
	<?= !empty($actionResultat)?$actionResultat:''; ?>
</div>

<div class="row">
	<section class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-9 ">
	<h2>Liste des utilisateurs</h2>
		<table>
			<thead>
				<tr>
					<th>Utilisateur</th>
					<th>Fonction</th>
					<th>Email</th>
					<th>Dernière connexion</th>
					<th>Actions</th>
				</tr>
			</thead>

			<tbody>
			<?php 
				$liste = User::listUsers(); 
				foreach ($liste as $l) {
			?>
				<tr>
					<td>
						<span class="<?= $l->getStatus() == 0?'fa fa-star':'fa fa-user'; ?>"></span> 
						<?= $l->getPublicName(); ?> <?= $l->getPublicSurname(); ?>
					</td>
					<td>
						<?= $l->getFunction(); ?>
					</td>
					<td>
						<?= $l->getEmailAdress(); ?>
					</td>
					<td>
						<?= !empty($l->getLastConnection())?getLastConnection():'Aucune connexion'; ?>
					</td>
					<td>
						<div class="form-group">
							<select class="form-control actionUser">
								<option></option>
								<option value="update" data-id="<?= $l->getId(); ?>">Modifier</option>
								<option value="delete" data-id="<?= $l->getId(); ?>">Supprimer</option>
							</select>
						</div>
					</td>
				</tr>
			<?php 
				};
			?>
			</tbody>

		</table>

	</section>

	<section class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-3 ">
	 <?php
		if(isset($targetUser)){
			$targetUser->form($_SERVER['PHP_SELF'], 'Modifier', 'edit');
		}
		else{
			$user = new User();
			$user->form($_SERVER['PHP_SELF'],'Créer', 'add');
		}
	?>

	</section>
<?php
 var_dump($newUser);
?>
</div>

<!-- Mettre en place var adminData quand la connexion sera ok -->
<script type="text/javascript"> var userData = <?php  if(isset($newUser)){echo $newUser->toJson() ;}else{echo "''";} ; ?></script>

<?php
include('footer.php');
