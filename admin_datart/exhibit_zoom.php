<?php

require_once('classes/user.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
require_once('includes/include.php');

if (isset($_GET['exhibit'])) {
	$targetExhibit = new Exhibit($_GET['exhibit']);
}


//MANIPULATION EN BASE DE DONNEE SUR LES DONNEES GENERALES
if (isset($_POST['title'])) {
	if (empty($_POST['id'])) {
		$newExhibit = new Exhibit();
		$newExhibit->setTitle($_POST['title']);
		$newExhibit->setBeginDate(dateFormat($_POST['begin_date']));
		$newExhibit->setEndDate(dateFormat($_POST['end_date']));
		$newExhibit->setPublicOpening($_POST['public_opening']);
		$create = $newExhibit->synchroDb();
		if ($create) {
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
			$actionResultat = '<div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Félicitation</strong> L\'exposition '.$targetExhibit->getTitle().' a bien été modifiée.
				</div>';
		}else{
			$actionResultat = '<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Erreur !</strong> L\'exposition '.$targetExhibit->getTitle().' n\'a pas pu être modifiée.
			</div>';	
		}
	}
}

//ACTIONS SUR UNE EXPOSITION MASQUEE
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
	elseif($_POST['action'] == 'delete'){
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
}
//MANIPULATION EN BASE DE DONNEE SUR LES TEXTES D'ACCOMPAGNEMENT D'UNE EXPO
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
			$actionResultat = '<div class="alert alert-success alert-dismissable">
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
		var_dump($valideInsert);
		if($valideInsert == 10){
			$actionResultat = '<div class="alert alert-success alert-dismissable">
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

$locationTitle = isset($targetExhibit)?$targetExhibit->getTitle():'Ajouter une exposition';

include('header.php');
?>
<div class="row">

	<!-- MODAL POUR CONFIRMER LA SUPPRESSION DEFINITIVE D'UNE EXPOSITION -->
	<div id="deleteExhibit" class="modal fade" role="dialog" >
		<div class="modal-dialog">
		</div>
			<div class="modal-content">
	        	<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal">&times;</button>
	        		<h4 class="modal-title">Attention !</h4>
	        	</div>
	        	<div class="modal-body_test">
	        		<p> Vous êtes sur le point de supprimer <strong>définitivement</strong> l'exposition <?= isset($targetExhibit)?$targetExhibit->getTitle():''; ?>. </p>
	                <p> Pour confirmer cette action, merci de saisir votre mot de passe</p>
	                <form action="<?= isset($targetExhibit)?$_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId():''; ?>" method="POST">
		                <label for="password">Mot de passe :</label>
		                <input type="password" name="password" placeholder="Votre mot de passe"  required />
		                <input type="hidden" name="action" value="delete" />
		                <input type="hidden" value="<?= isset($targetExhibit)?$targetExhibit->getId():';' ?>" name="targetId">
		                <input type="submit" value="Supprimer" />
	                </form>
	        	</div>
	        	<div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	            </div>
	        </div>
	</div>

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

	<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

<?php
	if (isset($targetExhibit)){
?>
	<!-- BOUTONS D'ACTIONS -->
		<div class="row">


		<?php
			if ($targetExhibit->getVisible() == TRUE) {
		?>
			<div class="col-lg-12 col-md-12 hidden-md col-sm-12 hidden-sm col-xs-12">
				<a href="#" class="btn btn-default" role="button"><span class="fa fa-cubes"></span> Placer les oeuvres</a>
				<a href="#" class="btn btn-default" role="button"><span class="fa fa-file-text"></span> Dossier technique</a>
				<a href="#" class="btn btn-default" role="button"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
			</div>
		<?php
			}
			else{
		?>
			<div class="col-lg-12 col-md-12 hidden-md col-sm-12 hidden-sm col-xs-12">
				<button class="btn btn-default btn-publish" role="button" data-id="<?= $targetExhibit->getId(); ?>" ><span class="fa fa-eye"></span> Publier l'exposition</button>
				<button class="btn btn-default btn-delete" role="button" data-id="<?= $targetExhibit->getId(); ?>" ><span class="fa fa-trash"></span> Supprimer définitivement l'exposition</button>
			</div>	
		<?php
			}
		?>
		</div>
<?php
	}
?>

		<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row" id="alert-area">
					<?= !empty($actionResultat)?$actionResultat:''; ?>
				</div>
			</div>

		</div>

		<div class="row">

			<div class="col-lg-12 col-md-9 col-sm-9 col-xs-12"> <!-- ZONE PRINCIPALE -->
				<section> <!--FORMULAIRES INFOS GENERALES & TEXTES -->
					<?php

						if (isset($targetExhibit)) {
							$targetExhibit->formInfos($_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId(),'Modifier');
						}
						else{
							$newExhibit = new Exhibit();
							$newExhibit->formInfos($_SERVER['PHP_SELF'],'Créer');
						}

					?>
				<div> <!--FORMULAIRE TEXTES -->
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

				</section>

				<section> <!-- FORMULAIRE ARTISTES -->
					
				</section>

				<section> <!-- FORMULAIRE OEUVRES -->
					
				</section>
			</div>

			<div class="col-lg-12 col-md-3 col-sm-3 col-xs-12"> <!-- ZONE ANNEXE A DROITE -->
			<?php
			if (isset($targetExhibit)){
				if ($targetExhibit->getVisible() == TRUE) {
			?>
				<div class="col-lg-12 hidden-lg col-md-12 col-sm-12 col-xs-12 hidden-xs">
					<a href="#" class="btn btn-default" role="button"><span class="fa fa-cubes"></span> Placer les oeuvres</a>
					<a href="#" class="btn btn-default" role="button"><span class="fa fa-file-text"></span> Dossier technique</a>
					<a href="#" class="btn btn-default" role="button"><span class="fa fa-desktop"></span> Voir la page visiteur</a>
				</div>
			<?php
				}
				else{
			?>
				<div class="col-lg-12 hidden-lg col-md-12 col-sm-12 col-xs-12 hidden-xs">
					<button class="btn btn-default btn-publish" role="button" data-id="<?= $targetExhibit->getId(); ?>" ><span class="fa fa-eye"></span> Publier l'exposition</button>
					<button class="btn btn-default btn-delete" role="button" data-id="<?= $targetExhibit->getId(); ?>" ><span class="fa fa-trash"></span> Supprimer définitivement l'exposition</button>
				</div>	
			<?php
				}
			}
			?>
				<h2>Vie de l'expo</h2>
				<section>
					
				</section>
			</div>

		</div>
		
	</div>

<?php
	}
?>

</div>


<?php
include('footer.php');
