<?php 


require_once('classes/artist.php');
require_once('classes/user.php');
require_once('includes/include.php');


if (isset($_GET['artist'])) {
	$targetArtist = new Artist($_GET['artist']);
}



if (isset($_POST['id'])) {
	

	if(!empty($_POST['id'])) {
		$targetArtist = new Artist($_POST['id']);
		$targetArtist->setSurname($_POST['surname']);
		$targetArtist->setName($_POST['name']);
		$targetArtist->setAlias($_POST['alias']);
		$updateArtist = $targetArtist->synchroDb();
		

		if ($updateArtist) {
			$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-edited">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Félicitation</strong> L\'artiste a bien été modifié. 
				</div>';
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur</strong> L\'artiste n\'a pas été modifié.
				</div>';
		}
	}
	else{
		$targetArtist = new Artist();
		$targetArtist->setSurname($_POST['surname']);	
		$targetArtist->setName($_POST['name']);	
		$targetArtist->setAlias($_POST['alias']);	

			if (!empty($_POST['surname'] && $_POST['name']) || $_POST['alias']) {
				$addArtist = $targetArtist->synchroDb();
				header('Location:zoom_artist.php?artist='.$addArtist);
			}
			else{
				$actionResultat = '<div class="alert alert-danger alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Erreur</strong> PROUT
					</div>';
			}



			// 	$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-added">
			// 		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			// 		<strong>Félicitation</strong> L\'artiste a bien été ajouté.
			// 		</div>';
			// }
			// else{
			// 	$actionResultat = '<div class="alert alert-danger alert-dismissable">
			// 		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			// 		<strong>Erreur</strong> L\'artiste n\'a pas été ajouté.
			// 		</div>';
			
	};
}


$locationTitle = isset($targetArtist)?$targetArtist->getName().' '.$targetArtist->getSurname():'Ajouter un artiste';

include('header.php');


 ?>


<div class="row" id="alert-area">
	<?= !empty($actionResultat)?$actionResultat:''; ?>
</div>


 <div class="row">

	<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

		<div class="row">

			<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row" id="alert-area">
					
				</div>
					<div>
						<h3>1 - Informations générales</h3>

				<?php
				if (isset($targetArtist)) {
					$targetArtist->formInfos($_SERVER['PHP_SELF'],'Modifier');
				}
				else{
					$artist = new Artist();
					$artist->formInfos($_SERVER['PHP_SELF'],'Créer');
				}

				?>
					</div>

					<div class="formText_formPhoto">
						<h3>2 - Textes et photos</h3>
				<?php

					if (isset($targetArtist) && $targetArtist->getVisible()==TRUE){	
					$targetArtist->formText($_SERVER['PHP_SELF'], 'Créer');
					$targetArtist->formPhoto($_SERVER['PHP_SELF'], 'Créer');
					}
					else{
					$createArtist = new Artist();
					$createArtist->formText($_SERVER['PHP_SELF'], 'Créer');
					$createArtist->formPhoto($_SERVER['PHP_SELF'], 'Créer');	
					}
						

					// else{
						// $targetArtist->formText($_SERVER['PHP_SELF'], 'Créer');
						// $targetArtist->formPhoto($_SERVER['PHP_SELF'], 'Créer');
					// }
						// if ($targetArtist->getName() || $targetArtist->getAlias()){
						// 	$artist = $targetArtist->getVisible() == TRUE;

				?>
					</div>
				<?php 
					
				 ?>
			</section>
			
		</div>
		
	</div>
	
</div>

<?php
include('footer.php');