<?php

require_once('classes/user.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
require_once('includes/include.php');

if (isset($_GET['exhibit'])) {
	$targetExhibit = new Exhibit($_GET['exhibit']);
}

if (isset($_POST['title'])) {
	if (empty($_POST['id'])) {
		$newExhibit = new Exhibit();
		$newExhibit->setTitle($_POST['title']);
		$newExhibit->setBeginDate($_POST['begin_date']);
		$newExhibit->setEndDate($_POST['end_date']);
		$newExhibit->setPublicOpening($_POST['public_opening']);
		$create = $newExhibit->synchroDb();
		if ($create) {
			header('Location:exhibit_zoom.php?exhibit='.$create);
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> L\'exposition n\'a pas pu être enregistrée.
			</div>';
		}
	}
	else{
		$targetExhibit = new Exhibit($_POST['id']);
		$targetExhibit->setTitle($_POST['title']);
		$targetExhibit->setBeginDate($_POST['begin_date']);
		$targetExhibit->setEndDate($_POST['end_date']);
		$targetExhibit->setPublicOpening($_POST['public_opening']);
		$update = $targetExhibit->synchroDb();
		var_dump($update);
		if ($update) {
			$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-edited">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Félicitation</strong> L\'exposition a bien été modifiée.
				</div>';
		}else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> L\'exposition n\'a pas pu être modifiée.
			</div>';	
		}
	}
}

if (isset($_POST['targetId']) && isset($_POST['action']) ) {
	if($_POST['action'] == 'publish'){
		$targetExhibit = new Exhibit($_POST['targetId']);
		$publish = $targetExhibit->publishExhibit();
	}
	elseif($_POST['action'] == 'delete'){
		$targetExhibit = new Exhibit($_POST['targetId']);
		$check = $currentUser->passwordCheck($_POST['password']);
		if ($check) {
			$delete = $targetExhibit->deleteExhibit();
			if ($delete) {
				header('Location:exhibit_management.php');
			}
			else{
				$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur !</strong> L\'exposition '.$targetExhibit->getTitle().' n\'a pas été supprimée.
				</div>';
			}
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Votre mot de passe est incorrect.</strong> Vous ne pouvez pas supprimer l\'exposition '.$targetExhibit->getTitle().'.
			</div>';
		}
	
	}
}

$locationTitle = isset($targetExhibit)?$targetExhibit->getTitle():'Ajouter une exposition';

include('header.php');

?>
<div class="row">

<?php
	if (isset($targetExhibit) && ($targetExhibit->getVisible() == FALSE && $currentUser->getStatus() == TRUE )) {
?>	
	
	<div class="col-lg-6 col-lg-offset-3 col-md-6 col-lg-offset-3 col-sm-12 col-xs-12">
		<div class="alert alert-warning text-center">
		  <strong>L'exposition que vous souhaitez consulter n'est plus disponible.</strong><br>
		  <a href="index.php" class="btn btn-default" role="button">Retour au tableau de bord</a>
		  <a href="exhibit_management.php" class="btn btn-default" role="button">Retour aux expositions</a>
		</div>
	</div>

<?php
	}
	else{
?>

	<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

<?php
	if (isset($targetExhibit)){
?>
	<!-- BOUTONS D'ACTIONS -->
		<div class="row">

		<!-- MODAL POUR CONFIRMER LA SUPPRESSION DEFINITIVE D'UNE EXPOSITION -->
		<div id="deleteExhibit" class="modal fade" role="dialog" >
			<div class="modal-dialog">
			</div>
				<div class="modal-content">
		        	<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title">Attention !</h4>
		        	</div>
		        	<div class="modal-body_test">
		        		<p> Vous êtes sur le point de supprimer <strong>définitivement</strong> l'exposition <?= isset($targetExhibit)?$targetExhibit->getTitle():''; ?>. </p>
		                <p> Pour confirmer cette action, merci de saisir votre mot de passe</p>
		                <form action="<?= $_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId() ?>" method="POST">
			                <label for="password">Mot de passe :</label>
			                <input type="password" name="password" placeholder="Votre mot de passe"  required />
			                <input type="hidden" name="action" value="delete" />
			                <input type="hidden" value="<?= isset($targetExhibit)?$targetExhibit->getId():';' ?>" name="targetId">
			                <input type="submit" value="Supprimer" />
		                </form>
		        	</div>
		        	<div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
		            </div>
		        </div>
		</div>

		<?php
			if ($targetExhibit->getVisible() == TRUE) {
		?>
			<section class="col-lg-12 col-md-12 hidden-md col-sm-12 hidden-sm col-xs-12">
				<a href="#" class="btn btn-default" role="button"><span class="fa fa-cubes"></span> Placer les oeuvres</a>
				<a href="#" class="btn btn-default" role="button"><span class="fa fa-file-text"></span> Dossier technique</a>
				<a href="#" class="btn btn-default" role="button"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
			</section>
		<?php
			}
			else{
		?>
			<section class="col-lg-12 col-md-12 hidden-md col-sm-12 hidden-sm col-xs-12">
				<button class="btn btn-default" role="button"><span class="fa fa-eye"></span> Publier l'exposition</button>
				<button class="btn btn-default btn-delete" role="button" data-id="<?= $targetExhibit->getId(); ?>" ><span class="fa fa-trash"></span> Supprimer définitivement l'exposition</button>
			</section>	
		<?php
			}
		?>
		</div>
<?php
	}
?>

		<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row" id="alert-area">
					<?= !empty($actionResultat)?$actionResultat:''; ?>
				</div>
			</div>

		</div>

		<div class="row">

			<div class="col-lg-12 col-md-9 col-sm-9 col-xs-12"> <!-- ZONE PRINCIPALE -->
				<section> <!--FORMULAIRES INFOS GENERALES & TEXTES -->
					<?php

						if (isset($targetExhibit)) {
							$targetExhibit->formInfos($_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId(),'Modifier');
						}
						else{
							$newExhibit = new Exhibit();
							$newExhibit->formInfos($_SERVER['PHP_SELF'],'Créer');
						}

					?>

				</section>

				<section> <!-- FORMULAIRE ARTISTES -->
					
				</section>

				<section> <!-- FORMULAIRE OEUVRES -->
					
				</section>
			</div>

			<div class="col-lg-12 col-md-3 col-sm-3 col-xs-12"> <!-- ZONE ANNEXE A DROITE -->
			<?php
			if (isset($targetExhibit)){
				if ($targetExhibit->getVisible() == TRUE) {
			?>
				<section class="col-lg-12 hidden-lg col-md-12 col-sm-12 col-xs-12 hidden-xs">
					<a href="#" class="btn btn-default" role="button"><span class="fa fa-cubes"></span> Placer les oeuvres</a>
					<a href="#" class="btn btn-default" role="button"><span class="fa fa-file-text"></span> Dossier technique</a>
					<a href="#" class="btn btn-default" role="button"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
				</section>
			<?php
				}
				else{
			?>
				<section class="col-lg-12 hidden-lg col-md-12 col-sm-12 col-xs-12 hidden-xs">
					<button class="btn btn-default" role="button"><span class="fa fa-eye"></span> Publier l'exposition</button>
					<button class="btn btn-default" role="button"><span class="fa fa-trash"></span> Supprimer définitivement l'exposition</button>
				</section>	
			<?php
				}
			}
			?>
			</div>

		</div>
		
	</div>

<?php
	}
?>

</div>


<?php
include('footer.php');
