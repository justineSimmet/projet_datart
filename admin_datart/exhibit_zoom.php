<?php

require_once('classes/user.php');
require_once('classes/artist.php');
require_once('classes/exhibit_textual_content.php');
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

/************************************************************************************************
**
** Actions sur les expositions masquées /visible == FALSE/
** Permet leur publication ou leur suppression définitive 
**
************************************************************************************************/

if (isset($_POST['targetId']) && isset($_POST['action']) ) {
	if($_POST['action'] == 'publish'){
		$targetExhibit = new Exhibit($_POST['targetId']);
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
	elseif ($_POST['action'] == 'delete-event') {
		$targetEvent = new Event($_POST['targetId']);
		$check = $currentUser->passwordCheck($_POST['password']);
		if ($check) {
			$delete = $targetEvent->deleteEvent();
			if ($delete) {
				header('Location:exhibit_zoom.php?exhibit='.$targetExhibit->getId());
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
			$actionResultat = '<div class="alert alert-success alert-dismissable" id="insert-exhibit-text">>
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
		$newEvent->setExhibitId($_POST['id-exhibit']);
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


/************************************************************************************************
**
** MANIPULATION DES ARTISTES LIES A L'EXPOSITION
** Ajoute ou modifie des expositions en base de donnée
**
************************************************************************************************/
if (isset($_POST['actionLink']) && $_POST['actionLink'] == 'addArtist') {
	$artistExposed = $targetExhibit->linkExposedArtist($_POST['artistId']);
	if ($artistExposed) {
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
	                <form action="<?= isset($targetExhibit)?$_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId():''; ?>" method="POST">
		                <label for="password">Mot de passe :</label>
		                <input type="password" name="password" placeholder="Votre mot de passe"  required />
		                <input type="hidden" name="action" value="delete-exhibit" />
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
	        	<div class="modal-body_test">
	        		<p> Vous êtes sur le point de supprimer <strong>définitivement</strong> l'événement <?= isset($targetEvent)?$targetEvent->getName():''; ?>. </p>
	                <p> Pour confirmer cette action, merci de saisir votre mot de passe</p>
	                <form action="<?= isset($targetExhibit)?$_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId():''; ?>" method="POST">
		                <label for="password">Mot de passe :</label>
		                <input type="password" name="password" placeholder="Votre mot de passe" required />
		                <input type="hidden" name="action" value="delete-event" />
		                <input type="hidden" value="<?= isset($targetEvent)?$targetEvent->getId():';' ?>" name="targetId">
		                <input type="submit" value="Supprimer" />
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
		  <a href="index.php" class="btn btn-default" role="button">Retour au tableau de bord</a>
		  <a href="exhibit_management.php" class="btn btn-default" role="button">Retour aux expositions</a>
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
		if ($targetExhibit->getVisible() == TRUE) {
?>
	<div class="hidden-lg hidden-sm btn-area-row">
		<a href="#" class="btn btn-default btn-custom btn-lg" role="button"><span class="fa fa-cubes"></span> Placer les oeuvres</a>
		<a href="#" class="btn btn-default btn-custom btn-lg" role="button"><span class="fa fa-file-text"></span> Dossier technique</a>
		<a href="#" class="btn btn-default btn-custom btn-lg" role="button"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
	</div>
<?php
	}
	else{
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
					<h3>Textes d'accompagnement :</h3>
					<div id="formTextArea">
					<?php
						if (isset($targetExhibit)) {
							if (!empty($targetExhibit->getTextualContent())) {
								$targetExhibit->formText($_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId() ,'Modifier');
							}
							else{
								$targetExhibit->formText($_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId(), 'Ajouter');
							}
						}
						else{
							$newExhibit = new Exhibit();
							$newExhibit->formText('','Ajouter');
						}
					?>
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
						<h2>Etape 2 : Artistes exposés</h2>
						<div class="row" id="exhibitLinkedArtist">
							<div class="col-sm-6">
								<h3>Artistes disponibles</h3>
								<ul id="recordedArtists">
									<?php
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
									?>
								</ul>
							</div>
							<div class="col-sm-6">
								<h3>Artistes associés</h3>
								<ul id="selectedArtists">
								<?php
									if(isset($targetExhibit) && !empty($targetExhibit->getId())){
										foreach ($selectedArtists as $ra) {
										?>
										<li data-artistId="<?= $ra->getId(); ?>"><?= $ra->getIdentity(); ?></li>
									<?php
										}
									}
									?>
								</ul>
							</div>
							<div class="col-xs-12">
								<div class="form-group" >
									<label for="searchArtist">Rechercher un artiste dans les listes :</label>
									<input type="text" name="searchArtist" id="searchArtist" placeholder="abc.." class="form-control" />
								</div>
								<div class="alert alert-danger alert-dismissable">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<strong>Attention !</strong> Retirer un artiste ayant des oeuvres prévues à l'exposition entraînera également le retrait de ses oeuvres.
								</div>
								<div class="form-group clearfix" >
									<button class="btn btn-default pull-right" id="btn-selectedArtist">Enregistrer</button>
								</div>
							</div>
						</div>
					</section>	
				</div>
				<div class="col-md-6 col-xs-12">
					<section>
						<h2>Etape 3 : Les oeuvres</h2>
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
					if ($targetExhibit->getVisible() == TRUE) {
			?>
				<div class="hidden-md hidden-xs btn-area-col">
					<a href="#" class="btn btn-default btn-custom btn-md" role="button"><span class="fa fa-cubes"></span> Placer les oeuvres</a>
					<a href="#" class="btn btn-default btn-custom btn-md" role="button"><span class="fa fa-file-text"></span> Dossier technique</a>
					<a href="#" class="btn btn-default btn-custom btn-md" role="button"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
				</div>
			<?php
				}
				else{
			?>
				<div class="hidden-md hidden-xs btn-area-col">
					<button class="btn btn-default btn-custom btn-md publish-exhibit" role="button" data-id="<?= $targetExhibit->getId(); ?>" ><span class="fa fa-eye"></span> Publier l'exposition</button>
					<button class="btn btn-default btn-custom btn-md delete-exhibit" role="button" data-id="<?= $targetExhibit->getId(); ?>" ><span class="fa fa-trash"></span> Supprimer définitivement l'exposition</button>
				</div>	
			<?php
				}
			}
			?>
		</div>
		<div class="col-xs-12" id="exhibitEvent">
			<h2 class="col-xs-12">Vie de l'exposition</h2>
			<div id="alert-area-event">
				<?= !empty($actionResultatEvent)?$actionResultatEvent:''; ?>
			</div>
			<section>
				<div class="row">
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-6">
						<h3>Evénements enregistrés : </h3>
						<div class="event-list">
							<ul>
								<?php
								if (isset($targetExhibit)) {
								foreach ($targetExhibit->event as $event) {
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
									echo '<input type="hidden" id="begin_date" value="'.$targetExhibit->getBeginDate().'" />';
									echo '<input type="hidden" id="end_date" value="'.$targetExhibit->getEndDate().'" />';
								}
								else{
									$newEvent = new Event();
									$newEvent->formEvent($_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId(), 'Ajouter', $targetExhibit->getId());
									echo '<input type="hidden" id="begin_date" value="'.$targetExhibit->getBeginDate().'" />';
									echo '<input type="hidden" id="end_date" value="'.$targetExhibit->getEndDate().'" />';
								}
							}
							else{
								$newEvent = new Event();
								$newEvent->formEvent('', 'Ajouter','');
								echo '<input type="hidden" id="begin_date" value="'.$targetExhibit->getBeginDate().'" />';
								echo '<input type="hidden" id="end_date" value="'.$targetExhibit->getEndDate().'" />';	
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

