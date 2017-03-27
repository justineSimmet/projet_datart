<?php 

require_once('classes/user.php');
require_once('classes/artwork.php');
require_once('classes/artwork_textual_content.php');
require_once('classes/artwork_visual.php');
require_once('classes/artwork_additional.php');
require_once('classes/artist.php');
require_once('classes/artist_textual_content.php');
require_once('classes/exhibit.php');
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
		<p><strong>Félicitations !</strong> '.$targetArtist->getIdentity().' a été supprimé.</p></div>';	
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p><strong>Erreur !</strong> '.$targetArtist->getIdentity().' n\'a pas pu être supprimé.</p></div>';	
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
				<p><strong>Vous venez de supprimer définitivement l\'artiste '.$targetArtist->getIdentity().' </strong></p>
				</div>';	
			}
			else{
				$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<p><strong>Erreur !</strong> L\'artiste '.$targetArtist->getIdentity().' n\'a pas été supprimé.</p>
				</div>';
			}
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<p><strong>Votre mot de passe est incorrect.</strong> Vous ne pouvez pas supprimer l\'artiste '.$targetArtist->getIdentity().'</p>
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

<!-- Modal pour mettre un artiste en false dc disparait pour les users -->
<div id="hideartist" class="modal fade" role="dialog">
    <div class="modal-dialog">
        	<div class="modal-content">
	        <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <h4 class="modal-title">Attention !</h4>
	        </div>
	        <div class="modal-body">
	           		<p> Vous êtes sur le point de supprimer l'artiste <?= isset($targetArtist)?$targetArtist->getIdentity():''; ?> </p>
	           		<p>Voulez-vous confirmer cette action ?</p>
	           			<form action="<?= URL_ADMIN; ?>artist_management.php" method="POST">
	           				<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
							<input type="hidden" value="<?= isset($targetArtist)?$targetArtist->getId():'' ; ?>" name="targetId"/>
							<input type="hidden" name="action" value="hide">
			               	<input type="submit" value="Supprimer" class="btn btn-danger pull-right" />
			            </form>
	            </div> 
	            <div class="modal-footer">
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	            </div>
        	</div>
    </div>
</div>


<!-- Modal pour confirmer la suppression definitive par l'admin -->
<div id="deleteArtist" class="modal fade" role="dialog" >
	<div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Attention !</h4>
        	</div>
        	<div class="modal-body">
        		<p> Vous êtes sur le point de supprimer <strong>définitivement</strong> l'artiste <?= isset($targetArtist)?$targetArtist->getIdentity():''; ?> </p>
                <p> Pour confirmer cette action, merci de saisir votre mot de passe</p>
                <form action="<?= URL_ADMIN; ?>artist_management.php" method="POST" class="form-inline clearfix">
                	<div class="form-group">
	                	<label for="password">Mot de passe :</label>
	                	<input type="password" name="password" placeholder="Votre mot de passe" class="form-control" required />
	                </div>
                	<input type="hidden" value="<?= isset($targetArtist)?$targetArtist->getId():';' ?>" name="targetId">
                	<input type="submit" value="Supprimer" class="btn btn-danger pull-right" />
				</form>
        	</div>
        	<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
	</div>
</div>


<div class="row" id="artistManagementList">

	<div class="col-sm-9 col-xs-12">
		 <h2>Artistes enregistrés</h2>

		 <section class="listing">
		 	<?php 
				// creation de la liste avec un foreach et chaque l = un artiste
				$artistes = Artist::listArtist();
				foreach ($artistes as $a) {
			?>
			<div class="row col-equal-height">
				<div class="col-sm-6">
					<h3><?= $a->getIdentity(); ?></h3>
				</div>
				<div class="col-sm-6">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="actions-artist" class="control-label col-sm-4">Actions :</label>
							<div class="col-sm-8">
								<select name="actions-artist" class="form-control actionArtist">
									<option> --- </option>
									<option value="update" data-id="<?= $a->getId(); ?>">Voir / Modifier</option>
									<option value="hide" data-id="<?= $a->getId(); ?>" >Supprimer</option>
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
                                    Oeuvres associées
                                </th>
                                <th>
                                    Biographie
                                </th>
                                <th>
                                    Note
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
                                    <!-- NOMBRE D'OEUVRES -->
                                </td>
                                <td>
                                     <!-- BIOGRAPHIE -->
                                </td>
                                <td>
                                     <!-- NOTE -->
                                </td>
                                <td>
                                    <!-- TRAD FRENCH -->
                                </td>
                                <td>
                                    <!-- TRAD GERMAN -->
                                </td>
                                <td>
                                    <!-- TRAD RUSSIAN -->
                                </td>
                                <td>
                                    <!-- TRAD CHINESE -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>              
                </div>
			</div>
			<?php
				}
			?>
		 </section>

	</div>
	<div class="col-sm-3 col-xs-12">
		<div>
			<a href="<?= URL_ADMIN ?>artist_zoom.php" class="btn btn-default btn-block btn-custom"><span class="fa fa-plus-circle"></span> Créer un nouvel artiste</a>
		</div>
	</div>
</div>

<?php
if($currentUser->getStatus() == FALSE ){
?>

<div class="row">
	<div class="col-sm-12">
		<h2>Artistes en cours de suppression</h2>
		<section>
			<?php 
				$artistHide = Artist::listHidenArtist();
				foreach ($artistHide as $ah){ 
			?>
				<div class="row col-equal-height">
					<div class="col-sm-4">
						<h3><?= $ah->getIdentity(); ?></h3>
						<p class="date">Créé le : <?= dateFormat($ah->getCreationDate()); ?></p>
					</div>
					<div class="col-sm-4">
						<form class="form-horizontal">
							<div class="form-group">
								<label for="actions-artist" class="control-label col-sm-5">Actions :</label>
								<div class="col-sm-7">
									<select name="actions-artist" class="form-control actionArtist">
										<option> --- </option>
										<option value="show" data-id="<?= $ah->getId(); ?>">Visionner</option>
										<option value="publish" data-id="<?= $ah->getId(); ?>" >Publier</option>
									</select>
								</div>
							</div>
						</form>
					</div>
					<div class="col-sm-4">
						<button type="button" class="btn btn-danger btn-block delete-artist" data-id="<?= $ah->getId(); ?>" >Supprimer définitivement</button>
					</div>
				</div>
			<?php 
				}
			?>
		</section>
	</div>
</div>
	

<?php
}
include('footer.php');