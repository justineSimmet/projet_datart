<?php

require_once('admin_datart/classes/user.php');
require_once('admin_datart/classes/artist.php');
require_once('admin_datart/classes/artist_textual_content.php');
require_once('admin_datart/classes/artwork.php');
require_once('admin_datart/classes/exhibit.php');
require_once('admin_datart/classes/exhibit_textual_content.php');
require_once('admin_datart/classes/event.php');
require_once('admin_datart/includes/include.php');
include('header.php');



?>

<section id="logo_GA">
	<div class="container_logo">
		<img src="<?= URL_ASSETS_FRONT ?>images/DAgrand-angle_vecto-blanc.png" alt="">
	</div>
</section>


<section id="GA_presentation">
	<div class="presentation">
		<h4>Présentation de l'association </h4>
		<p>Suspendisse fringilla tincidunt odio ac volutpat. Aliquam iaculis nibh id mauris ornare sodales. Donec ut arcu at elit consequat sodales id quis metus. Morbi dapibus egestas lorem, sit amet tempus lacus dictum sed. Pellentesque maximus, arcu eget aliquet euismod, erat nisl tempor diam, euismod lacinia velit orci eu enim. Vestibulum vulputate congue aliquam.</p>
	</div>
</section>


<section id="mention">
	<div class="mentions_legales">
		<h4>Mentions légales </h4>
		<p>Suspendisse fringilla tincidunt odio ac volutpat. Aliquam iaculis nibh id mauris ornare sodales. Donec ut arcu at elit consequat sodales id quis metus. Morbi dapibus egestas lorem, sit amet tempus lacus dictum sed. Pellentesque maximus, arcu eget aliquet euismod, erat nisl tempor diam, euismod lacinia velit orci eu enim. Vestibulum vulputate congue aliquam.</p>
	</div>
</section>

<?php

include('footer.php');
 ?>
