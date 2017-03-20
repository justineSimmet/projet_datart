<?php

require_once('classes/user.php');
require_once('classes/artwork.php');
require_once('classes/artist.php');
require_once('classes/artist_textual_content.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
require_once('classes/artist.php');
require_once('classes/event.php');
require_once('includes/include.php');

$locationTitle = 'Gestion des Oeuvres';
include('header.php');
?>

<div class="row" id="alert-area">
	<?= !empty($actionResultat)?$actionResultat:''; ?>
</div>

<!-- MODAL POUR CONFIRMER LE CHANGEMENT DE STATUT D'UNE OEUVRE A VISIBLE FALSE -->
<div id="hideArtwork" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Attention !</h4>
        	</div>
        	<div class="modal-body">
        		<p> Vous êtes sur le point de supprimer l'oeuvre <?= isset($targetArtwork)?$targetArtwork->getTitle():''; ?>. </p>
                <p> Voulez-vous confirmer cette action ?</p>
                <form action="exhibit_management.php" method="POST">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
	                <input type="hidden" name="targetId" value="<?= isset($targetArtwork)?$targetArtwork->getId():'' ; ?>" />
	                <input type="hidden" name="action" value="hide" />
	              	<!-- <button type="button" class="btn btn-default" id="btn-hide">Supprimer</button> -->
	              	<input type="submit" class="btn btn-default" value="Supprimer" />
                </form>
        	</div>
        	<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POUR CONFIRMER LA SUPPRESSION DEFINITIVE D'UNE OEUVRE -->
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
                <form action="exhibit_management.php" method="POST">
	                <label for="password">Mot de passe :</label>
	                <input type="password" name="password" placeholder="Votre mot de passe"  required />
	                <input type="hidden" name="action" value="delete" />
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

<div class="row">

	<div class="col-lg-9" id="managementArtworkList">

	</div>

	<div class="col-lg-3" id="hiddenArtworkList">

	</div>

</div>

<?php
include('footer.php');
