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
if (isset($_GET['exhibit'])) {
	$targetExhibit = new Exhibit($_GET['exhibit']);
}

// INITIALISE UN OBJET EVENT
if (isset($_POST['targetEvent'])) {
	$targetEvent = new Event($_POST['targetEvent']);
}


/************************************************************************************************
**
** Insertion ou update en base de donnée après un submit du formulaire 1 (Infos générale)
**
************************************************************************************************/

if (isset($_POST['title'])) {
	if (empty($_POST['id'])) {
		$newExhibit = new Exhibit();
		$newExhibit->setTitle($_POST['title']);
		$newExhibit->setBeginDate(dateFormat($_POST['begin_date']));
		$newExhibit->setEndDate(dateFormat($_POST['end_date']));
		$newExhibit->setPublicOpening($_POST['public_opening']);
		$create = $newExhibit->synchroDb();
		if ($create) {
			// CREATION DES EVENEMENTS DE L'EXPO
			// Si l'insert est réussi, les dates de début et de fin de l'expo sont transformées
			// en objet Event avec des horaires génériques et inscrits en base de donnée
			// Ouverture
			$openEvent = new Event();
			$openEvent->setExhibitId($create);
			$openEvent->setName('Début');
			$openEvent->setEventDate($newExhibit->getBeginDate());
			// Fermeture
			$closeEvent = new Event();
			$closeEvent->setExhibitId($create);
			$closeEvent->setName('Fin');
			$closeEvent->setEventDate($newExhibit->getEndDate());
			// Insertion des événements en base de donnée
			$openEvent->synchroDb();
			$closeEvent->synchroDb();

			// Redirection vers la fiche Expo créé
			header('Location:'.URL_ADMIN.'exhibit_zoom.php?exhibit='.$create);
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
		$targetExhibit->setBeginDate(dateFormat($_POST['begin_date']));
		$targetExhibit->setEndDate(dateFormat($_POST['end_date']));
		$targetExhibit->setPublicOpening($_POST['public_opening']);
		$update = $targetExhibit->synchroDb();
		if ($update) {
			// UPDATE DES EVENEMENTS DE L'EXPO
			// Si l'update est réussi, je récupère les événements de début et de fin et update
			// leurs donnée avec des horaires génériques et inscrits en base de donnée
			// Ouverture
			$openEvent = new Event($targetExhibit->getOpenEvent());
			$openEvent->setEventDate($targetExhibit->getBeginDate());
			// Fermeture
			$closeEvent = new Event($targetExhibit->getCloseEvent());
			$closeEvent->setEventDate($targetExhibit->getEndDate());
			// Update des événements en base de donnée
			$openEvent->synchroDb();
			$closeEvent->synchroDb();

			$actionResultat = '<div class="alert alert-success alert-dismissable" id="update-exhibit">
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

if (isset($_POST['targetExhibit']) && isset($_POST['action']) ) {
	$targetExhibit = new Exhibit($_POST['targetExhibit']);
	if($_POST['action'] == 'publish'){
		$publish = $targetExhibit->publishExhibit();
		if ($publish) {
			$actionResultat = '<div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Félicitation,</strong> L\'exposition '.$targetExhibit->getTitle().' est de nouveau visible.
				</div>';
		}else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> L\'exposition '.$targetExhibit->getTitle().' est toujours masquée.
			</div>';	
		}
	}
	elseif($_POST['action'] == 'delete-exhibit'){
		$check = $currentUser->passwordCheck($_POST['password']);
		if ($check) {
			$delete = $targetExhibit->deleteExhibit();
			if ($delete) {
				header('Location:'.URL_ADMIN.'exhibit_management.php');
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
	elseif ($_POST['action'] == 'delete-event') {
		$targetEvent = new Event($_POST['targetEvent']);
		$check = $currentUser->passwordCheck($_POST['password']);
		if ($check) {
			$delete = $targetEvent->deleteEvent();
			if ($delete) {
				 header('Location: '.$_SERVER['REQUEST_URI']);
			}
			else{
				$actionResultatEvent = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur !</strong> L\'événement '.$targetEvent->getName().' n\'a pas été supprimée.
				</div>';
			}
		}
		else{
			$actionResultatEvent = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Votre mot de passe est incorrect.</strong> Vous ne pouvez pas supprimer l\'événement '.$targetEvent->getName().'.
			</div>';	
		}
	}
	elseif($_POST['action'] == 'add-artist'){
		if(isset($_POST['artistId'])){
			$artistExposed = $targetExhibit->linkExposedArtist($_POST['artistId']);
			var_dump($artistExposed);
			if ($artistExposed) {
				$reset = $targetExhibit->resetArtistExposed();
				foreach ($_POST['artistId'] as $artist) {
					$targetExhibit->setArtistExposed($artist);
				}
				$actionResultatArtist = '<div id="update-exhibit-artist"><div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong> Les artistes ont bien été lié à l\'exposition.</strong>
				</div></div>';
			}
			else{
				$actionResultatArtist = '<div id="update-exhibit-artist"><div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur !</strong> Les artistes n\'ont pas été lié à l\'exposition.
				</div></div>';
			}
		}
		else{
			$clean = $targetExhibit->cleanArtistExposed();
			if ($clean) {
				$actionResultatArtist = '<div id="update-exhibit-artist"><div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong> La liste des artistes exposés bien été vidée.</strong>
				</div></div>';
			}
			else{
				$actionResultatArtist = '<div id="update-exhibit-artist"><div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur !</strong> La liste des artistes exposés n\'a pas été vidée.
				</div></div>';
			}

		}

	}
	elseif($_POST['action'] == 'add-artwork'){
		if(isset($_POST['artworkId'])){
			$artworkDisplayed = $targetExhibit->linkDisplayedArtwork($_POST['artworkId']);
			if ($artworkDisplayed) {
				$reset = $targetExhibit->resetArtworkDisplayed();
				foreach ($_POST['artworkId'] as $artwork) {
					$targetExhibit->setArtworkDisplayed($artwork);
				}
				$actionResultatArtwork = '<div id="update-exhibit-artwork"><div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong> Les oeuvres ont bien été associées à l\'exposition.</strong>
				</div></div>';
			}
			else{
				$actionResultatArtwork = '<div id="update-exhibit-artwork"><div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur !</strong> Les oeuvres n\'ont pas été associées à l\'exposition.
				</div></div>';
			}
		}
		else{
			$clean = $targetExhibit->cleanArtworkDisplayed();
			if ($clean) {
				$actionResultatArtwork = '<div id="update-exhibit-artwork"><div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong> La liste des oeuvres exposées a bien été vidée.</strong>
				</div></div>';
			}
			else{
				$actionResultatArtwork = '<div id="update-exhibit-artwork"><div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur !</strong> La liste des oeuvres exposées n\'a pas été vidée.
				</div></div>';
			}

		}

	}
}

/************************************************************************************************
**
** Manipulation sur les textes d'accompagnement d'une exposition
** Lorsque le formulaire 2 est validé, enregistre automatiquement l'ensemble des entrées de
** de langues en base de donnée.
**
************************************************************************************************/
if(isset($_POST['categoryFrench']) && isset($_POST['summaryFrench']) ) {
	//UPDATE DES TEXTES
	$targetExhibit = new Exhibit($_POST['id']);

	if (!empty($targetExhibit->getTextualContent())) {
		$frenchCategory = new ExhibitFrenchCategory($targetExhibit->getFrenchCategory()->getId());
		$frenchCategory->setContent($_POST['categoryFrench']);
		$frenchSummary = new ExhibitFrenchSummary($targetExhibit->getFrenchSummary()->getId());
		$frenchSummary->setContent($_POST['summaryFrench']);

		$englishCategory = new ExhibitEnglishCategory($targetExhibit->getEnglishCategory()->getId());
		$englishCategory->setContent($_POST['categoryEnglish']);
		$englishSummary = new ExhibitEnglishSummary($targetExhibit->getEnglishSummary()->getId());
		$englishSummary->setContent($_POST['summaryEnglish']);

		$germanCategory = new ExhibitGermanCategory($targetExhibit->getGermanCategory()->getId());
		$germanCategory->setContent($_POST['categoryGerman']);
		$germanSummary = new ExhibitGermanSummary($targetExhibit->getGermanSummary()->getId());
		$germanSummary->setContent($_POST['summaryGerman']);

		$russianCategory = new ExhibitRussianCategory($targetExhibit->getRussianCategory()->getId());
		$russianCategory->setContent($_POST['categoryRussian']);
		$russianSummary = new ExhibitRussianSummary($targetExhibit->getRussianSummary()->getId());
		$russianSummary->setContent($_POST['summaryRussian']);

		$chineseCategory = new ExhibitChineseCategory($targetExhibit->getChineseCategory()->getId());
		$chineseCategory->setContent($_POST['categoryChinese']);
		$chineseSummary = new ExhibitChineseSummary($targetExhibit->getChineseSummary()->getId());
		$chineseSummary->setContent($_POST['summaryChinese']);

		$valideUpdate = 0;
		$textualContent = array();
		array_push($textualContent, $frenchCategory, $frenchSummary, $englishCategory, $englishSummary, $germanCategory, $germanSummary, $russianCategory, $russianSummary, $chineseCategory, $chineseSummary);
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
		$frenchCategory = new ExhibitFrenchCategory();
		$frenchCategory->setContent($_POST['categoryFrench']);
		$frenchSummary = new ExhibitFrenchSummary();
		$frenchSummary->setContent($_POST['summaryFrench']);

		$englishCategory = new ExhibitEnglishCategory();
		$englishCategory->setContent($_POST['categoryEnglish']);
		$englishSummary = new ExhibitEnglishSummary();
		$englishSummary->setContent($_POST['summaryEnglish']);

		$germanCategory = new ExhibitGermanCategory();
		$germanCategory->setContent($_POST['categoryGerman']);
		$germanSummary = new ExhibitGermanSummary();
		$germanSummary->setContent($_POST['summaryGerman']);

		$russianCategory = new ExhibitRussianCategory();
		$russianCategory->setContent($_POST['categoryRussian']);
		$russianSummary = new ExhibitRussianSummary();
		$russianSummary->setContent($_POST['summaryRussian']);

		$chineseCategory = new ExhibitChineseCategory();
		$chineseCategory->setContent($_POST['categoryChinese']);
		$chineseSummary = new ExhibitChineseSummary();
		$chineseSummary->setContent($_POST['summaryChinese']);

		$valideInsert = 0;
		$textualContent = array();
		array_push($textualContent, $frenchCategory, $frenchSummary, $englishCategory, $englishSummary, $germanCategory, $germanSummary, $russianCategory, $russianSummary, $chineseCategory, $chineseSummary);
		foreach ($textualContent as $tc) {
			$tc->setExhibitId($targetExhibit->getId());
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

/************************************************************************************************
**
** MANIPULATION DES EVENEMENTS DE L'EXPOSITION
** Ajoute ou modifie des expositions en base de donnée
**
************************************************************************************************/

if (isset($_POST['name']) && isset($_POST['date'])) {
	if (empty($_POST['id'])) {
		$newEvent = new Event();
		$newEvent->setExhibitId($_POST['targetExhibit']);
		$newEvent->setName($_POST['name']);
		$newEvent->setDescription($_POST['description']);
		$newEvent->setEventDate(dateFormat($_POST['date']));
		$newEvent->setEventStartTime(timeFormat($_POST['start-time']));
		$insert = $newEvent->synchroDb();
		if ($insert) {
			$actionResultatEvent = '<div class="alert alert-success alert-dismissable" id="insert-exhibit-event">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>L\'événement a bien été enregistré.</strong>
			</div>';
		}
		else{
			$actionResultatEvent = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> L\'événement "'.$newEvent->getName().'" n\'a pas été enregistré.
			</div>';
		}
	}
	else{
		$targetEvent = new Event($_POST['id']);
		$targetEvent->setName($_POST['name']);
		$targetEvent->setDescription($_POST['description']);
		$targetEvent->setEventDate(dateFormat($_POST['date']));
		$targetEvent->setEventStartTime(timeFormat($_POST['start-time']));
		$update = $targetEvent->synchroDb();
		if ($update) {
			$actionResultatEvent = '<div class="alert alert-success alert-dismissable" id="update-exhibit-event">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>L\'événement a bien été modifié.</strong>
			</div>';
		}
		else{
			$actionResultatEvent = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> L\'événement "'.$targetEvent->getName().'" n\'a pas été modifié.
			</div>';
		}
	}
}



$locationTitle = isset($targetExhibit)?$targetExhibit->getTitle():'Ajouter une exposition';

include('header.php');

?>

<!--
************************************************************************************************
	MODAL POUR CONFIRMER LA SUPPRESSION DEFINITIVE D'UNE EXPOSITION
************************************************************************************************
-->
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
	                <form action="<?= isset($targetExhibit)?$_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId():''; ?>" method="POST" class="clearfix">
		                <label for="password">Mot de passe :</label>
		                <input type="password" name="password" placeholder="Votre mot de passe"  required />
		                <input type="hidden" name="action" value="delete-exhibit" />
		                <input type="hidden" value="<?= isset($targetExhibit)?$targetExhibit->getId():';' ?>" name="targetId">
		                <input type="submit" class="btn btn-danger pull-right" value="Supprimer" />
	                </form>
	        	</div>
	        	<div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	            </div>
	        </div>
		</div>
	</div>

<!--
************************************************************************************************
	MODAL POUR CONFIRMER LA SUPPRESSION DEFINITIVE D'UN EVENEMENT
************************************************************************************************
-->
	<div id="deleteEvent" class="modal fade" role="dialog" >
		<div class="modal-dialog">
			<div class="modal-content">
	        	<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal">&times;</button>
	        		<h4 class="modal-title">Attention !</h4>
	        	</div>
	        	<div class="modal-body">
	        		<p> Vous êtes sur le point de supprimer <strong>définitivement</strong> l'événement <?= isset($targetEvent)?$targetEvent->getName():''; ?>. </p>
	                <p> Pour confirmer cette action, merci de saisir votre mot de passe</p>
	                <form action="<?= isset($targetExhibit)?$_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId():''; ?>" method="POST" class="clearfix">
		                <label for="password">Mot de passe :</label>
		                <input type="password" name="password" placeholder="Votre mot de passe" required />
		                <input type="hidden" name="action" value="delete-event" />
		                <input type="hidden" value="<?= isset($targetEvent)?$targetEvent->getId():''; ?>" name="targetEvent">
		                <input type="hidden" value="<?= isset($targetExhibit)?$targetExhibit->getId():''; ?>" name="targetExhibit">
		                <input type="submit" class="btn btn-danger pull-right" value="Supprimer" />
	                </form>
	        	</div>
	        	<div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	            </div>
	        </div>
		</div>
	</div>

<!--
************************************************************************************************
	MESSAGE D'ERREUR si une exposition est masquée et que l'utilisateur n'est pas admin
	Ouvre une condition php selon la situation de l'utilisateur
************************************************************************************************
-->

<?php
	if (isset($targetExhibit) && ($targetExhibit->getVisible() == FALSE && $currentUser->getStatus() == TRUE )) {
?>	
	
	<div class="col-lg-6 col-lg-offset-3 col-md-6 col-lg-offset-3 col-sm-12 col-xs-12">
		<div class="alert alert-warning text-center">
		  <strong>L'exposition que vous souhaitez consulter n'est plus disponible.</strong><br>
		  <a href="<?= URL_ADMIN ?>index.php" class="btn btn-default" role="button">Retour au tableau de bord</a>
		  <a href="<?= URL_ADMIN ?>exhibit_management.php" class="btn btn-default" role="button">Retour aux expositions</a>
		</div>
	</div>

<?php
	}

	else{
?>

<!--
************************************************************************************************
	MENU ADAPTATIF SELON L'ECRAN ET LE TYPE DE CONTENU CIBLE (expo visible/modifiable ou non)
************************************************************************************************
-->
<div class="col-xs-12">
<?php
	if (isset($targetExhibit)){
		if ($targetExhibit->getVisible() == TRUE && $targetExhibit->getEndDate() > date('Y-m-d')) {
?>
	<div class="hidden-lg hidden-sm btn-area-row">
		<a href="<?= URL_ADMIN ?>exhibit_zoning.php?id=<?= $targetExhibit->getId() ?>" target="_blank" class="btn btn-default btn-custom btn-lg" role="button"><span class="fa fa-cubes"></span> Placer les oeuvres</a>
		<a href="<?= URL_ADMIN ?>exhibit_technical_doc.php?id=<?= $targetExhibit->getId() ?>" target="_blank" class="btn btn-default btn-custom btn-lg" role="button"><span class="fa fa-file-text"></span> Dossier technique</a>
		<a href="#" class="btn btn-default btn-custom btn-lg" role="button"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
	</div>
<?php
	}
	elseif($targetExhibit->getVisible() == FALSE && $targetExhibit->getEndDate() > date('Y-m-d')){
?>
	<div class="hidden-lg hidden-sm btn-area-row">
		<button class="btn btn-default btn-custom btn-lg publish-exhibit" role="button" data-id="<?= $targetExhibit->getId(); ?>" ><span class="fa fa-eye"></span> Publier l'exposition</button>
		<button class="btn btn-default btn-custom btn-lg delete-exhibit" role="button" data-id="<?= $targetExhibit->getId(); ?>" ><span class="fa fa-trash"></span> Supprimer définitivement l'exposition</button>
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

<!-- *************************** FORMULAIRE Infos générales *************************** -->
				<div id="exhibitMainInfo">
					<h2>Etape 1 : Informations Générales</h2>
					<?php

						if (isset($targetExhibit)) {
							$targetExhibit->formInfos($_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId(),'Modifier');
						}
						else{
							$newExhibit = new Exhibit();
							$newExhibit->formInfos($_SERVER['PHP_SELF'],'Créer');
						}

					?>
				</div>

<!-- *************************** FORMULAIRE Textes d'accompagnement *************************** -->
				<div class="div-minus">
					<h3>Etape 2 : Textes d'accompagnement</h3>
					<div id="formTextArea">
					<?php
						if (isset($targetExhibit)) {
							if (!empty($targetExhibit->getTextualContent())) {
								$targetExhibit->formText($_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId(),'Modifier');
							}
							else{
								$targetExhibit->formText($_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId(),'Ajouter');
							}
						}
						else{
							$newExhibit = new Exhibit();
							$newExhibit->formText('','Ajouter');
						}
					?>
					<div id="loading-svg"><img src="<?= URL_IMAGES ?>ripple.svg"></div>
					</div>
				</div>
			</section>
		</div>
		<div class="col-sm-12">
			<div class="row">
				<div class="col-md-6 col-xs-12">
					<?php //INITIALISATION DES LISTES
						$recordedArtists = Artist::listArtist();
						if(isset($targetExhibit) && !empty($targetExhibit->getId())){
							$selectedArtists = $targetExhibit->getArtistExposed();
						}
						else{
							$selectedArtists = array();
						}
					?>
					<section>
						<div class="col-sm-12" id="alert-area-artist"> <!-- ZONE DES MESSAGES DE SUCCES OU D'ERREUR -->
							<?= !empty($actionResultatArtist)?$actionResultatArtist:''; ?>
						</div>
						<h2>Etape 3 : Artistes exposés</h2>
						<div class="row" id="exhibitLinkedArtist">
							<div class="col-sm-6">
								<h4>Artistes disponibles</h4>
								<ul <?= (!empty($targetExhibit->getId()) && $targetExhibit->getVisible() == 0) || (!empty($targetExhibit->getId()) && $targetExhibit->getEndDate() < date('Y-m-d'))?'':'id="recordedArtists"'; ?> >
									<?php
									if(isset($targetExhibit) && !empty($targetExhibit->getId())){
										$clone = Artist::compareList($recordedArtists, $selectedArtists);
										if (!empty($clone)) {
											foreach ($recordedArtists as $ra) {
												if (!in_array($ra->getId(), $clone)) {
													?>
													<li data-artistId="<?= $ra->getId(); ?>"><?= $ra->getIdentity(); ?></li> 
													<?php
												}
											}
										}
										else{
											foreach ($recordedArtists as $ra) {
											?>
												<li data-artistId="<?= $ra->getId(); ?>"><?= $ra->getIdentity(); ?></li> 
											<?php
											}
										}
									}
									?>
								</ul>
							</div>
							<div class="col-sm-6">
								<h4>Artistes associés</h4>
								<ul <?= (!empty($targetExhibit->getId()) && $targetExhibit->getVisible() == 0) || (!empty($targetExhibit->getId()) && $targetExhibit->getEndDate() < date('Y-m-d'))?'':'id="selectedArtists"'; ?> >
								<?php
									if(isset($targetExhibit) && !empty($targetExhibit->getId())){
										foreach ($selectedArtists as $ra) {
										?>
										<li data-artistId="<?= $ra->getId(); ?>"><?= $ra->getIdentity(); ?></li>
									<?php
										}
									?>
								</ul>
							</div>
							<div class="col-xs-12">
								<div class="form-group" >
									<label for="searchArtist" class="sr-only">Rechercher un artiste dans les listes :</label>
									<input type="text" name="searchArtist" id="searchArtist" placeholder="Rechercher un artiste par son nom" class="form-control" />
								</div>
								<p class="alert-infos">
									<strong>Attention !</strong> Retirer un artiste ayant des oeuvres prévues à l'exposition entraînera également le retrait de ses oeuvres.
								</p>
								<div class="form-group clearfix" >
									<button class="btn btn-default pull-right <?= !empty($targetExhibit->getId()) && $targetExhibit->getVisible() == 0?'disabled':''; ?><?= !empty($targetExhibit->getId()) && $targetExhibit->getEndDate() < date('Y-m-d')?'disabled':''; ?>" id="btn-selectedArtist" data-exhibitId="<?= $targetExhibit->getId(); ?>" id>Enregistrer</button>
								</div>
							</div>
							<?php 
							}
							?>
						</div>
					</section>	
				</div>
				<div class="col-md-6 col-xs-12">
					<section>
						<div class="col-sm-12" id="alert-area-artwork"> <!-- ZONE DES MESSAGES DE SUCCES OU D'ERREUR -->
							<?= !empty($actionResultatArtwork)?$actionResultatArtwork:''; ?>
						</div>
						<h2>Etape 4 : Les oeuvres</h2>
						<div class="row">
							<div class="col-sm-12">
							<div id="exhibitLinkedArtwork">
							<?php
								if (isset($targetExhibit)) {
								foreach ($targetExhibit->getArtistExposed() as $artist) {
									echo '<h3>'.$artist->getIdentity().'</h3>';
									echo '<div class="form-group form-group-lg">';
									if ( !empty($artist->getArtwork()) ) {
										foreach ($artist->getArtwork() as $artwork) {
											?>
											<label class="checkbox-inline"><input type="checkbox" value="<?= $artwork->getId() ?>" <?= in_array($artwork, $targetExhibit->getArtworkDisplayed())?'checked':''; ?> ><a href="<?= URL_ADMIN ?>artwork_zoom.php?artwork=<?= $artwork->getId()?>"><?= $artwork->getTitle(); ?></a></label>
											<?php
										}
									}
										echo '<p><a href="<?= URL_ADMIN ?>artwork_zoom.php?artist='.$artist->getId().'">Ajouter une oeuvre</a></p>';
										echo '</div>';
								}
								}
							?>
							</div>
							<?php
							 

								if(isset($targetExhibit)){
							?>
								<div class="form-group clearfix" >
									<button class="btn btn-default pull-right <?= !empty($targetExhibit->getId()) && $targetExhibit->getVisible() == 0?'disabled':''; ?><?= !empty($targetExhibit->getId()) && $targetExhibit->getEndDate() < date('Y-m-d')?'disabled':''; ?>" id="btn-selectedArtwork" data-exhibitId="<?= $targetExhibit->getId(); ?>">Enregistrer</button>
								</div>
							<?php
								}
							?>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
</div> <!-- FIN DU CONTAINER CENTRAL -->

<div class="col-lg-3 col-md-12 col-sm-3 col-xs-12">
	<div class="row">
	<!-- *************************** GESTION DES BOUTONS VARIANTE *************************** -->
		<div class="col-xs-12">
			<?php
				if (isset($targetExhibit)){
					if ($targetExhibit->getVisible() == TRUE && $targetExhibit->getEndDate() > date('Y-m-d')) {
			?>
				<div class="hidden-md hidden-xs btn-area-col">
					<a href="<?= URL_ADMIN ?>exhibit_zoning.php?id=<?= $targetExhibit->getId() ?>" target="_blank" class="btn btn-default btn-custom btn-md" role="button"><span class="fa fa-cubes"></span> Placer les oeuvres</a>
					<a href="<?= URL_ADMIN ?>exhibit_technical_doc.php?id=<?= $targetExhibit->getId() ?>" target="_blank" class="btn btn-default btn-custom btn-md" role="button"><span class="fa fa-file-text"></span> Dossier technique</a>
					<a href="#" class="btn btn-default btn-custom btn-md" role="button"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
				</div>
			<?php
				}
				elseif($targetExhibit->getVisible() == FALSE && $targetExhibit->getEndDate() > date('Y-m-d')){
			?>
				<div class="hidden-md hidden-xs btn-area-col">
					<button class="btn btn-default btn-custom btn-md publish-exhibit" role="button" data-id="<?= $targetExhibit->getId(); ?>" ><span class="fa fa-eye"></span> Publier l'exposition</button>
					<button class="btn btn-default btn-custom btn-md delete-exhibit" role="button" data-id="<?= $targetExhibit->getId(); ?>" ><span class="fa fa-trash"></span> Supprimer définitivement l'exposition</button>
				</div>	
			<?php
				}
			}
			?>
			<div id="alert-area-event">
				<?= !empty($actionResultatEvent)?$actionResultatEvent:''; ?>
			</div>
		</div>
		<div class="col-xs-12" id="exhibitEvent">
			<h2 class="col-xs-12">Vie de l'exposition</h2>
			<section>
				<div class="row">
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-6">
						<h3>Evénements enregistrés : </h3>
						<div class="event-list">
							<ul>
								<?php
								if (isset($targetExhibit)) {
								foreach ($targetExhibit->getEvents() as $event) {
								?>
								<li>
									<div class="event-text">
										<p class="event-date">Le <?= dateFormat($event->getEventDate()); ?> <?= empty($event->getEventStartTime()) || $event->getEventStartTime() == '00:00:00' ?'':' à '. timeFormat($event->getEventStartTime()); ?>
										</p>
										<h4><?= $event->getName(); ?></h4>
										<p class="event-description">
											<?= $event->getDescription(); ?>
										</p>
									</div>
									<div class="event-action">
										<?php
										if ($targetExhibit->getEndDate() > date('Y-m-d')) {
											if($event->getName() == 'Début' || $event->getName() == 'Fin'){
												?>
												<div class="btn-group-vertical ">
													<a type="button" class="btn btn-default update-event" data-id="<?= $event->getId();?>"><span class="fa fa-pencil"></span></a>
												</div>
											<?php
											}
											else{
												?>
												<div class="btn-group-vertical ">
													<a type="button" class="btn btn-default update-event" data-id="<?= $event->getId();?>"><span class="fa fa-pencil"></span></a>
													<a type="button" class="btn btn-default delete-event" data-id="<?= $event->getId();?>"><span class="fa fa-trash"></span></a>
												</div>
												<?php	
											}
										}
										?>
									</div>
								</li>
								<?php
									}
								}
								?>
							</ul>
						</div>
					</div>
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-6">
						<h3>Gérer les événements</h3>
						<?php
							if(isset($targetExhibit)){
								if(isset($targetEvent)){
									$targetEvent->formEvent($_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId(), 'Modifier', $targetExhibit->getId());
								}
								else{
									$newEvent = new Event();
									$newEvent->formEvent($_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId(), 'Ajouter', $targetExhibit->getId());
								}
							}
							else{
								$newEvent = new Event();
								$newEvent->formEvent('', 'Ajouter','');
							}
						?>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>

<!-- Fin de la condition de PHP selon le statut de l'utilisateur -->
<?php
	}

include('footer.php');

