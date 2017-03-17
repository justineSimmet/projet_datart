<?php

require_once('classes/user.php');
require_once('includes/include.php');


$locationTitle = 'Gestion des utilisateurs';


if (isset($_POST['targetUser'])) {
	$targetUser = new User($_POST['targetUser']);
};

//INSERTIONS EN BASE DE DONNEE DU FORMULAIRE
if(isset($_POST['public_name'])){
	//VERIFICATION PHP DES DONNEES ENVOYEES
	if( empty(trim($_POST['public_name'])) || empty(trim($_POST['public_surname'])) || empty(trim($_POST['function'])) || empty(trim($_POST['email'])) ){
		$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<p><strong>Erreur !</strong> Il est nécéssaire de remplir tout les champs du formulaire.<p>
				</div>';
	}
	else{
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE) {
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<p><strong>Erreur !</strong> Vous devez saisir une adresse mail valide.<p>
				</div>';
		}
		else{
			if(!empty($_POST['id'])){
				$targetUser = new User($_POST['id']);
				$targetUser->setPublicName($_POST['public_name']);
				$targetUser->setPublicSurname($_POST['public_surname']);
				$targetUser->setFunction($_POST['function']);
				$targetUser->setLogin($_POST['login']);
				$targetUser->setEmailAdress($_POST['email']);
				$targetUser->setStatus($_POST['status']);
				$updateUser = $targetUser->synchroDb('', '');
		
				if ($updateUser) {
					$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-edited">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<p><strong>Félicitation !</strong> L\'utilisateur a bien été modifié. Merci de patienter, un e-mail de confirmation est en cours d\'envoi.</p>
					</div>';
				}
				else{
					$actionResultat = '<div class="alert alert-danger alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<p><strong>Erreur !</strong> L\'utilisateur n\'a pas été modifié.</p>
					</div>';
		   		 }
			}
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
					<p><strong>Félicitation !</strong> L\'utilisateur a bien été enregistré. Merci de patienter, un e-mail de confirmation est en cours d\'envoi.</p>
					</div>';
				}
				else{
					$actionResultat = '<div class="alert alert-danger alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<p><strong>Erreur !</strong> L\'utilisateur n\'a pas été enregistré.</p>
					</div>';
				};
			};			
		}
	}
};


if(isset($_POST['action']) && $_POST['action'] == 'resetPassword'){
	$targetUser = new User($_POST['id']);
	$resetPassword = $targetUser->resetPassword();
	if ($resetPassword) {
		$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-password">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p><strong>Vous venez de réinitialiser le mot de passe de '.$targetUser->getPublicName().' '.$targetUser->getPublicSurname().'.</strong> Merci de patienter, un e-mail de confirmation est en cours d\'envoi.</p>
		</div>';
	}
	else{
		$actionResultat = '<div class="alert alert-danger alert-dismissable">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p><strong>Erreur !</strong> le mot de passe de l\'utilisateur n\'a pas pu être réinitialiser.</p>
		</div>';
	}
}

if(isset($_POST['password'])){
	$delete = $currentUser->deleteUser($_POST['password'], $_POST['targetId']);

	if ($delete) {
		$actionResultat = '<div class="alert alert-success alert-dismissable" >
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p><strong>Félicitation !</strong> L\'utilisateur a bien été supprimé.</p></div>';			
	}
	else{
		$actionResultat = '<div class="alert alert-danger alert-dismissable">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p><strong>Erreur !</strong> Votre mot de passe ne correspond pas. L\'utilisateur n\'a pas pu été supprimé.</p>
		</div>';			
	}
}



include('header.php');

?>


<div class="row"">
	<div class="col-xs-12 text-center" id="alert-area">
		<?= !empty($actionResultat)?$actionResultat:''; ?>
	</div>
</div>

<div id="modalDeleteUser" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Attention!</h4>
            </div>
            <div class="modal-body">
            <?php if(isset($targetUser)){
            	if($targetUser->getStatus() == 0){
            		?>
	                <p class="alert-red">Vous ne pouvez pas supprimer un administrateur !</p>
	                <p>Si vous souhaitez vraiment supprimer le compte de <?= $targetUser->getPublicName(); ?> <?= $targetUser->getPublicSurname(); ?>, vous devez d'abord changer son statut.</p>
            		<?php
            	}
            	else{
            		?>
            		<p class="alert-red"> Vous êtes sur le point de supprimer définitivement le compte de <?= $targetUser->getPublicName(); ?> <?= $targetUser->getPublicSurname(); ?>.</p>
               		<p> Pour valider la suppression, veuillez saisir votre mot de passe.</p>

                	<form action="users_management.php" method="post" class="form-inline clearfix">
                		<div class="form-group">
	                		<label for="password">Mot de passe :</label>
	                		<input type="password" name="password" placeholder="Votre mot de passe" class="form-control" required />
	                	</div>
                		<input type="hidden" value="<?= isset($targetUser)?$targetUser->getId():';' ?>" name="targetId">
                		<input type="submit" value="Supprimer" class="btn btn-danger pull-right" />
					</form>
            		<?php
            	}
            }?>
			</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-9 col-xs-12">
		<section id="formUsers">
		<h2>Liste des utilisateurs</h2>
			<div class="table-responsive">
			<table class="table table-striped">
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
							<?= !empty($l->getLastConnection())?dateFormat($l->getLastConnection()):'Aucune connexion'; ?>
						</td>
						<td>
							<div class="form-group" >
								<select class="form-control actionUser">
									<option> --- </option>
									<option value="update" data-id="<?= $l->getId(); ?>">Modifier</option>
									<option value="delete" data-id="<?= $l->getId(); ?>" >Supprimer</option>
								</select>
							</div>
						</td>
					</tr>
				<?php 
					};
				?>
				</tbody>

			</table>
			</div>
			<div>
				<a href="<?= URL_ADMIN ?>users_management.php" class="btn btn-default btn-lg btn-custom"><span class="fa fa-plus-circle"></span> Ajouter un utilisateur</a>
			</div>
		</section>
	</div>

	<div class="col-md-3 col-xs-12">

		<section>
		<div id="formArea">
		 <?php
			if(isset($targetUser)){
				$targetUser->form(URL_ADMIN.'users_management.php', 'Modifier', 'Modifier un utilisateur :', 'edit');
				?>
				<form action="<?= URL_ADMIN; ?>users_management.php" method="POST" id="reset-password">
					<input type="hidden" name="action" value="resetPassword">
					<input type="hidden" name="id" value="<?= $targetUser->getId(); ?>" />
					<input type="submit" value="Réinitialiser le mot de passe" class="btn btn-warning btn-md" />
				</form>
			<?php
			}
			else{

				$user = new User();
				$user->form(URL_ADMIN.'users_management.php','Créer', 'Créer un utilisateur :', 'add');

			}
		?>
		</div>
		</section>
	</div>

</div>
<script type="text/javascript"> var adminData = <?php  if(isset($currentUser)){echo $currentUser->toJson() ;}else{echo "''";} ; ?></script>

<script type="text/javascript"> var userData = <?php  if(isset($newUser)){echo $newUser->toJson() ;}else{echo "''";} ; ?></script>

<script type="text/javascript"> var targetUserData = <?php  if(isset($targetUser)){echo $targetUser->toJson() ;}else{echo "''";} ; ?></script>

<?php
include('footer.php');
