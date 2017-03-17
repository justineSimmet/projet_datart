<?php

require_once('classes/user.php');
require_once('classes/artwork.php');
require_once('classes/artist.php');
require_once('classes/exhibit.php');
require_once('includes/include.php');

$locationTitle = 'Gestion des Oeuvres';

if (isset($_POST['targetId'])) {
    $targetArtwork = new Artwork($_POST['targetId']);
    $targetArtist = new Artist($targetArtwork->getArtistId());
};


if (isset($_POST['targetId']) && isset($_POST['action']) ) {
    if ($_POST['action'] == 'hide') {
        $targetArtwork = new Artwork($_POST['targetId']);
        $hide = $targetArtwork->hideArtwork();
        if ($hide) {
            $actionResultat = '<div class="alert alert-success alert-dismissable"s>
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Vous venez de supprimer l\'oeuvre "'.$targetArtwork->getTitle().'"" de '. $targetArtist->getIdentity().'. </strong>
                </div>';
        }
        else{
            $actionResultat = '<div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Erreur !</strong> L\'oeuvre '.$targetArtwork->getTitle().' n\'a pas été supprimée.
            </div>';
        }
    }
    elseif($_POST['action'] == 'publish'){
        $targetArtwork = new Artwork($_POST['targetId']);
        $publish = $targetArtwork->publishArtwork();
        if ($publish) {
            $actionResultat = '<div class="alert alert-success alert-dismissable"s>
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Vous venez de re-publier l\'oeuvre '.$targetArtwork->getTitle().' . </strong>
                </div>';
        }
        else{
            $actionResultat = '<div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Erreur !</strong> L\'oeuvre '.$targetArtwork->getTitle().' n\'a pas pu être re-publiée.
            </div>';
        }
    }
    elseif($_POST['action'] == 'delete'){
        $targetArtwork = new Artwork($_POST['targetId']);
        $check = $currentUser->passwordCheck($_POST['password']);
        if ($check) {
            $delete = $targetArtwork->deleteArtwork();
            if ($delete) {
                $actionResultat = '<div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Vous venez de supprimer définitivement l\'oeuvre '.$targetArtwork->getTitle().'. </strong>
                </div>';
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


include('header.php');
?>

<div class="row" id="alert-area">
    <div class="col-sm-12 text-center">
	   <?= !empty($actionResultat)?$actionResultat:''; ?>
    </div>
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
                <form action="<?= URL_ADMIN; ?>artwork_management.php" method="POST">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
	                <input type="hidden" name="targetId" value="<?= isset($targetArtwork)?$targetArtwork->getId():'' ; ?>" />
	                <input type="hidden" name="action" value="hide" />
	              	<input type="submit" value="Supprimer" class="btn btn-danger pull-right" />
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
                <form action="?= URL_ADMIN; ?>artwork_management.php" method="POST">
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

	<div class="col-sm-9" id="managementArtworkList">
        <h2>Oeuvres enregistrées</h2>
        <section>
             <?php
                $listedArtwork = Artwork::listArtwork();
                foreach ($listedArtwork as $index) {
                    echo '<h3 class="listClassification">'.$index->getIdentity().'</h3>';
                    foreach ($index->getArtwork() as $artwork) {
                    ?>
                        <div class="row col-equal-height">
                            <div class="col-sm-6">
                                <h3><?= $artwork->getTitle(); ?></h3>
                            </div>
                            <div class="col-sm-6">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="actions-artwork" class="control-label col-sm-4">Actions :</label>
                                        <div class="col-sm-8">
                                            <select name="actions-artwork" class="form-control actionArtworkt">
                                                <option> --- </option>
                                                <option value="update" data-id="<?= $artwork->getId(); ?>">Voir / Modifier</option>
                                                <option value="hide" data-id="<?= $artwork->getId(); ?>" >Supprimer</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row table-artwork">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th>
                                                Nature
                                            </th>
                                            <th>
                                                Référence
                                            </th>
                                            <th>
                                                Anglais
                                            </th>
                                            <th>
                                                Allemand
                                            </th>
                                            <th>
                                                Russe
                                            </th>
                                            <th>
                                                Chinois
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <!-- NATURE DE L'OEUVRE -->
                                            </td>
                                            <td>
                                                <?= $artwork->getReferenceNumber(); ?>
                                            </td>
                                            <td>
                                                <!-- TRAD FRENCH -->
                                            </td>
                                            <td>
                                                <!-- TRAD GERMAN -->
                                            </td>
                                            <td>
                                                <!-- TRAD RUSSIAN -->
                                            </td>
                                            <td>
                                                <!-- TRAD CHINESE -->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>              
                            </div>
                        </div>
                    <?php
                    }
                }
            ?>
        </section>
	</div>

    <?php 
        if ($currentUser->getStatus() == FALSE) {
    ?>
	<div class="col-lg-3" id="hiddenArtworkList">
        <h2>Oeuvres en cours de suppression</h2>
        <section>
        <?php
            $listHidden = Artwork::listHiddenArtwork();
            var_dump($listHidden);
        ?>
        </section>
	</div>
    <?php
        }
    ?>

</div>

<?php
include('footer.php');
