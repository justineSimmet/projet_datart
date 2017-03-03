<?php

require_once('classes/user.php');
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
						echo 'yo';
						?>
						<div class="current-exhibit">
						<div>
							<h2><?= $currentExhibit[0]->getTitle(); ?></h2>
							<p class="date">
								<?=  $currentExhibit[0]->getBeginDate(); ?> > <?=  $currentExhibit[0]->getEndDate(); ?>
							</p>
							<p class="summary">
								<?=  !empty($currentExhibit[0]->getTextualContent())?$currentExhibit[0]->getFrenchSummary():''; ?>
							</p>

						</div>
						<div><!-- Photo aléatoire tirée d'une oeuvre de l'expo -->
							
						</div>
						</div>
						<?php
					}
					else{
						echo 'no';
					}
				?>
					
				</section>
			</div>
<!--
****************************** MODULE DERNIERS AJOUTS ******************************
-->
			<div class="col-lg-6">
				<section>
					
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
