<?php

require_once('classes/user.php');
require_once('classes/artist.php');
require_once('classes/artist_textual_content.php');
require_once('classes/artwork.php');
require_once('classes/artwork_textual_content.php');
require_once('classes/artwork_visual.php');
require_once('classes/artwork_additional.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
require_once('classes/event.php');
require_once('includes/include.php');

// INITIALISE UN OBJET ARTWORK SI ID EN GET
if (isset($_GET['artwork'])) {
	$targetArtwork = new Artwork($_GET['artwork']);
	if (!empty($targetArtwork->getId())) {
		$parentArtist = new Artist($targetArtwork->getArtistId());
	}
}

$currentExhibit = Exhibit::currentExhibit();


if (isset($_POST['targetId']) && isset($_POST['action']) ) {
	$targetArtwork = new Artwork($_POST['targetId']);
	//PUBLICATION D'UNE OEUVRE MASQUEE
	if($_POST['action'] == 'publish'){
		$publish = $targetArtwork->publishArtwork();
		if ($publish) {
			$actionResultat = '<div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Félicitation,</strong> L\'oeuvre '.$targetArtwork->getTitle().' est de nouveau visible.
				</div>';
		}else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> L\'oeuvre '.$targetArtwork->getTitle().' est toujours masquée.
			</div>';	
		}
	}
	//SUPPRESSION DEFINITIVE D'UNE OEUVRE MASQUEE
	elseif($_POST['action'] == 'delete-artwork'){
		$check = $currentUser->passwordCheck($_POST['password']);
		if ($check) {
			$delete = $targetArtwork->deleteExhibit();
			if ($delete) {
				header('Location:'.URL_ADMIN.'artwork_management.php');
			}
			else{
				$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur !</strong> L\'oeuvre '.$targetArtwork->getTitle().' n\'a pas été supprimée.
				</div>';
			}
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Votre mot de passe est incorrect.</strong> Vous ne pouvez pas supprimer l\'oeuvre '.$targetArtwork->getTitle().'.
			</div>';
		}
	}
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


if(isset($_POST['characteristicFrench']) && isset($_POST['mainFrench']) ) {
	//UPDATE DES TEXTES
	$targetArtwork = new Artwork($_POST['id']);

	if (!empty($targetArtwork->getTextualContent())) {
		$frenchCharacteristic = new ArtworkFrenchCharacteristic($targetArtwork->getFrenchCharacteristic()->getId());
		$frenchCharacteristic->setContent($_POST['characteristicFrench']);
		$frenchMain = new ArtworkFrenchMain($targetArtwork->getFrenchMain()->getId());
		$frenchMain->setContent($_POST['mainFrench']);

		$englishCharacteristic = new ArtworkEnglishCharacteristic($targetArtwork->getEnglishCharacteristic()->getId());
		$englishCharacteristic->setContent($_POST['characteristicEnglish']);
		$englishMain = new ArtworkEnglishMain($targetArtwork->getEnglishMain()->getId());
		$englishMain->setContent($_POST['mainEnglish']);

		$germanCharacteristic = new ArtworkGermanCharacteristic($targetArtwork->getGermanCharacteristic()->getId());
		$germanCharacteristic->setContent($_POST['characteristicGerman']);
		$germanMain = new ArtworkGermanMain($targetArtwork->getGermanMain()->getId());
		$germanMain->setContent($_POST['mainGerman']);

		$russianCharacteristic = new ArtworkRussianCharacteristic($targetArtwork->getRussianCharacteristic()->getId());
		$russianCharacteristic->setContent($_POST['characteristicRussian']);
		$russianMain = new ArtworkRussianMain($targetArtwork->getRussianMain()->getId());
		$russianMain->setContent($_POST['mainRussian']);

		$chineseCharacteristic = new ArtworkChineseCharacteristic($targetArtwork->getChineseCharacteristic()->getId());
		$chineseCharacteristic->setContent($_POST['characteristicChinese']);
		$chineseMain = new ArtworkChineseMain($targetArtwork->getChineseMain()->getId());
		$chineseMain->setContent($_POST['mainChinese']);

		$valideUpdate = 0;
		$textualContent = array();
		array_push($textualContent, $frenchCharacteristic, $frenchMain, $englishCharacteristic, $englishMain, $germanCharacteristic, $germanMain, $russianCharacteristic, $russianMain, $chineseCharacteristic, $chineseMain);
		foreach ($textualContent as $tc) {
			$update = $tc->synchroDb();
			if ($update) {
				$valideUpdate ++;
			}
		}
		if($valideUpdate == 10){
			$actionResultat = '<div class="alert alert-success alert-dismissable" id="update-exhibit-text">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Les textes d\'accompagnement ont bien été mis à jour.</strong>
			</div>';
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> Les textes d\'accompagnement n\'ont pas été mis à jours.
			</div>';	
		}
	}
	//INSERT DES TEXTES
	else{
		$frenchCharacteristic = new ArtworkFrenchCharacteristic();
		$frenchCharacteristic->setContent($_POST['characteristicFrench']);
		$frenchMain = new ArtworkFrenchMain();
		$frenchMain->setContent($_POST['mainFrench']);

		$englishCharacteristic = new ArtworkEnglishCharacteristic();
		$englishCharacteristic->setContent($_POST['characteristicEnglish']);
		$englishMain = new ArtworkEnglishMain();
		$englishMain->setContent($_POST['mainEnglish']);

		$germanCharacteristic = new ArtworkGermanCharacteristic();
		$germanCharacteristic->setContent($_POST['characteristicGerman']);
		$germanMain = new ArtworkGermanMain();
		$germanMain->setContent($_POST['mainGerman']);

		$russianCharacteristic = new ArtworkRussianCharacteristic();
		$russianCharacteristic->setContent($_POST['characteristicRussian']);
		$russianMain = new ArtworkRussianMain();
		$russianMain->setContent($_POST['mainRussian']);

		$chineseCharacteristic = new ArtworkChineseCharacteristic();
		$chineseCharacteristic->setContent($_POST['characteristicChinese']);
		$chineseMain = new ArtworkChineseMain();
		$chineseMain->setContent($_POST['mainChinese']);

		$valideInsert = 0;
		$textualContent = array();
		array_push($textualContent, $frenchCharacteristic, $frenchMain, $englishCharacteristic, $englishMain, $germanCharacteristic, $germanMain, $russianCharacteristic, $russianMain, $chineseCharacteristic, $chineseMain);
		foreach ($textualContent as $tc) {
			$tc->setArtworkId($targetArtwork->getId());
			$insert = $tc->synchroDb();
			if ($insert) {
				$valideInsert ++;
			};
		}
		if($valideInsert == 10){
			$actionResultat = '<div class="alert alert-success alert-dismissable" id="insert-exhibit-text">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Les textes d\'accompagnement ont bien été enregistré.</strong>
			</div>';
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> Les textes d\'accompagnement n\'ont pas été ajouté.
			</div>';	
		}
	}
}


$locationTitle = isset($targetArtwork)?$targetArtwork->getTitle():'Ajouter une oeuvre';
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
		<a href="<?= URL_ROOT ?>artwork.php?exhibit=<?= $currentExhibit[0]->getId() ?>&id=<?= $targetArtwork->getId();?>" class="btn btn-default btn-custom btn-lg" role="button" target="_blank"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
		<a href="<?= URL_ADMIN ?>artist_zoom.php?artist=<?= $parentArtist->getId();?>" class="btn btn-default btn-custom" role="button"><span class="fa fa-paint-brush"></span> Voir la fiche artiste</a>
	</div>
<?php
	}
	else{
?>
	<div class="hidden-lg hidden-sm btn-area-row">
		<button class="btn btn-default btn-custom btn-lg publish-artwork" role="button" data-id="<?= $targetArtwork->getId(); ?>" ><span class="fa fa-eye"></span> Publier l'oeuvre</button>
		<button class="btn btn-default btn-custom btn-lg delete-artwork" role="button" data-id="<?= $targetArtwork->getId(); ?>" ><span class="fa fa-trash"></span> Supprimer définitivement l'oeuvre</button>
	</div>	
<?php
	}
}
?>
</div>

<div class="col-xs-12">
	<div class="row" id="alert-area">
		<?= !empty($actionResultat)?$actionResultat:''; ?>
	</div>
</div>

<div class="col-lg-9 col-md-12 col-sm-9 col-xs-12">
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
				<div class="div-minus">
					<h3>Etape 2 : Textes d'accompagnement</h3>
					<div id="formTextArea">
					<?php
						if (isset($targetArtwork)) {
							if (!empty($targetArtwork->getTextualContent())) {
								$targetArtwork->formText($_SERVER['PHP_SELF'].'?artwork='.$targetArtwork->getId(),'Modifier');
							}
							else{
								$targetArtwork->formText($_SERVER['PHP_SELF'].'?artwork='.$targetArtwork->getId(),'Ajouter');
							}
						}
						else{
							$newArtwork = new Artwork();
							$newArtwork->formText('','Ajouter');
						}
					?>
					<div id="loading-svg"><img src="<?= URL_IMAGES ?>ripple.svg"></div>
					</div>
				</div>
			</section>
			<?php
			if ( isset($targetArtwork) && !empty($targetArtwork->getId())) {
			?>
			<section>
				<h2>Etape 3 : Visuels</h2>
				<h4>Visuels principaux</h4>
				<div id="artwork-main-visual">
					<div>
						<p>Visuel 1</p>
						<?php
							if (isset($targetArtwork) && !empty($targetArtwork)) {
								$targetArtwork->formMainPictures('one');
							}
							else{
								$newArtwork = new Artwork(); 
								$newArtwork->formMainPictures('one') ;
							}
						?>
					</div>
					<div>
						<p>Visuel 2</p>
						<?php
							if (isset($targetArtwork) && !empty($targetArtwork)) {
								$targetArtwork->formMainPictures('two');
							}
							else{
								$newArtwork = new Artwork(); 
								$newArtwork->formMainPictures('two') ;
							}
						?>
					</div>
					<div>
						<p>Visuel 3</p>
						<?php
							if (isset($targetArtwork) && !empty($targetArtwork)) {
								$targetArtwork->formMainPictures('three');
							}
							else{
								$newArtwork = new Artwork(); 
								$newArtwork->formMainPictures('three') ;
							}
						?>
					</div>
				</div>
				<div id="artwork-secondary-visual">
				<h4>Visuels annexes</h4>

				    <div class="row preview-gallery">
				    	<?php
				    		if (isset($targetArtwork) && !empty($targetArtwork->getId())) {
				    			$visualList =$targetArtwork->getVisual();
				    			foreach ($visualList as $v) {
				    				if ($v->getDisplayOrder() !== '1' && $v->getDisplayOrder() != '2' && $v->getDisplayOrder() != '3' ) {
				    					?>
			    						<div class="col-sm-3">
			    							<div class="thumbnail">
				    							<div class="img-container">
				    								<img src="<?= URL_IMAGES.$v->getTarget(); ?>">
				    							</div>
				    							<div class="caption" data-artwork="<?= !empty($v->getArtworkId())?$v->getArtworkId():''; ?>" data-visual="<?= !empty($v->getId())?$v->getId():''; ?>">
				    								<textarea name="legend" disabled placeholder="Légende"><?= !empty($v->getLegend())?$v->getLegend():''; ?></textarea>
				    								<p class="action-area clearfix">
				    									<button type="button" class="delete-visual-annexe btn btn-danger pull-left" disabled><span class="fa fa-trash"></span></button>
				    									<button type="button" class="update-visual-annexe btn btn-default pull-right" disabled><span class="fa fa-save"></span></button>
				    								</p>

				    							</div>
			    							</div>
			    						</div>
				    					<?php
				    				}
				    			}
				    		}
    					?>
    				</div>
    			<!-- DROPZONE POUR L'AJOUT DE VISUELS ANNEXES -->
				<form action="<?= URL_ADMIN ?>picture_process.php" method="POST" id="picturesUpload" class="dropzone">
					<div class="fallback">
	    				<input name="image" type="file" />
	  				</div>
					<input type="hidden" name="artworkId" value="<?= isset($targetArtwork) && !empty($targetArtwork->getId())?$targetArtwork->getId():''; ?>">
					<input type="hidden" name="action" value="uploadPictures">
				</form>

				</div>
			</section>
			<section>
				<div id="artwork-additionnal-content">
				<h2>Etape 4 : Documents additionnels</h2>

				    <div class="row preview-list">
					<?php
						if (isset($targetArtwork) && !empty($targetArtwork->getId())) {
				    		$additionalList =$targetArtwork->getAdditional();
				    	?>						    
				    	<div class="col-sm-4">
				    		<h4>PDF :</h4>
				    		<ul id="add-pdf">
				    		<?php
				    			foreach ($additionalList as $add) {
				    				if ($add->getFormat() == 'pdf') {
				    					?>
				    					<li class="clearfix">
				    						<input type="text" name="add-name" class="form-control" value="<?= $add->getName() ?>" readonly>
				    						<div class="btn-group pull-right" data-addId="<?= $add->getId() ?>">
				    							<a href="<?= URL_IMAGES.$add->getTarget() ?>" target="_blank" class="btn btn-default"><span class="fa fa-eye"></span></a>
												<button type="button" class="btn btn-default edit-add"><span class="fa fa-pencil"></button>
												<button type="button" class="btn btn-danger delete-add"><span class="fa fa-trash"></button>
											</div>
				    					</li>
				    					<?php
				    				}
				    			}
				    		?>
				    		</ul>
				    	</div>
				    	<div class="col-sm-4">
				    		<h4>Vidéo / Son :</h4>
				    		<ul id="add-media">
				    		<?php
				    			foreach ($additionalList as $add) {
				    				if ($add->getFormat() != 'pdf' && $add->getFormat() != 'link' ) {
				    					?>
				    					<li class="clearfix">
				    						<input type="text" name="add-name" class="form-control" value="<?= $add->getName() ?>" readonly>
				    						<div class="btn-group pull-right" data-addId="<?= $add->getId() ?>">
				    							<a href="<?= $add->getTarget() ?>" target="_blank" class="btn btn-default"><span class="fa fa-eye"></span></a>
												<button type="button" class="btn btn-default edit-add"><span class="fa fa-pencil"></button>
												<button type="button" class="btn btn-danger delete-add"><span class="fa fa-trash"></button>
											</div>
				    					</li>
				    					<?php
				    				}
				    			}
				    		?>
				    		</ul>
				    	</div>
				    	<div class="col-sm-4">
				    		<h4>Liens :</h4>
				    		<ul id="add-link" data-artwork="<?= $targetArtwork->getId(); ?>" >
				    		<?php
				    			foreach ($additionalList as $add) {
				    				if ($add->getFormat() == 'link' ) {
				    					?>
				    					<li class="clearfix">
				    						<input type="text" name="add-name" class="form-control" value="<?= $add->getName() ?>" readonly>
				    						<input type="text" name="add-target" class="form-control" value="<?= $add->getTarget() ?>" readonly>
				    						<div class="btn-group pull-right" data-addId="<?= $add->getId() ?>">
				    							<a href="<?= $add->getTarget() ?>" target="_blank" class="btn btn-default"><span class="fa fa-eye"></span></a>
												<button type="button" class="btn btn-default edit-add"><span class="fa fa-pencil"></button>
												<button type="button" class="btn btn-danger delete-add"><span class="fa fa-trash"></button>
											</div>
				    					</li>
				    					<?php
				    				}
				    			}
				    		?>
				    			<li class="clearfix">
				    				<input type="text" name="add-name" class="form-control" placeholder="Titre du lien">
				    				<input type="text" name="add-target" class="form-control" placeholder="Cible du lien">
				    				<button type="button" class="btn btn-default add-link pull-right"><span class="fa fa-plus"></button>
				    			</li>
				    		</ul>
				    	</div>
				    <?php
				    		
				    	}
    				?>
    				</div>
    			<!-- DROPZONE POUR L'AJOUT DE DOCUMENTS ANNEXES -->	
				<form action="<?= URL_ADMIN ?>additional_content.php" method="POST" id="filesUpload" class="dropzone">
					<div class="fallback">
	    				<input name="file" type="file" />
	  				</div>
					<input type="hidden" name="artworkId" value="<?= isset($targetArtwork) && !empty($targetArtwork->getId())?$targetArtwork->getId():''; ?>">
					<input type="hidden" name="action" value="uploadFiles">
				</form>

				</div>
			</section>
			<?php
			}
			?>
		</div>
	</div>
</div>

<div class="col-lg-3 col-md-12 col-sm-3 col-xs-12">
	<div class="row">
		<div class="col-xs-12">
		<?php
			if (isset($targetArtwork)){
				if ($targetArtwork->getVisible() == TRUE) {
		?>
			<div class="hidden-md hidden-xs btn-area-col">
				<a href="<?= URL_ROOT ?>artwork.php?exhibit=<?= $currentExhibit[0]->getId() ?>&id=<?= $targetArtwork->getId();?>" class="btn btn-default btn-custom" role="button" target="_blank"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
				<a href="<?= URL_ADMIN ?>artist_zoom.php?artist=<?= $parentArtist->getId();?>" class="btn btn-default btn-custom" role="button"><span class="fa fa-paint-brush"></span> Voir la fiche artiste</a>
			</div>
		<?php
			}
			else{
		?>
			<div class="hidden-md hidden-xs btn-area-col">
				<button class="btn btn-default btn-custom publish-artwork" role="button" data-id="<?= $targetArtwork->getId(); ?>" ><span class="fa fa-eye"></span> Publier l'oeuvre</button>
				<button class="btn btn-default btn-custom delete-artwork" role="button" data-id="<?= $targetArtwork->getId(); ?>" ><span class="fa fa-trash"></span> Supprimer définitivement l'oeuvre</button>
			</div>	
		<?php
			}
		}
		?>
		</div>
		<div class="col-xs-12">
			<section id="artwork-display">
				<h2>Exposée dans:</h2>
				<ul>
					<?php
					if(isset($targetArtwork) && !empty($targetArtwork->getId())){
						$exhibit = $targetArtwork->getExhibit();
						foreach ($exhibit as $ex) {
							?>
							<li <?= $ex->getEndDate() < date('Y-m-d')?'class="exhibit-passed"':'' ; ?> >
								<h3><?= $ex->getTitle() ?></h3>
								<p><?= dateFormat($ex->getBeginDate()) ?> > <?= dateFormat($ex->getEndDate()) ?></p>
								<?php
									if ($ex->getEndDate() > date('Y-m-d')) {
										if (empty($targetArtwork->getQrCode())) {
											?>
											<div class="qrcode-area text-center">
												<div id="qrcode"><img src="" /></div>
												<button type="button" class="btn btn-custom generateCode" data-artwork="<?= $targetArtwork->getId() ?>" data-picture="<?= !empty($targetArtwork->getPictureOne())?$targetArtwork->getPictureOne()->getTarget():'' ?>" data-exhibit="<?= $ex->getId() ?>"><span class="fa fa-qrcode"></span>Générer QR Code</button>
											</div>
											<?php
										}
										else{
											?>
											<div class="qrcode-area text-center">
												<div id="qrcode"><img src="<?= URL_IMAGES ?>artwork/<?= $targetArtwork->getId() ?>/<?= $targetArtwork->getQrCode() ?>" /></div>
												<a type="button" class="btn btn-custom saveCode" href="<?= URL_IMAGES ?>artwork/<?= $targetArtwork->getId() ?>/<?= $targetArtwork->getQrCode() ?>" download><span class="fa fa-qrcode"></span>Télécharger le QR Code</a>
											</div>
											<?php
										}
									}
								?>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</section>
			<section>
				<h2>Du même artiste :</h2>
					<ul>
					<?php
					if (isset($targetArtwork) && !empty($targetArtwork->getId())) {
						$listArtwork = $parentArtist->getArtwork();
						foreach ($listArtwork as $artwork) {
							?>
							<li><a href="<?= URL_ADMIN ?>artwork_zoom.php?artwork=<?= $artwork->getId() ?>"><h4><span class="fa fa-eye"></span> <?= $artwork->getTitle(); ?></h4></a></li>
							<?php
						}
					}
					?>
					</ul>
			</section>
		</div>
	</div>
</div>

<?php
	}
include('footer.php');
