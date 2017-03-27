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
	</div>



<!-- ////////////////////////////////////////////////////////////////

	MENU D'ACTION DU EXHIBIT ZONNING

//////////////////////////////////////////////////////////////// -->


<!-- ////////////////////////////////////////////////////////////////

	LISTING DES OEUVRES

//////////////////////////////////////////////////////////////// -->


<!-- ////////////////////////////////////////////////////////////////

	PLAN DE POSITIONNEMENT

//////////////////////////////////////////////////////////////// -->
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