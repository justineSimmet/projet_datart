<?php

require_once('classes/user.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
require_once('includes/include.php');


$locationTitle = 'Gestion des expositions';

include('header.php');

?>

<div class="row" id="alert-area">
	<?= !empty($actionResultat)?$actionResultat:''; ?>
</div>

<div class="row">

	<div class="col-lg-9">

<!-- SECTION TABLEAU DE L'EXPOSITION EN COURS -->
	<section>
		<h2>Exposition en cours</h2>

		<table>
		<?php
			$currentExhibit = Exhibit::currentExhibit();
		?>
			<tbody>
				<tr>
					<td>
					<h3><?= $currentExhibit->getTitle(); ?></h3>
					<p class="date"><?= dateFormat($currentExhibit->getBeginDate()); ?> > <?= dateFormat($currentExhibit->getEndDate()); ?></p>
					</td>
					<td>
						<div class="form-group" >
							<select class="form-control actionExhibit">
								<option></option>
								<option value="update" data-id="<?= $currentExhibit->getId(); ?>">Modifier</option>
								<option value="delete" data-id="<?= $currentExhibit->getId(); ?>" >Supprimer</option>
							</select>
						</div>
					</td>
				</tr>
			</tbody>
			<tbody>
				<table>
					<thead>
						<th>
							Artistes
						</th>
						<th>
							Oeuvres
						</th>
						<th>
							Oeuvres dispo.
						</th>
						<th>
							Anglais
						</th>
						<th>
							Allemand
						</th>
						<th>
							Chinois
						</th>
						<th>
							Russe
						</th>
					</thead>
					<tbody>
						<tr>
							<td>
								<!-- Fonction qui retourne le nombre d'artistes liés à l'expo -->
							</td>
							<td>
								<!-- Fonction qui retourne le nombre d'oeuvres liés à l'expo -->
							</td>
							<td>
								<!-- Fonction qui retourne le nombre d'oeuvres liés à l'expo et disponible à la galerie -->
							</td>
							<td>
								<!-- Fonction qui retourne si la traduction de l'expo en anglais est ok -->
							</td>
							<td>
								<!-- Fonction qui retourne si la traduction de l'expo en allemand est ok -->
							</td>
							<td>
								<!-- Fonction qui retourne si la traduction de l'expo en chinois est ok -->
							</td>
							<td>
								<!-- Fonction qui retourne si la traduction de l'expo en russe est ok -->
							</td>
						</tr>
					</tbody>
				</table>
			</tbody>
		</table>
	</section>

<!-- SECTION TABLEAU DES EXPOSITIONS A VENIR -->
	<section>
		<h2>Expositions à venir</h2>
		<table>
			<table>
				<?php
					$listNext = Exhibit::listNextExhibit();
					foreach ($listNext as $ln) {
						?>
						<table>
							<tbody>
								<tr>
									<td>
										<h3><?= $ln->getTitle(); ?></h3>
										<p class="date"><?= dateFormat($ln->getBeginDate()); ?> > <?= dateFormat($ln->getEndDate()); ?></p>
									</td>
									<td>
										<div class="form-group" >
											<select class="form-control actionExhibit">
												<option></option>
												<option value="update" data-id="<?= $ln->getId(); ?>">Modifier</option>
												<option value="delete" data-id="<?= $ln->getId(); ?>" >Supprimer</option>
											</select>
										</div>
									</td>
								</tr>
							</tbody>
							<tbody>
								<table>
									<thead>
										<th>
											Artistes
										</th>
										<th>
											Oeuvres
										</th>
										<th>
											Oeuvres dispo.
										</th>
										<th>
											Anglais
										</th>
										<th>
											Allemand
										</th>
										<th>
											Chinois
										</th>
										<th>
											Russe
										</th>
									</thead>
									<tbody>
										<tr>
											<td>
												<!-- Fonction qui retourne le nombre d'artistes liés à l'expo -->
											</td>
											<td>
												<!-- Fonction qui retourne le nombre d'oeuvres liés à l'expo -->
											</td>
											<td>
												<!-- Fonction qui retourne le nombre d'oeuvres liés à l'expo et disponible à la galerie -->
											</td>
											<td>
												<!-- Fonction qui retourne si la traduction de l'expo en anglais est ok -->
											</td>
											<td>
												<!-- Fonction qui retourne si la traduction de l'expo en allemand est ok -->
											</td>
											<td>
												<!-- Fonction qui retourne si la traduction de l'expo en chinois est ok -->
											</td>
											<td>
												<!-- Fonction qui retourne si la traduction de l'expo en russe est ok -->
											</td>
										</tr>
									</tbody>
								</table>
							</tbody>
						</table>
						<?php
					}
				?>
			</table>		
		</table>
		
	</section>

	<?php
		if($currentUser->getStatus() == false ){
			?>
			<section>
				<h2>Expositions en cours de suppression</h2>
				<table>
					<!-- Retourne une liste des expositions dont la visibilité est à false -->
				</table>
			</section>
			<?php
		}
	?>
		
	</div>

	<div class="col-lg-3">

		<h2>Expositions passées</h2>
		<table>
			<?php
				$listOld = Exhibit::listPassedExhibit();
				foreach ($listOld as $lo) {
					?>
						<tr>
							<h4><?= $lo->getTitle(); ?></h4>
							<p class="date"><?= $lo->getBeginDate(); ?> > <?= $lo->getEndDate(); ?></p>
						</tr>
					<?php
				}
			?>
		</table>
	</div>

</div>
<?php
include('footer.php');
