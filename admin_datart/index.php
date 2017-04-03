<?php

require_once('classes/user.php');
require_once('classes/artist.php');
require_once('classes/artist_textual_content.php');
require_once('classes/artwork.php');
require_once('classes/artwork_additional.php');
require_once('classes/artwork_textual_content.php');
require_once('classes/artwork_visual.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
require_once('classes/event.php');
require_once('includes/include.php');
include('header.php');
?>

<div class="row">


<!--
****************************** ZONE ALERTE AGENDA ******************************
-->
	<div class="col-sm-12">
		
	</div>

<!--
****************************** ZONE MODULES ******************************
-->
	<div class="col-sm-12">
		<div class="row">

<!--
****************************** MODULE EXPO ******************************
-->

			<div class="col-sm-12">
				<section>
				<?php
					$currentExhibit = Exhibit::currentExhibit();
					if (count($currentExhibit) == 1 ) {
						$artworkArray = $currentExhibit[0]->getArtworkDisplayed();
						$artworkCount = count($artworkArray);
						$randNumber = rand(0, $artworkCount-1);
						

						?>
						<div id="current-exhibit">
						<div>
							<h2><?= $currentExhibit[0]->getTitle(); ?></h2>
							<p class="date">
								<?=  dateFormat($currentExhibit[0]->getBeginDate()); ?> > <?=  dateFormat($currentExhibit[0]->getEndDate()); ?>
							</p>
							<div class="summary">
								<?= !empty($currentExhibit[0]->getTextualContent())?substr($currentExhibit[0]->getFrenchSummary()->getContent(), 0, 400).'...':''; ?>
								<p class="clearfix">
									<a href="<?= URL_ADMIN ?>exhibit_zoom.php?exhibit=<?= $currentExhibit[0]->getId(); ?>" class="btn btn-default pull-right">Voir / Modifier l'exposition</a>
								</p>
								<p class="clearfix">
									<a href="#" class="btn btn-default pull-right">Voir la page visiteur</a>
								</p>
							</div>

						</div>
						<div class="exhibit-picture"><!-- Photo aléatoire tirée d'une oeuvre de l'expo -->
							<?php
								$randomImage = $artworkArray[$randNumber]->getPictureOne();
							?>
							<img src="<?= $randomImage->getTarget(); ?>" alt="<?= $randomImage->getLegend(); ?>"/>
						</div>
						</div>
						<?php
					}
					elseif (count($currentExhibit) >1 && count($currentExhibit) <4 ) {
						echo '<p class="exhibit-count">Il y a '.count($currentExhibit).' expositions en cours</p>';
						echo '<div id="current-exhibit-part">';
						foreach ($currentExhibit as $ce) {
							?>
							<div class="exhibit-box">
								<div>
									<h2><?= $ce->getTitle(); ?></h2>
									<p class="date">
										<?=  dateFormat($ce->getBeginDate()); ?> > <?=  dateFormat($ce->getEndDate()); ?>
									</p>
									<p class="summary">
									<?php
										if (!empty($ce->getFrenchSummary()->getContent()) ) {
											if (strlen($ce->getFrenchSummary()->getContent()) > 150 ) {
												$paragraphe = array('<p>','</p>');
												echo substr(str_replace($paragraphe, '', $ce->getFrenchSummary()->getContent()) ,0,144).' (...)';
											}
											else{
												echo $ce->getFrenchSummary()->getContent();
											}
										}
										else{
											echo '' ;
										}
									?>
									</p>
									<p class="text-center">
										<a href="<?= URL_ADMIN ?>exhibit_zoom.php?exhibit=<?= $ce->getId(); ?>" class="btn btn-default">Voir / Modifier l'exposition</a>
										<a href="#" class="btn btn-default">Voir la page visiteur</a>
									</p>
								</div>
								<div class="exhibit-picture"><!-- Photo aléatoire tirée d'une oeuvre de l'expo -->
									<img src="<?= URL_IMAGES ?>artwork/temp2.gif">
								</div>
							</div>

							<?php
						}
						echo '</div>';
					}
				?>
					<div id="next-exhibit">
						<div class="icon">
							
							<span class="fa fa-chevron-right"></span>
							<span class="valign"></span>

						</div>
						<div class="content">
							<?php
								$nextExhibit = Exhibit::listNextExhibit();
								if (!empty($nextExhibit)) {

							?>
							<p class="next-date"><span>A suivre...</span> <?= dateFormat($nextExhibit[0]->getBeginDate()) ; ?> > <?= dateFormat($nextExhibit[0]->getEndDate()) ; ?></p>
							<h3><?= $nextExhibit[0]->getTitle(); ?></h3>
							<div class="clearfix">
								<div>
								<?php
									if (!empty($nextExhibit[0]->getFrenchSummary()) ) {
										if (strlen($nextExhibit[0]->getFrenchSummary()->getContent()) > 200) {
												echo substr($nextExhibit[0]->getFrenchSummary()->getContent(),0,194).' (...)';
											}
											else{
												echo $nextExhibit[0]->getFrenchSummary()->getContent();
											}
										}
									else{
										echo '' ;
									}
									?>
									</div>
									<a href="<?= URL_ADMIN ?>exhibit_zoom.php?exhibit=<?= $nextExhibit[0]->getId(); ?>" class="btn btn-default pull-right">Voir / Modifier l'exposition</a>								
							</div>

						<?php
						 } 
						 ?>	
						</div>
					</div>
				</section>
			</div>
<!--
****************************** MODULE DERNIERS AJOUTS ******************************
-->
			<div class="col-lg-6">
				<section>

					<h2 class="module-title">Derniers ajouts</h2>
					<ul id="last-creation">
						<?php
							$last = lastCreateElement();
							foreach ($last as $l) {
								if (is_a($l, 'Exhibit')) {
									echo '<li><a href="'.URL_ADMIN.'exhibit_zoom?exhibit='.$l->getId().'">Expo : '.$l->getTitle().' - Enregistré le : '.dateFormat($l->getCreationDate()).'</a></li>';
								}
								elseif (is_a($l, 'Artist')) {
									echo '<li><a href="'.URL_ADMIN.'artist_zoom?artist='.$l->getId().'">Artiste : '.$l->getIdentity().' - Enregistré le : '.dateFormat($l->getCreationDate()).'</a></li>';
								}
								else{
									echo '<li><a href="'.URL_ADMIN.'artwork_zoom?artwork='.$l->getId().'">Oeuvre : '.$l->getTitle().' - Enregistré le : '.dateFormat($l->getCreationDate()).'</a></li>';
								}
							}
						?>
					</ul>
				</section>
			</div>

<!--
****************************** MODULE STATISTIQUES ******************************
-->
			<div class="col-lg-6">
				<section>
					
				</section>
			</div>
<!--
****************************** MODULE CALENDRIER ******************************
-->
			<div class="col-lg-12">
				<section>
					
				</section>
			</div>
			
		</div>
	</div>	



	
</div>

<?php
include('footer.php');