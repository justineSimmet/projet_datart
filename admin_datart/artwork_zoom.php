<?php

require_once('classes/user.php');
require_once('classes/artist.php');
require_once('classes/artist_textual_content.php');
require_once('classes/artwork.php');
require_once('classes/artist.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
require_once('classes/event.php');
require_once('includes/include.php');

// INITIALISE UN OBJET EXHIBIT SI ID EN GET
if (isset($_GET['artwork'])) {
	$targetArtwork= new Artwork($_GET['artwork']);
}


/************************************************************************************************
**
** Insertion ou update en base de donnée après un submit du formulaire 1 (Infos générale)
**
************************************************************************************************/

if (isset($_POST['title'])){
	if (empty($_POST['id'])) {
		$newArtwork = new Artwork();
		$newArtwork->setArtistId($_POST['artist']);
		$newArtwork->setTitle($_POST['title']);
		$newArtwork->setDimensions($_POST['dimensions']);
		$newArtwork->setArtistId($_POST['artist']);
		$newArtwork->setDisponibility($_POST['disponibility']);
		$newArtwork->setArtistRequest($_POST['artistRequest']);
		$create = $newArtwork->synchroDb();
		if ($create) {
			header('Location:'.URL_ADMIN.'artwork_zoom.php?artwork='.$create);
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<p><strong>Erreur !</strong> L\'oeuvre '.$newArtwork->getTitle().' n\'a pas pu être enregistrée.</p>
			</div>';
		}
	}
	else{
		$targetArtwork = new Artwork($_GET['artwork']);
		$targetArtwork->setTitle($_POST['title']);
		$targetArtwork->setDimensions($_POST['dimensions']);
		$targetArtwork->setDisponibility($_POST['disponibility']);
		$targetArtwork->setArtistRequest($_POST['artistRequest']);
		$update = $targetArtwork->synchroDb();
		if ($update) {
			$actionResultat = '<div class="alert alert-success alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<p>L\'oeuvre '.$targetArtwork->getTitle().' a été modifiée.</p>
			</div>';
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<p><strong>Erreur !</strong> L\'oeuvre '.$targetArtwork->getTitle().' n\'a pas pu être modifiée.</p>
			</div>';
		}
	}
}


$locationTitle = isset($targetExhibit)?$targetExhibit->getTitle():'Ajouter une oeuvre';
include('header.php');

?>
<!--
************************************************************************************************
	MODAL POUR CONFIRMER LA SUPPRESSION DEFINITIVE D'UNE OEUVRE
************************************************************************************************
-->
	<div id="deleteArtwork" class="modal fade" role="dialog" >
		<div class="modal-dialog">
			<div class="modal-content">
	        	<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal">&times;</button>
	        		<h4 class="modal-title">Attention !</h4>
	        	</div>
	        	<div class="modal-body">
	        		<p> Vous êtes sur le point de supprimer <strong>définitivement</strong> l'oeuvre <?= isset($targetArtwork)?$targetArtwork->getTitle():''; ?>. </p>
	                <p> Pour confirmer cette action, merci de saisir votre mot de passe</p>
	                <form action="<?= isset($targetArtwork)?$_SERVER['PHP_SELF'].'?artwork='.$targetArtwork->getId():''; ?>" method="POST">
		                <label for="password">Mot de passe :</label>
		                <input type="password" name="password" placeholder="Votre mot de passe"  required />
		                <input type="hidden" name="action" value="delete-artwork" />
		                <input type="hidden" value="<?= isset($targetArtwork)?$targetArtwork->getId():';' ?>" name="targetId">
		                <input type="submit" value="Supprimer" />
	                </form>
	        	</div>
	        	<div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	            </div>
	        </div>
		</div>
	</div>

<?php
	if (isset($targetArtwork) && ($targetArtwork->getVisible() == FALSE && $currentUser->getStatus() == TRUE )) {
?>	
	
	<div class="col-lg-6 col-lg-offset-3 col-md-6 col-lg-offset-3 col-sm-12 col-xs-12">
		<div class="alert alert-warning text-center">
		  <strong>L'oeuvre que vous souhaitez consulter n'est plus disponible.</strong><br>
		  <a href="index.php" class="btn btn-default" role="button">Retour au tableau de bord</a>
		  <a href="artwork_management.php" class="btn btn-default" role="button">Retour aux oeuvres</a>
		</div>
	</div>

<?php
	}

	else{
?>

<div class="col-xs-12">
<?php
	if (isset($targetArtwork)){
		if ($targetArtwork->getVisible() == TRUE) {
?>
	<div class="hidden-lg hidden-sm btn-area-row">
		<a href="#" class="btn btn-default btn-custom btn-lg" role="button"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
	</div>
<?php
	}
	else{
?>
	<div class="hidden-lg hidden-sm btn-area-row">
		<button class="btn btn-default btn-custom btn-lg publish-artwork" role="button" data-id="<?= $targetArtwork->getId(); ?>" ><span class="fa fa-eye"></span> Publier l'oeuvre</button>
		<button class="btn btn-default btn-custom btn-lg delete-arwork" role="button" data-id="<?= $targetArtwork->getId(); ?>" ><span class="fa fa-trash"></span> Supprimer définitivement l'oeuvre</button>
	</div>	
<?php
	}
}
?>
</div>

<div class="col-lg-9 col-md-12 col-sm-9 col-xs-12">
	<div class="row" id="alert-area">
		<?= !empty($actionResultat)?$actionResultat:''; ?>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<section>
			<div id="artworkMainInfo">
					<h2>Etape 1 : Informations Générales</h2>
					<?php

						if (isset($targetArtwork)) {
							$targetArtwork->formInfos($_SERVER['PHP_SELF'].'?artwork='.$targetArtwork->getId(),'Modifier');
						}
						else{
							$newArtwork = new Artwork();
							$newArtwork->formInfos($_SERVER['PHP_SELF'],'Créer');
						}

					?>
				</div>
			</section>
		</div>
	</div>

</div>
<?php
	}
include('footer.php');
