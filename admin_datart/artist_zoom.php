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


if (isset($_GET['artist'])) {
	$targetArtist = new Artist($_GET['artist']);
}

$currentExhibit = Exhibit::currentExhibit();

/************************************************************************************************
**
** Insertion ou update en base de donnée après un submit du formulaire 1 (Infos générale)
**
************************************************************************************************/

if (isset($_POST['id'])) {
	

	if(!empty($_POST['id'])) {
		$targetArtist = new Artist($_POST['id']);
		$targetArtist->setSurname($_POST['surname']);
		$targetArtist->setName($_POST['name']);
		$targetArtist->setAlias($_POST['alias']);
		
		if (!empty($_POST['surname'] && $_POST['name']) || $_POST['alias']) {
			$updateArtist = $targetArtist->synchroDb();
		
			if ($updateArtist) {
				$actionResultat = '<div class="alert text-center alert-success alert-dismissable" id="artist-edited">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Félicitation</strong> L\'artiste a bien été modifié. 
				</div>';
			}
			else{
				$actionResultat = '<div class="alert text-center alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur</strong> L\'artiste n\'a pas été modifié.
				</div>';
			}
		}
		else{
			$actionResultat = '<div class="alert text-center alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur</strong> Veuillez renseigner l\'un des champs suivants: Nom + Prénom ou Pseudonyme pour modifier '.$targetArtist->getIdentity().'.
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
				header('Location:artist_zoom.php?artist='.$addArtist);
			}
			else{
				$actionResultat = '<div class="alert text-center alert-danger alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Erreur</strong> '.$targetArtist->getIdentity().' n\'a pas pu être enregistré.
					</div>';
			}

	};
}


if (isset($_POST['targetArtist']) && isset($_POST['action']) ) {
	if($_POST['action'] == 'publish'){
		$targetArtist = new Artist($_POST['targetArtist']);
		$publish = $targetArtist->publishArtist();
		if ($publish) {
			$actionResultat = '<div class="alert text-center alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Félicitation,</strong> L\'artiste '.$targetArtist->getIdentity().' est de nouveau visible.
				</div>';
		}
		else{
			$actionResultat = '<div class="alert text-center alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> L\'artiste '.$targetArtist->getIdentity().' est toujours masqué.
			</div>';	
		}
	}
	elseif($_POST['action'] == 'deleteArtiste'){
		$targetArtist = new Artist($_POST['targetArtist']);
		$check = $currentUser->passwordCheck($_POST['password']);
		if ($check) {
			$delete = $targetArtist->deleteArtist();
			if ($delete) {
				header('Location:'.URL_ADMIN.'artist_management.php');
			}
			else{
				$actionResultat = '<div class="alert text-center alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur !</strong> L\'artiste '.$targetArtist->getIdentity().' n\'a pas été supprimée.
				</div>';
			}
		}
		else{
			$actionResultat = '<div class="alert text-center alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Votre mot de passe est incorrect.</strong> Vous ne pouvez pas supprimer l\'artiste '.$targetArtist->getIdentity().'.
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
if(isset($_POST['biographyFrench']) && isset($_POST['noteFrench']) ) {

		$targetArtist = new Artist($_POST['artistId']);

	if (!empty($targetArtist->getTextualContent())) {
		$frenchBiography = new ArtistFrenchBiography($targetArtist->getFrenchBiography()->getId());
		$frenchBiography->setContent($_POST['biographyFrench']);
		$frenchNote = new ArtistFrenchNote($targetArtist->getFrenchNote()->getId());
		$frenchNote->setContent($_POST['noteFrench']);

		$englishBiography = new ArtistEnglishBiography($targetArtist->getEnglishBiography()->getId());
		$englishBiography->setContent($_POST['biographyEnglish']);
		$englishNote = new ArtistEnglishNote($targetArtist->getEnglishNote()->getId());
		$englishNote->setContent($_POST['noteEnglish']);

		$germanBiography = new ArtistGermanBiography($targetArtist->getGermanBiography()->getId());
		$germanBiography->setContent($_POST['biographyGerman']);
		$germanNote = new ArtistGermanNote($targetArtist->getGermanNote()->getId());
		$germanNote->setContent($_POST['noteGerman']);		

		$russianBiography = new ArtistRussianBiography($targetArtist->getRussianBiography()->getId());
		$russianBiography->setContent($_POST['biographyRussian']);
		$russianNote = new ArtistRussianNote($targetArtist->getRussianNote()->getId());
		$russianNote->setContent($_POST['noteRussian']);

		$chineseBiography = new ArtistChineseBiography($targetArtist->getChineseBiography()->getId());
		$chineseBiography->setContent($_POST['biographyChinese']);
		$chineseNote = new ArtistChineseNote($targetArtist->getChineseNote()->getId());
		$chineseNote->setContent($_POST['noteChinese']);

		$valideUpdate = 0;
		$textualContent = array();
		array_push($textualContent, $frenchBiography, $frenchNote, $englishBiography, $englishNote, $germanBiography, $germanNote, $russianBiography, $russianNote, $chineseBiography, $chineseNote);
		foreach ($textualContent as $tc) {
			$update = $tc->synchroDb();
			if ($update) {
				$valideUpdate ++;
			}
		}
		if($valideUpdate == 10){
			$actionResultat = '<div class="alert text-center alert-success alert-dismissable" id="update-artist-text">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Les textes d\'accompagnement ont bien été mis à jour.</strong>
			</div>';
		}
		else{
			$actionResultat = '<div class="alert text-center alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> Les textes d\'accompagnement n\'ont pas été mis à jour.
			</div>';	
		}
	}
	//INSERT DES TEXTES
	else{
	// $targetArtist = new Artist($_POST['artistId']);
		$frenchBiography = new ArtistFrenchBiography();
		$frenchBiography->setContent($_POST['biographyFrench']);
		$frenchNote = new ArtistFrenchNote();
		$frenchNote->setContent($_POST['noteFrench']);
		$englishBiography = new ArtistEnglishBiography();
		$englishBiography->setContent($_POST['biographyEnglish']);
		$englishNote = new ArtistEnglishNote();
		$englishNote->setContent($_POST['noteEnglish']);
		$germanBiography = new ArtistGermanBiography();
		$germanBiography->setContent($_POST['biographyGerman']);
		$germanNote = new ArtistGermanNote();
		$germanNote->setContent($_POST['noteGerman']);
		$russianBiography = new ArtistRussianBiography();
		$russianBiography->setContent($_POST['biographyRussian']);
		$russianNote = new ArtistRussianNote();
		$russianNote->setContent($_POST['noteRussian']);
		$chineseBiography = new ArtistChineseBiography();
		$chineseBiography->setContent($_POST['biographyChinese']);
		$chineseNote = new ArtistChineseNote();
		$chineseNote->setContent($_POST['noteChinese']);
		$valideInsert = 0;
		$textualContent = array();
		array_push($textualContent, $frenchBiography, $frenchNote, $englishBiography, $englishNote, $germanBiography, $germanNote, $russianBiography, $russianNote, $chineseBiography, $chineseNote);
		foreach ($textualContent as $tc) {
			$tc->setArtistId($targetArtist->getId());
			$insert = $tc->synchroDb();
			if ($insert) {
				$valideInsert ++;
			};
		}
		if($valideInsert == 10){
			$actionResultat = '<div class="alert text-center alert-success alert-dismissable" id="insert-artist-text">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Les textes d\'accompagnement ont bien été enregistré.</strong>
			</div>';
		}
		else{
			$actionResultat = '<div class="alert text-center alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> Les textes d\'accompagnement n\'ont pas été ajouté.
			</div>';	
		}
	}
}


/************************************************************************************************
**
Insertion en bdd et recuperation en bdd des images de l'artiste
**
************************************************************************************************/


	if (!empty($_POST['action']) && $_POST['action'] == 'addArtistPicture') {
		$dossierUpload = 'assets/images/artist_portraits/';
		$pictureFile = basename($_FILES['fichier']['name']);
		$tailleMaxi = 2097152;
		$taille = filesize($_FILES['fichier']['tmp_name']);
		$uploadFormat = array('.jpg', '.jpeg');
		$extension = strrchr($_FILES['fichier']['name'], '.'); 
		$namePicture = $targetArtist->getId().''.$extension;
		$targetArtist = new Artist($_POST['artistId']);
		
		if(!in_array($extension, $uploadFormat)) //Si l'extension n'est pas dans le tableau
		{
		     $erreur = 'Vous devez uploader un fichier de type jpg ou jpeg ...';
		}
		if($taille>$tailleMaxi)
		{
		    $erreur = 'Le fichier est trop gros...';
		}
		if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
			{

				if(move_uploaded_file($_FILES['fichier']['tmp_name'], $dossierUpload . $namePicture)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
				{
					$_FILES['fichier']['tmp_name'] = $namePicture;
		          	$targetArtist->setPhotographicPortrait($dossierUpload . $namePicture);
					$update = $targetArtist->synchroPicture();

					if ($update) {
						$actionResultat = '<div class="alert text-center alert-success alert-dismissable" id="artist-edited"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Félicitation</strong> La photo de ' .$targetArtist->getIdentity().' a bien été ajoutée.</div>';
					}
					else{
						$actionResultat = '<div class="alert text-center alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Erreur !</strong>La photo de ' .$targetArtist->getIdentity(). ' n\a pas été ajoutée.</div>';		
					}
				}
	 			else //Sinon (la fonction renvoie FALSE).
					{
						$erreur = 'Echec de l\'upload !';
						$actionResultat = '<div class="alert text-center alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Erreur !</strong> Echec de l\'upload !</div>';
					}
				}
		else
		{
		    $actionResultat = '<div class="alert text-center alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Erreur !</strong>'.$erreur.'</div>';
		}
	}


$locationTitle = isset($targetArtist)?$targetArtist->getIdentity():'Ajouter un artiste';

include('header.php');

 ?>

<!--
************************************************************************************************
	MODAL POUR CONFIRMER LA SUPPRESSION DEFINITIVE D'UN ARTISTE
************************************************************************************************
-->
	<div id="deleteArtist" class="modal fade" role="dialog" >
		<div class="modal-dialog">
			<div class="modal-content">
	        	<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal">&times;</button>
	        		<h4 class="modal-title">Attention !</h4>
	        	</div>
	        	<div class="modal-body">
	        		<p> Vous êtes sur le point de supprimer <strong>définitivement</strong> l'artist <?= isset($targetArtist)?$targetArtist->getIdentity():''; ?>. </p>
	                <p> Pour confirmer cette action, merci de saisir votre mot de passe</p>
	                <form action="<?= isset($targetArtist)?$_SERVER['PHP_SELF'].'?artist='.$targetArtist->getId():''; ?>" method="POST">
		                <label for="password">Mot de passe :</label>
		                <input type="password" name="password" placeholder="Votre mot de passe" required />
		                <input type="hidden" name="action" value="deleteArtiste" />
		                <input type="hidden" value="<?= isset($targetArtist)?$targetArtist->getId():';' ?>" name="targetArtist">
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
	if (isset($targetArtist) && ($targetArtist->getVisible() == FALSE && $currentUser->getStatus() == TRUE )) {
?>	
	
	<div class="col-lg-6 col-lg-offset-3 col-md-6 col-lg-offset-3 col-sm-12 col-xs-12">
		<div class="alert alert-warning text-center">
		  <strong>L'artiste que vous souhaitez consulter n'est plus disponible.</strong><br>
		  <a href="<?= URL_ADMIN ?>index.php" class="btn btn-default" role="button">Retour au tableau de bord</a>
		  <a href="<?= URL_ADMIN ?>artist_management.php" class="btn btn-default" role="button">Retour aux artiste</a>
		</div>
	</div>

<?php
	}
	else{
?>

<div class="col-xs-12">
<?php
	if (isset($targetArtist)){
		if ($targetArtist->getVisible() == TRUE) {
?>
	<div class="hidden-lg hidden-sm btn-area-row">
		<a href="<?= URL_ROOT ?>artist.php?exhibit=<?= $currentExhibit[0]->getId() ?>&id=<?= $targetArtist->getId(); ?>" target="_blank" class="btn btn-default btn-custom btn-lg" role="button" target="_blank"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
		<a href="<?= URL_ADMIN ?>artwork_zoom.php?artist=<?= $targetArtist->getId(); ?>"class="btn btn-default btn-custom btn-lg" role="button"><span class="fa fa-image"></span> Ajouter une oeuvre</a>
	</div>
<?php
	}
	else{
?>
	<div class="hidden-lg hidden-sm btn-area-row">
		<button class="btn btn-default btn-custom btn-lg publish-artist" role="button" data-id="<?= $targetArtist->getId(); ?>" ><span class="fa fa-eye"></span> Publier l'artiste</button>
		<button class="btn btn-default btn-custom btn-lg delete-artist" role="button" data-id="<?= $targetArtist->getId(); ?>" ><span class="fa fa-trash"></span> Supprimer définitivement l'artiste</button>
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

				<div id="artistMainInfo">
					<h2>Etape 1 : Informations Générales</h2>
					<?php
						
						if (isset($targetArtist)) {
							$targetArtist->formInfos($_SERVER['PHP_SELF'].'?artist='.$targetArtist->getId(),'Modifier');
						}
						else{
							$artist = new Artist();
							$artist->formInfos($_SERVER['PHP_SELF'],'Créer');
						}

					?>
				</div>

<!-- *************** FORMULAIRE Textes accompagnemt et photos*************************** -->					

				<div class="div-minus">
					<h3>Etape 2 : Textes d'accompagnement</h3>
					<div id="formTextArea">
					<?php
						if (isset($targetArtist)){	
							if (!empty($targetArtist->getTextualContent())) {
							$targetArtist->formText($_SERVER['PHP_SELF'].'?artist='.$targetArtist->getId(), 'Modifier');
						}
							else{
							$targetArtist->formText($_SERVER['PHP_SELF'].'?artist='.$targetArtist->getId(), 'Ajouter');
							}
						}
						else{
							$newArtist = new Artist();
							$newArtist->formText('','Ajouter');
						}

					?>
						<div id="loading-svg"><img src="<?= URL_IMAGES ?>ripple.svg"></div>
					</div>
					</div>


			</div>
		</div>
	</section>
	<section>
		<div id="formPhotoArea">
			<h3>(optionnel) Portrait de l'artiste</h3>
				<?php
					if (isset($targetArtist)){	
						if (!empty($targetArtist->getPhotographicPortrait())) {
						$targetArtist->formPhoto($_SERVER['PHP_SELF'].'?artist='.$targetArtist->getId(), 'Modifier');	
						}
						else{
						$targetArtist->formPhoto($_SERVER['PHP_SELF'].'?artist='.$targetArtist->getId(), 'Ajouter');
						}
					}
					else{
						$newArtist = new Artist();
						$newArtist->formPhoto('','Ajouter');
					}

				?>
		</div>
	
	</section>
	
</div>
<div class="col-lg-3 col-md-12 col-sm-3 col-xs-12">
	<div class="row">
		<div class="col-xs-12">
			<?php
				if (isset($targetArtist)){
					if ($targetArtist->getVisible() == TRUE) {
			?>
				<div class="hidden-md hidden-xs btn-area-col">
					<a href="<?= URL_ROOT ?>artist.php?exhibit=<?= $currentExhibit[0]->getId() ?>&id=<?= $targetArtist->getId(); ?>" target="_blank"  class="btn btn-default btn-custom" role="button" target="_blank"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
					<a href="<?= URL_ADMIN ?>artwork_zoom.php?artist=<?= $targetArtist->getId(); ?>"class="btn btn-default btn-custom" role="button"><span class="fa fa-image"></span> Ajouter une oeuvre</a>
				</div>
			<?php
				}
				else{
			?>
				<div class="hidden-md hidden-xs btn-area-col">
					<button class="btn btn-default btn-custom publish-artist" role="button" data-id="<?= $targetArtist->getId(); ?>" ><span class="fa fa-eye"></span> Publier l'artiste</button>
					<button class="btn btn-default btn-custom delete-artist" role="button" data-id="<?= $targetArtist->getId(); ?>" ><span class="fa fa-trash"></span> Supprimer</button>
				</div>	
			<?php
				}
			}
			?>
		</div>
		<?php 
			if (isset($targetArtist)) {
				?>
				<div class="col-xs-12">
					<?php
						if (!empty($targetArtist->getPhotographicPortrait())) {
							?>
							<div class="artistPicture">
								<img src="<?= $targetArtist->getPhotographicPortrait() ?>" alt="Portrait : <?= $targetArtist->getIdentity() ?>" />
							</div>
							<?php
						}
					?>
					<section>
						<h2>Exposé dans :</h2>
						<ul>
						<?php
							$listExhibit = $targetArtist->listArtistExhibit();
							foreach ($listExhibit as $exhibit) {
								?>
								<li><a href="<?= URL_ADMIN ?>exhibit_zoom.php?exhibit=<?= $exhibit->getId() ?>">
									<h4><span class="fa fa-eye"></span><?= $exhibit->getTitle() ?></h4>
									<p>du <?= dateFormat($exhibit->getBeginDate()); ?> au <?= dateFormat($exhibit->getEndDate()); ?></p>
								</a></li>
								<?php
							}
						?>
						</ul>
					</section>
					<section>
						<h2>Oeuvres enregistrées :</h2>
						<ul>
						<?php
							$listArtwork = $targetArtist->getArtwork();
							foreach ($listArtwork as $artwork) {
								?>
								<li><a href="<?= URL_ADMIN ?>artwork_zoom.php?artwork=<?= $artwork->getId() ?>"><h4><span class="fa fa-eye"></span> <?= $artwork->getTitle(); ?></h4></a></li>
								<?php
							}
						?>
						</ul>
					</section>
				</div>
				<?php
			}
		?>

	</div>
</div>

<?php
}
include('footer.php');