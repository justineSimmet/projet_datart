<?php

require_once('classes/user.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
require_once('includes/include.php');

if (isset($_GET['exhibit'])) {
	$targetExhibit = new Exhibit($_GET['exhibit']);
}

if (isset($_POST['title'])) {
	if (empty($_POST['id'])) {
		$newExhibit = new Exhibit();
		$newExhibit->setTitle($_POST['title']);
		$newExhibit->setBeginDate($_POST['begin_date']);
		$newExhibit->setEndDate($_POST['end_date']);
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
		$targetExhibit->setBeginDate($_POST['begin_date']);
		$targetExhibit->setEndDate($_POST['end_date']);
		$targetExhibit->setPublicOpening($_POST['public_opening']);
		$update = $targetExhibit->synchroDb();
		var_dump($update);
		if ($update) {
			$actionResultat = '<div class="alert alert-success alert-dismissable" id="user-edited">
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

$locationTitle = isset($targetExhibit)?$targetExhibit->getTitle():'Ajouter une exposition';

include('header.php');

?>
<div class="row">

	<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

		<div class="row">

			<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row" id="alert-area">
					<?= !empty($actionResultat)?$actionResultat:''; ?>
				</div>

				<!-- MISE EN PLACE DU FORMULAIRE INFOS GENERALES -->

				<?php

					if (isset($targetExhibit)) {
						$targetExhibit->formInfos($_SERVER['PHP_SELF'].'?exhibit='.$targetExhibit->getId(),'Modifier');
					}
					else{
						$newExhibit = new Exhibit();
						$newExhibit->formInfos($_SERVER['PHP_SELF'],'Créer');
					}

				?>

			</section>
			
		</div>
		
	</div>
	
</div>


<?php
include('footer.php');
