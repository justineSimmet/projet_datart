<?php

require_once('classes/user.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
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
			$actionResultat = '<div class="alert alert-success alert-dismissable"s>
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
<div id="hideExhibit" class="modal fade" role="dialog" >
	<div class="modal-dialog">
	</div>
		<div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Attention !</h4>
        	</div>
        	<div class="modal-body_test">
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

<div class="row">

	<div class="col-lg-9">

<!-- SECTION TABLEAU DE L'EXPOSITION EN COURS -->
		<h2>Exposition en cours</h2>
	<section>

		<table class="table">
		<?php
			$currentExhibit = Exhibit::currentExhibit();
		?>
			<tbody>
				<tr>
					<td>
					<h3><?= $currentExhibit->getTitle(); ?></h3>
					<p class="date"><?= dateFormat($currentExhibit->getBeginDate()); ?> > <?= dateFormat($currentExhibit->getEndDate()); ?></p>
					</td>
					<td>
						<div class="form-group" >
							<select class="form-control actionExhibit">
								<option> --- </option>
								<option value="update" data-id="<?= $currentExhibit->getId(); ?>">Modifier</option>
								<option value="delete" data-id="<?= $currentExhibit->getId(); ?>" >Supprimer</option>
							</select>
						</div>
					</td>
				</tr>
			</tbody>
			<tbody>
				<table class="table table-bordered text-center">
					<thead>
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
							Chinois
						</th>
						<th>
							Russe
						</th>
					</thead>
					<tbody>
						<tr>
							<td>
								<!-- Fonction qui retourne le nombre d'artistes liés à l'expo -->
							</td>
							<td>
								<!-- Fonction qui retourne le nombre d'oeuvres liés à l'expo -->
							</td>
							<td>
								<!-- Fonction qui retourne le nombre d'oeuvres liés à l'expo et disponible à la galerie -->
							</td>
							<td>
								<?= $currentExhibit->checkTrad('english') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
							<td>
								<?= $currentExhibit->checkTrad('german') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
							<td>
								<?= $currentExhibit->checkTrad('russian') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
							<td>
								<?= $currentExhibit->checkTrad('chinese') == TRUE?'<span class="fa fa-check red"></span>':'<span class="fa fa-circle-o red"></span>' ?>
							</td>
						</tr>
					</tbody>
				</table>
			</tbody>
		</table>
	</section>

<!-- SECTION TABLEAU DES EXPOSITIONS A VENIR -->
		<h2>Expositions à venir</h2>
	<section>
		<table class="table table-striped">
			<?php
				$listNext = Exhibit::listNextExhibit();
				foreach ($listNext as $ln) {
					?>
					<table class="table">
						<tbody>
							<tr>
								<td>
									<h3><?= $ln->getTitle(); ?></h3>
									<p class="date"><?= dateFormat($ln->getBeginDate()); ?> > <?= dateFormat($ln->getEndDate()); ?></p>
								</td>
								<td>
									<div class="form-group" >
										<select class="form-control actionExhibit">
											<option> --- </option>
											<option value="update" data-id="<?= $ln->getId(); ?>">Modifier</option>
											<option value="hide" data-id="<?= $ln->getId(); ?>" >Supprimer</option>
										</select>
									</div>
								</td>
							</tr>
						</tbody>
						<tbody>
							<table class="table table-bordered text-center">
								<thead>
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
										Chinois
									</th>
									<th>
										Russe
									</th>
								</thead>
								<tbody>
									<tr>
										<td>
											<!-- Fonction qui retourne le nombre d'artistes liés à l'expo -->
										</td>
										<td>
											<!-- Fonction qui retourne le nombre d'oeuvres liés à l'expo -->
										</td>
										<td>
											<!-- Fonction qui retourne le nombre d'oeuvres liés à l'expo et disponible à la galerie -->
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
						</tbody>
					</table>
					<?php
				}
			?>		
		</table>
		
	</section>

	<?php
		if($currentUser->getStatus() == FALSE ){
			?>
				<h2>Expositions en cours de suppression</h2>
			<section>
				<table class="table table-striped">
					<?php
					$listHide = Exhibit::listHidenExhibit();
					foreach ($listHide as $lh) {
					?>
						<tr>
							<td>
								<h4><?= $lh->getTitle(); ?></h4>
								<p class="date">Crée le : <?= dateFormat($lh->getCreationDate()); ?></p>
							</td>
							<td>
								<div class="form-group" >
									<select class="form-control actionExhibit">
										<option> --- </option>
										<option value="show" data-id="<?= $lh->getId(); ?>">Visionner</option>
										<option value="publish" data-id="<?= $lh->getId(); ?>" >Publier</option>
									</select>
								</div>
							</td>
							<td>
								<button type="button" class="btn btn-default btn-delete" data-id="<?= $lh->getId(); ?>" >Supprimer définitivement</button>
							</td>
						</tr>
					<?php
					}
					?>
				</table>
			</section>
			<?php
		}
	?>
		
	</div>

	<div class="col-lg-3">

		<h2>Expositions passées</h2>
		<table class="table table-bordered">
			<?php
				$listOld = Exhibit::listPassedExhibit();
				foreach ($listOld as $lo) {
					?>
						<tr>
							<h4><?= $lo->getTitle(); ?></h4>
							<p class="date"><?= dateFormat($lo->getBeginDate()); ?> > <?= dateFormat($lo->getEndDate()); ?></p>
							<a href="exhibit_zoom.php?exhibit=<?= $lo->getId(); ?>"><span class="fa fa-eye"></span></a>
						</tr>
					<?php
				}
			?>
		</table>
	</div>

</div>

<?php
include('footer.php');
