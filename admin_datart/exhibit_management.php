<?php

require_once('classes/user.php');
require_once('classes/artist.php');
require_once('classes/artist_textual_content.php');
require_once('classes/artwork.php');
require_once('classes/artwork_visual.php');
require_once('classes/artwork_additional.php');
require_once('classes/artwork_textual_content.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
require_once('classes/event.php');
require_once('includes/include.php');


$locationTitle = 'Gestion des expositions';

if (isset($_POST['targetId'])) {
	$targetExhibit = new Exhibit($_POST['targetId']);
};

if (isset($_POST['targetId']) && isset($_POST['action']) ) {
	if ($_POST['action'] == 'hide') {
		$targetExhibit = new Exhibit($_POST['targetId']);
		$hide = $targetExhibit->hideExhibit();
		if ($hide) {
			$actionResultat = '<div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Vous venez de supprimer l\'exposition '.$targetExhibit->getTitle().' . </strong>
				</div>';
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> L\'exposition '.$targetExhibit->getTitle().' n\'a pas été supprimée.
			</div>';
	    }
	}
	elseif($_POST['action'] == 'publish'){
		$targetExhibit = new Exhibit($_POST['targetId']);
		$publish = $targetExhibit->publishExhibit();
		if ($publish) {
			$actionResultat = '<div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Vous venez de re-publier l\'exposition '.$targetExhibit->getTitle().' . </strong>
				</div>';
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> L\'exposition '.$targetExhibit->getTitle().' n\'a pas pu être re-publiée.
			</div>';
	    }
	}
	elseif($_POST['action'] == 'delete'){
		$targetExhibit = new Exhibit($_POST['targetId']);
		$check = $currentUser->passwordCheck($_POST['password']);
		if ($check) {
			$delete = $targetExhibit->deleteExhibit();
			if ($delete) {
				$actionResultat = '<div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Vous venez de supprimer définitivement l\'exposition '.$targetExhibit->getTitle().'. </strong>
				</div>';
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

include('header.php');

?>

<div class="row" id="alert-area">
	<?= !empty($actionResultat)?$actionResultat:''; ?>
</div>

<!-- MODAL POUR CONFIRMER LE CHANGEMENT DE STATUT D'UNE EXPOSITION A VISIBLE FALSE -->
<div id="hideExhibit" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Attention !</h4>
        	</div>
        	<div class="modal-body">
        		<p> Vous êtes sur le point de supprimer l'exposition <?= isset($targetExhibit)?$targetExhibit->getTitle():''; ?>. </p>
                <p> Voulez-vous confirmer cette action ?</p>
                <form action="exhibit_management.php" method="POST">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
	                <input type="hidden" name="targetId" value="<?= isset($targetExhibit)?$targetExhibit->getId():'' ; ?>" />
	                <input type="hidden" name="action" value="hide" />
	              	<!-- <button type="button" class="btn btn-default" id="btn-hide">Supprimer</button> -->
	              	<input type="submit" class="btn btn-default" value="Supprimer" />
                </form>
        	</div>
        	<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POUR CONFIRMER LA SUPPRESSION DEFINITIVE D'UNE EXPOSITION -->
<div id="deleteExhibit" class="modal fade" role="dialog" >
	<div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Attention !</h4>
        	</div>
        	<div class="modal-body">
        		<p> Vous êtes sur le point de supprimer <strong>définitivement</strong> l'exposition <?= isset($targetExhibit)?$targetExhibit->getTitle():''; ?>. </p>
                <p> Pour confirmer cette action, merci de saisir votre mot de passe</p>
                <form action="exhibit_management.php" method="POST">
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
</div>

<div class="row">

	<div class="col-lg-9" id="managementExhibitList">

<!--
************************************************************************************************
	ZONE LISTE EXPO EN COURS
************************************************************************************************
-->
	<h2>Exposition en cours</h2>
	<section class="listing">
		<?php
		$currentExhibit = Exhibit::currentExhibit();
		foreach ($currentExhibit as $ce){
		?>
		<div class="row col-equal-height">
			<div class="col-sm-6">
				<h3><?= $ce->getTitle(); ?></h3>
				<p class="major-list-date"><?= dateFormat($ce->getBeginDate()); ?> > <?= dateFormat($ce->getEndDate()); ?></p>
			</div>
			<div class="col-sm-6">
				<form class="form-horizontal">
					<div class="form-group">
						<label for="actions-expo" class="control-label col-sm-4">Actions :</label>
						<div class="col-sm-8">
							<select name="actions-expo" class="form-control actionExhibit">
								<option> --- </option>
								<option value="update" data-id="<?= $ce->getId(); ?>">Voir / Modifier</option>
								<option value="hide" data-id="<?= $ce->getId(); ?>" >Supprimer</option>
							</select>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">
				<table class="table text-center">
					<thead>
						<tr>
							<th>
								Artistes
							</th>
							<th>
								Oeuvres
							</th>
							<th>
								Oeuvres dispo.
							</th>
							<th>
								Anglais
							</th>
							<th>
								Allemand
							</th>
							<th>
								Russe
							</th>
							<th>
								Chinois
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<?= $ce->totalArtistExposed(); ?>
							</td>
							<td>
								<?= $ce->totalArtworkDisplayed(); ?>
							</td>
							<td>
								<?= $ce->totalAvaibleArtwork(); ?> / <?= $ce->totalArtworkDisplayed(); ?>
							</td>
							<td>
								<?= $ce->checkTrad('english') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
							<td>
								<?= $ce->checkTrad('german') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
							<td>
								<?= $ce->checkTrad('russian') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
							<td>
								<?= $ce->checkTrad('chinese') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
						</tr>
					</tbody>
				</table>
				</div>				
			</div>
		</div>
		<?php
		};
		?>
		</section>


<!--
************************************************************************************************
	ZONE LISTE EXPO A VENIR
************************************************************************************************
-->
	<h2>Expositions à venir</h2>
	<section class="listing">
		<?php
		$listNext = Exhibit::listNextExhibit();
		foreach ($listNext as $ln) {
		?>
		<div class="row col-equal-height">
			<div class="col-sm-6">
				<h3><?= $ln->getTitle(); ?></h3>
				<p class="major-list-date"><?= dateFormat($ln->getBeginDate()); ?> > <?= dateFormat($ln->getEndDate()); ?></p>
			</div>
			<div class="col-sm-6">
				<form class="form-horizontal">
					<div class="form-group">
						<label for="actions-expo" class="control-label col-sm-4">Actions :</label>
						<div class="col-sm-8">
							<select name="actions-expo" class="form-control actionExhibit">
								<option> --- </option>
								<option value="update" data-id="<?= $ln->getId(); ?>">Voir / Modifier</option>
								<option value="hide" data-id="<?= $ln->getId(); ?>" >Supprimer</option>
							</select>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">
				<table class="table text-center">
					<thead>
						<tr>
							<th>
								Artistes
							</th>
							<th>
								Oeuvres
							</th>
							<th>
								Oeuvres dispo.
							</th>
							<th>
								Anglais
							</th>
							<th>
								Allemand
							</th>
							<th>
								Russe
							</th>
							<th>
								Chinois
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<?= $ln->totalArtistExposed(); ?>
							</td>
							<td>
								<?= $ln->totalArtworkDisplayed(); ?>
							</td>
							<td>
								<?= $ln->totalAvaibleArtwork(); ?> / <?= $ln->totalArtworkDisplayed(); ?>
							</td>
							<td>
								<?= $ln->checkTrad('english') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
							<td>
								<?= $ln->checkTrad('german') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
							<td>
								<?= $ln->checkTrad('russian') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
							<td>
								<?= $ln->checkTrad('chinese') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
						</tr>
					</tbody>
				</table>
				</div>				
			</div>
		</div>
		<?php
		};
		?>	
		</section>

<!--
************************************************************************************************
	ZONE LISTE EXPO EN COURS DE SUPPRESSION
************************************************************************************************
-->	
		<?php
		if($currentUser->getStatus() == FALSE ){
			?>
		<h2>Expositions en cours de suppression</h2>
			<section>
				<?php
				$listHide = Exhibit::listHiddenExhibit();
				foreach ($listHide as $lh) {
				?>
				<div class="row col-equal-height">
					<div class="col-sm-4">
						<h3><?= $lh->getTitle(); ?></h3>
						<p class="date">Crée le : <?= dateFormat($lh->getCreationDate()); ?></p>
					</div>
					<div class="col-sm-4">
						<form class="form-horizontal">
							<div class="form-group">
								<label for="actions-expo" class="control-label col-sm-5">Actions :</label>
								<div class="col-sm-7">
									<select name="actions-expo" class="form-control actionExhibit">
										<option> --- </option>
										<option value="show" data-id="<?= $lh->getId(); ?>">Visionner</option>
										<option value="publish" data-id="<?= $lh->getId(); ?>" >Publier</option>
									</select>
								</div>
							</div>
						</form>
					</div>
					<div class="col-sm-4">
						<button type="button" class="btn btn-danger btn-block delete-exhibit" data-id="<?= $lh->getId(); ?>" >Supprimer définitivement</button>
					</div>
				</div>
				<?php
				}
				?>
			</section>
			<?php
		}
	?>
		
	</div>

<!--
************************************************************************************************
	ZONE LISTE EXPO PASSEES
************************************************************************************************
-->	
	<div class="col-lg-3">
		<a href="<?= URL_ADMIN ?>exhibit_zoom.php" class="btn btn-default btn-block btn-lg btn-custom"><span class="fa fa-plus-circle"></span> Nouvelle exposition</a>	

		<h4>Expositions passées</h4>
		<section class="minor-list">
			<ul>
			<?php
				$listOld = Exhibit::listPassedExhibit();
				foreach ($listOld as $lo) {
			?>
				<li>
					<h5><?= $lo->getTitle(); ?></h5>
					<p class="minor-date"><?= dateFormat($lo->getBeginDate()); ?> > <?= dateFormat($lo->getEndDate()); ?></p>
					<a href="<?= URL_ADMIN ?>exhibit_zoom.php?exhibit=<?= $lo->getId(); ?>"><span class="fa fa-eye"></span></a>
				</li>
			<?php
				}
			?>
			</ul>
		</section>
	</div>

</div>

<?php
include('footer.php');
