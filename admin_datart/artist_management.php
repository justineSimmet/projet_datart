<?php 

require_once('classes/artist.php');
require_once('classes/user.php');
require_once('classes/artist_textual_content.php');
require_once('includes/include.php');


$locationTitle = 'Les artistes';

if (isset($_POST['targetArtist'])) {
	$targetArtist = new Artist($_POST['targetArtist']);

};

if(isset($_POST['targetId'])){
	$targetArtist = new Artist($_POST['targetId']);
};


$actionResultat = '';

if(isset($_POST['targetId']) && isset($_POST['action']) ){
	if ($_POST['action'] == 'hide') {
		$targetArtist = new Artist($_POST['targetId']);
		$hide = $targetArtist->hideArtist();
		if ($hide) {
			$actionResultat = '<div class="alert alert-success alert-dismissable" >
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Félicitations </strong>'.$targetArtist->getIdentity().' a été supprimé.</div>';	
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Erreur</strong> '.$targetArtist->getIdentity().' n\'a pas pu être supprimé.</div>';	
		}
	}

	elseif($_POST['action'] == 'publish'){
		$targetArtist = new Artist($_POST['targetId']);
		$publish = $targetArtist->publishArtist();
	}

	elseif ($_POST['action'] = 'delete') {
		$targetArtist = new Artist($_POST['targetId']);
		$check = $currentUser->passwordCheck($_POST['password']);
		if ($check) {
			$delete = $targetArtist->deleteArtist();
			if ($delete) {
				$actionResultat = '<div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Vous venez de supprimer définitivement l\'artiste '.$targetArtist->getIdentity().' </strong>
				</div>';	
			}
			else{
				$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur !</strong> L\'artiste '.$targetArtist->getIdentity().' n\'a pas été supprimé.
				</div>';
			}
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Votre mot de passe est incorrect.</strong> Vous ne pouvez pas supprimer l\'artiste '.$targetArtist->getIdentity().'
			</div>';			
		}
	}
}


include('header.php');
 ?>


<div class="row" id="alert-area">
	<?= !empty($actionResultat)?$actionResultat:''; ?>
</div>

<!-- Modal pour mettre un artiste en false dc disparait pour les users -->
<div id="hideartist" class="modal fade" role="dialog">
    <div class="modal-dialog">
    </div>
        	<div class="modal-content">
	        <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <h4 class="modal-title">Attention !</h4>
	        </div>
	        <div class="modal-body_test">
	           		<p> Vous êtes sur le point de supprimer l'artiste <?= isset($targetArtist)?$targetArtist->getIdentity():''; ?> </p>
	           		<p>Voulez-vous confirmer cette action ?</p>
	           			<form action="artist_management.php" method="POST">
	           				<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
							<input type="hidden" value="<?= isset($targetArtist)?$targetArtist->getId():'' ; ?>" name="targetId"/>
							<input type="hidden" name="action" value="hide">
			               	<input type="submit" class="btn btn-default" value="Supprimer" />
			            </form>
	            </div> 
	            <div class="modal-footer">
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	            </div>
        	</div>
</div>


<!-- Modal pour confirmer la suppression definitive par l'admin -->
<div id="deleteArtist" class="modal fade" role="dialog" >
	<div class="modal-dialog">
	</div>
		<div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Attention !</h4>
        	</div>
        	<div class="modal-body_test">
        		<p> Vous êtes sur le point de supprimer <strong>définitivement</strong> l'artiste <?= isset($targetArtist)?$targetArtist->getIdentity():''; ?> </p>
                <p> Pour confirmer cette action, merci de saisir votre mot de passe</p>
                <form action="artist_management.php" method="POST">
	                <label for="password">Mot de passe :</label>
	                <input type="password" name="password" placeholder="Votre mot de passe"  required />
	                <input type="hidden" name="action" value="delete" />
	                <input type="hidden" value="<?= isset($targetArtist)?$targetArtist->getId():';' ?>" name="targetId">
	                <input type="submit" value="Supprimer" />
                </form>
        	</div>
        	<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
</div>

 <a href="artist_zoom.php" class="btn btn-default">Créer un nouvel artiste</a>


<!-- Liste des artistes -->
 <h2>Liste des artistes</h2>
 	<section>
 		<div class="row col-equal-height">
	 		<table>
	 			<!-- <thead> -->
<!-- 		 			<tr>
			 			<th>Nom</th>
			 			<th>Prénom</th>
			 			<th>Pseudonyme</th>
		 			</tr> -->
				<!-- </thead> -->
				<!-- <tbody> -->
					<?php 
						// creation de la liste avec un foreach et chaque l = un artiste
						$liste = Artist::listArtist();
							foreach ($liste as $l) {
					?>
						 	<tr>
							 	<td>
							 		<?= $l->getIdentity(); ?>
<!-- 							 	</td> -->

							 		<div class="form-group">
							 			<!-- liste deroulante -->
							 			<select class="form-control actionArtist">
								 			<option> --- </option>
								 			<option value="update" data-id="<?= $l->getId(); ?>">Voir / Modifier</option> 
								 			<option value="delete" data-id="<?= $l->getId(); ?>">Supprimer</option> 
								 		</select>
							 		</div>
							 	</td>
							</tr>
						<?php 
							};
						?>
					<!-- </tbody> -->
	 		</table>
		</div>
 	</section>


<!-- Liste des artistes en cours de suppression -->

	<?php
		if($currentUser->getStatus() == FALSE ){
	?>
		<h2>Artistes en cours de suppression</h2>

 			<section>
				<?php 
					$listHide = Artist::listHidenArtist();
					foreach ($listHide as $lh){ 
				?>
						<div class="row col-equal-height">
							<div class="col-sm-5">
								<h4><?= $lh->getIdentity(); ?></h4>
							</div>
							<div class="col-sm-4">
								<form class="form-horizontal">
									<div class="form-group">
										<label for="actions-artiste" class="control-label col-sm-4">Actions </label>
										<div class="col-sm-8">
											<select name="actions-artiste" class="form-control actionArtistAdmin">
												<option> --- </option>
												<option value="show" data-id="<?= $lh->getId(); ?>">Visionner</option>
												<option value="publish" data-id="<?= $lh->getId(); ?>">Publier</option>
											</select>
										</div>
									</div>
								</form>	
							</div>
							<div class="col-sm-3">
								<button type="button" class="btn btn-default btn-delete" data-id="<?= $lh->getId(); ?>" >Supprimer définitivement</button>
							</div>
						</div>
				<?php 
					}
				?>
				
 			</section>
		<?php

		}

include('footer.php');