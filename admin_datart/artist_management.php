<?php 

require_once('classes/artist.php');
require_once('classes/user.php');
require_once('includes/include.php');


if (isset($_POST['targetArtist'])) {
	$targetArtist = new Artist($_POST['targetArtist']);
};

$locationTitle = isset($targetArtist)?$targetArtist->getName().''.$targetArtist->getSurname():'Les artistes';

include('header.php');
 ?>

 <div>
 	<a href="zoom_artist.php">Créer un nouvel artiste</a>
 </div>


<div id="mymodal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Attention !</h4>
            </div>
           	<div class="modal-body_user">
           		<p>Etes vous sûr(e) de vouloir supprimer cet artiste?</p>
               	<button type="submit" class="btn btn-default" data-dismiss="modal">Supprimer</button>
            </div> 
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

 <div class="row">
 	<section class="col-lg-12 col-md-12 col-sm-9 col-xs-9">
 		<h2>Liste des artistes</h2>
 			<table>
 				<thead>
	 				<tr>
	 					<th>Nom</th>
	 					<th>Prénom</th>
	 					<th>Pseudonyme</th>
	 				</tr>
				</thead>

				<tbody>
					<?php 
					// creation de la liste avec un foreach et chaque l = un artiste
						$liste = Artist::listArtist();
						foreach ($liste as $l) {
					 ?>
					 	<tr>
						 	<td>
						 		<?= $l->getSurname(); ?>
						 	</td>
						 	<td>
						 		<?= $l->getName(); ?>
						 	</td>
						 	<td>
						 		<?= $l->getAlias(); ?>
						 	</td>
						 	<td>
						 		<div class="form-group">
						 			<!-- liste deroulante -->
						 			<select class="form-control actionArtist">
							 			<option></option>
							 			<option value="update" data-id="<?= $l->getId(); ?>">Modifier</option> 
							 			<option value="delete" data-id="<?= $l->getId(); ?>">Supprimer</option> 
							 		</select>
						 		</div>
						 	</td>
						</tr>
					<?php 
						};
					?>
				</tbody>
 			</table>
 	</section>
 	<section class="col-lg-12 col-md-12 col-sm-3 col-xs-3">

 	</section>
 </div>


<?php
include('footer.php');