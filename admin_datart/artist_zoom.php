<?php 


require_once('classes/artist.php');
require_once('classes/user.php');
require_once('classes/artist_textual_content.php');
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
		
		if (!empty($_POST['surname'] && $_POST['name']) || $_POST['alias']) {
			$updateArtist = $targetArtist->synchroDb();
		
			if ($updateArtist) {
				$actionResultat = '<div class="alert alert-success alert-dismissable" id="artist-edited">
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
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
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
				$actionResultat = '<div class="alert alert-danger alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Erreur</strong> PROUT
					</div>';
			}

	};
}


if (isset($_POST['targetArtist']) && isset($_POST['action']) ) {
	if($_POST['action'] == 'publish'){
		$targetArtist = new Artiste($_POST['targetArtist']);
		$publish = $targetArtist->publishArtist();
		if ($publish) {
			$actionResultat = '<div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Félicitation,</strong> L\'artiste '.$targetArtist->getIdentity().' est de nouveau visible.
				</div>';
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> L\'artiste '.$targetArtist->getIdentity().' est toujours masqué.
			</div>';	
		}
	}
	elseif($_POST['action'] == 'deleteArtiste'){
		$targetArtist = new Artiste($_POST['targetArtist']);
		$check = $currentUser->passwordCheck($_POST['password']);
		if ($check) {
			$delete = $targetArtist->deleteArtist();
			if ($delete) {
				header('Location:exhibit_management.php');
			}
			else{
				$actionResultat = '<div class="alert alert-danger alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur !</strong> L\'artiste '.$targetArtist->getIdentity().' n\'a pas été supprimée.
				</div>';
			}
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Votre mot de passe est incorrect.</strong> Vous ne pouvez pas supprimer l\'artiste '.$targetExhibit->getIdentity().'.
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
			$actionResultat = '<div class="alert alert-success alert-dismissable" id="update-artist-text">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Les textes d\'accompagnement ont bien été mis à jour.</strong>
			</div>';
		}
		else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
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
			$actionResultat = '<div class="alert alert-success alert-dismissable" id="insert-artist-text">>
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
Insertion en bdd et recuperation en bdd des images de l'artiste
**
************************************************************************************************/


	if (!empty($_POST['action']) && $_POST['action'] == 'addArtistPicture') {
		$dossierUpload = 'assets/images/artist_portraits/';
		$pictureFile = basename($_FILES['fichier']['name']);
		$tailleMaxi = 2097152;
		$taille = filesize($_FILES['fichier']['tmp_name']);
		$uploadFormat = array('.jpg', '.png', '.jpeg');
		$extension = strrchr($_FILES['fichier']['name'], '.'); 
		$namePicture = $targetArtist->getId().''.$extension;
		$targetArtist = new Artist($_POST['artistId']);
		
		if(!in_array($extension, $uploadFormat)) //Si l'extension n'est pas dans le tableau
		{
		     $erreur = 'Vous devez uploader un fichier de type png, jpg, ou jpeg ...';
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
		          		echo 'Upload effectué avec succès !';
					}
	 				else //Sinon (la fonction renvoie FALSE).
					{
						echo 'Echec de l\'upload !';
					}
				}
		else
		{
		     echo $erreur;
		}



	

		$targetArtist->setPhotographicPortrait($dossierUpload . $namePicture);
		$update = $targetArtist->synchroPicture();

		if ($update) {
		$actionResultat = '<div class="alert alert-success alert-dismissable" id="artist-edited"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Félicitation</strong> La photo de' .$targetArtist->getIdentity().' a bien été ajoutée.</div>';
		}
		else{
		$actionResultat = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Erreur !</strong>La photo de' .$targetArtist->getIdentity(). ' n\a pas été ajoutée.</div>';		
		}
	}


$locationTitle = isset($targetArtist)?$targetArtist->getIdentity():'Ajouter un artiste';

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
									$targetArtist->formInfos($_SERVER['PHP_SELF'].'?artist='.$targetArtist->getId(),'Modifier');
								}
								else{
									$artist = new Artist();
									$artist->formInfos($_SERVER['PHP_SELF'],'Créer');
								}

							?>
					</div>

						<h3>2 - Textes et Photos </h3>
					<div class="formText_formPhoto">

					<div>
						
					</div>
				<?php

					if (isset($targetArtist)){	
						if (!empty($targetArtist->getTextualContent())) {
							$targetArtist->formText($_SERVER['PHP_SELF'].'?artist='.$targetArtist->getId(), 'Modifier');
							$targetArtist->formPhoto($_SERVER['PHP_SELF'].'?artist='.$targetArtist->getId(), 'Modifier');	
						}
						else{
							$targetArtist->formText($_SERVER['PHP_SELF'].'?artist='.$targetArtist->getId(), 'Ajouter');
							$targetArtist->formPhoto($_SERVER['PHP_SELF'].'?artist='.$targetArtist->getId(), 'Ajouter');
						}
					}
					else{
						$newArtist = new Artist();
						$newArtist->formText('','Ajouter');
						$newArtist->formPhoto('','Ajouter');
					}

				?>
					</div>
					<div class="artistPicture">
						<img src="<?= !empty($targetArtist->getPhotographicPortrait())?$targetArtist->getPhotographicPortrait():''; ?>" alt="">
					</div>
				<?php 
					
				 ?>
			</section>
			
		</div>
		
	</div>
	
</div>

<?php
include('footer.php');