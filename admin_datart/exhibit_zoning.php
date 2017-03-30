<?php
require_once('classes/user.php');
require_once('classes/artist.php');
require_once('classes/artist_textual_content.php');
require_once('classes/exhibit_textual_content.php');
require_once('classes/artwork_additional.php');
require_once('classes/artwork.php');
require_once('classes/artwork_textual_content.php');
require_once('classes/artwork_visual.php');
require_once('classes/event.php');
require_once('classes/exhibit.php');
require_once('includes/include.php');

// INITIALISE UN OBJET EXHIBIT SI ID EN GET
if (isset($_GET['id'])) {
	$targetExhibit = new Exhibit($_GET['id']);
	if (!empty($targetExhibit->getZoning())) {
		$artworkItem = json_decode($targetExhibit->getZoning());
	}
};



?><!DOCTYPE html>
 <html lang="fr">
 <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 	<title><?= isset($location)?$location:'Application DATART / Grand Angle'; ?></title>
 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS ?>styles/css/jquery-ui.min.css" />
 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS ?>styles/css/jquery-ui.structure.min.css" />
 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS ?>styles/css/jquery-ui.theme.min.css" />
 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS ?>styles/css/bootstrap.min.css" />
 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS ?>styles/css/font-awesome.min.css" />
 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS ?>styles/css/reset.css" />
 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS ?>styles/css/styles.css" />
 </head>
 <body>

<!-- MODAL POUR CONFIRMER L'ANNULATION DES CHANGEMENTS -->
<div id="cancelChange" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Attention !</h4>
        	</div>
        	<div class="modal-body clearfix">
        		<p> Vous êtes sur le point d'annuler vos modifications récentes.</p>
                <p> Voulez-vous confirmer cette action ?</p>
	            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Annuler</button>
	            <button type="button" class="btn btn-danger pull-right" onclick="location.reload(true)">Confirmer</button>

        	</div>
        	<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POUR CONFIRMER LA SUPPRESSION DES DONNEES -->
<div id="deleteChange" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Attention !</h4>
        	</div>
        	<div class="modal-body clearfix">
        		<p> Vous êtes sur le point de supprimer le positionnement d'oeuvres.</p>
                <p> Voulez-vous confirmer cette action ?</p>
	            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Annuler</button>
	            <button type="button" class="btn btn-danger pull-right" data-exhibit="<?= $targetExhibit->getId() ?>" id="deleteData">Confirmer</button>

        	</div>
        	<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POUR CONFIRMER LE FAIT DE QUITTER LA PAGE -->
<div id="quitWindow" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Attention !</h4>
        	</div>
        	<div class="modal-body clearfix">
        		<p>Vous êtes sur le point de quitter la page actuelle.</p>
                <p>Vos modifications seront perdues si elles n'ont pas été enregistrées</p>
	            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Annuler</button>
	            <button type="button" class="btn btn-danger pull-right" onclick="window.close()">Quitter la page</button>

        	</div>
        	<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
	<div class="row">

<!-- ////////////////////////////////////////////////////////////////

	MENU PRINCIPAL DU EXHIBIT ZONNING

//////////////////////////////////////////////////////////////// -->
	<header class="col-sm-12" id="zoning-header">
        <div class="col-sm-9 col-xs-6">
            <div class="row">
            	<div class="col-sm-2 col-xs-12 nav-btn text-center">
            		<a href="#" id="quit"><span class="fa fa-arrow-circle-o-left"></span><br/>Retour</a>
            	</div>
            	<div class="col-sm-10 hidden-xs">
	                <h1>Placement des oeuvres</h1>
	                <h4>Exposition : <?= isset($targetExhibit)?$targetExhibit->getTitle():''; ?></h4>
            	</div>
            </div>
        </div>
		<div class="col-sm-3 col-xs-6 nav-logo">
           <img src="<?= URL_IMAGES ?>DAgrand-angle_vecto-blanc.png">
        </div>
        <div class="hidden-lg hidden-md hidden-sm col-xs-12 text-center">
        	<h1>Placement des oeuvres</h1>
	        <h4>Exposition : <?= isset($targetExhibit)?$targetExhibit->getTitle():''; ?></h4>
        </div>


<!-- ////////////////////////////////////////////////////////////////

	MENU D'ACTION DU EXHIBIT ZONNING

//////////////////////////////////////////////////////////////// -->

	</header>
	<nav class="navbar navbar-default col-sm-12" id="action-header">
		<div class="container-fluid">
    		<div class="navbar-header">
	    	</div>
		    <ul class="nav navbar-nav text-center">
		      <li><a href="#" id="saveZoning" data-exhibit="<?= $targetExhibit->getId(); ?>">Enregistrer</a></li>
		      <li><a href="#" id="cancelZoning" >Annuler les changements</a></li>
		      <li><a href="#" id="resetZoning" data-exhibit="<?= $targetExhibit->getId(); ?>">Réinitialiser le plan</a></li>
		    </ul>
		</div>
	</nav>
	
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2" id="alert-area">
				<?= !empty($actionResultat)?$actionResultat:''; ?>
			</div>
		</div>
		<div class="row">
<!-- ////////////////////////////////////////////////////////////////

	LISTING DES OEUVRES ET PLAN SVG

//////////////////////////////////////////////////////////////// -->
			<div class="col-md-3 col-sm-4">
				<section id="availble-artwork">
					<h2>Oeuvres enregistrées : </h2>
					<ul>
					<?php
						$listElement = $targetExhibit->listAvailableArtwork();
						foreach ($listElement as $artist => $artwork) {
							?>
							<li>
								<h4><?= $artist ;?></h4>
								<?php
									foreach ($artwork as $artwork) {
										?>
										<div class="list-element">
											<div class="text-area">
												<h3><?= $artwork->getTitle(); ?></h3>
												<p><?= !empty($artwork->getFrenchCharacteristic())?$artwork->getFrenchCharacteristic()->getContent():'---';?> | Réf. : <?= $artwork->getReferenceNumber(); ?></p>
											</div>
											<?php
											if (isset($artworkItem)) {
												if (searchArray($artwork->getReferenceNumber(), $artworkItem, false)) {
													?>
														<div class="action-item-area refreshItem"  data-reference="<?= $artwork->getReferenceNumber(); ?>">
															<span class="fa fa-refresh"></span>
														</div>
													<?php
												}
												else{
													?>
														<div class="action-item-area dragItem" data-reference="<?= $artwork->getReferenceNumber(); ?>">
															<span class="fa fa-arrows"></span>
														</div>
													<?php
												}
											}
											else{
												?>
													<div class="action-item-area dragItem" data-reference="<?= $artwork->getReferenceNumber(); ?>">
														<span class="fa fa-arrows"></span>
													</div>
												<?php
											}
											?>
										</div>
										<?php
									}
								?>
							</li>
							<?php
						}
					?>
					</ul>
				</section>
			</div>
			<div class="col-md-9 col-sm-8">
				<div id="drop-area">
						<div id="dropTarget">
							<?php
							if (isset($artworkItem)) {
								foreach ($artworkItem as $array => $item) {
									echo'<div class="ui-draggable-dropped dropItem" id="'.$item[0].'" style="position: absolute; left: '.$item[1]->left.'px; top: '.$item[1]->top.'px;"><span>'.$item[0].'</span></div>';
								}
							}
							?>
							<img src="<?= URL_IMAGES ?>/galerie-ga.png" alt="Plan de la galerie Grand Angle"/>
						</div>
				</div>
			</div>
		</div>
	</div>	
	</div>
</div>
<div id='targetCanvas'>
	
</div>
<footer>
	<script src="<?= URL_ASSETS ?>js/jquery-3.1.1.js"></script>
	<script src="<?= URL_ASSETS ?>js/jquery-ui.min.js"></script>
	<script src="<?= URL_ASSETS ?>js/bootstrap.min.js"></script>
	<script src="<?= URL_ASSETS ?>js/html2canvas.js"></script>
	<script src="<?= URL_ASSETS ?>js/zoning.js"></script>
</footer>
</body>
</html>