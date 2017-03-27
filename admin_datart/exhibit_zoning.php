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
<div class="container-fluid">
	<div class="row">

<!-- ////////////////////////////////////////////////////////////////

	MENU PRINCIPAL DU EXHIBIT ZONNING

//////////////////////////////////////////////////////////////// -->
	<header class="col-sm-12" id="zoning-header">
        <div class="col-sm-9 col-xs-6">
            <div class="row">
            	<div class="col-sm-2 col-xs-12 nav-btn text-center">
            		<a href="JavaScript:window.close()"><span class="fa fa-arrow-circle-o-left"></span><br/>Retour</a>
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
		    <ul class="nav navbar-nav">
		      <li><a href="#">Enregistrer</a></li>
		      <li><a href="#">Annuler</a></li>
		      <li><a href="#">Réinitialiser</a></li>
		    </ul>
		</div>
	</nav>

	<div class="col-sm-12">
		<div class="row">
<!-- ////////////////////////////////////////////////////////////////

	LISTING DES OEUVRES

//////////////////////////////////////////////////////////////// -->
			<div class="col-sm-3">
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
											<div class="drag-area">
												<span class="fa fa-arrows"></span>
												<p class="artwork-ref"><?= $artwork->getReferenceNumber(); ?></p>
											</div>
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
			<div class="col-sm-9">
				<div id="drop-area">
					<img src="<?= URL_IMAGES ?>galerie-ga.svg">
					<div id="drop-target"></div>
				</div>
			</div>

<!-- ////////////////////////////////////////////////////////////////

	PLAN DE POSITIONNEMENT

//////////////////////////////////////////////////////////////// -->
		</div>
	</div>	
	</div>
</div>
<footer>
	<script src="<?= URL_ASSETS ?>js/jquery-3.1.1.js"></script>
	<script src="<?= URL_ASSETS ?>js/jquery-ui.min.js"></script>
	<script src="<?= URL_ASSETS ?>js/bootstrap.min.js"></script>
	<script src="<?= URL_ASSETS ?>js/zoning.js"></script>
</footer>
</body>
</html>