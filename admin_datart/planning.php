<?php

require_once('classes/user.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
require_once('classes/event.php');
require_once('includes/include.php');


$locationTitle = 'Gestion des expositions';


include('header.php');

?>

<div class="row" id="alert-area">
	<?= !empty($actionResultat)?$actionResultat:''; ?>
</div>

<div class="row">

<!--
************************************************************************************************
	SECTION CALENDRIER
************************************************************************************************
-->

	<div class="col-lg-6 col-md-5 col-sm-5 col-xs-12">
		<section>
			<div>
				
			</div>
		</section>
	</div>

<!--
************************************************************************************************
	SECTION EVENEMENT
************************************************************************************************
-->
	<div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
		<section>
			
		</section>
	</div>
		
</div>

<!--
************************************************************************************************
	GESTION DES ALERTES
************************************************************************************************
-->
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<section>
			
		</section>
	</div>
</div>

<?php
include('footer.php');