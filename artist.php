<?php

require_once('lang.php');
require_once('admin_datart/includes/include.php');
require_once('admin_datart/classes/artist.php');
require_once('admin_datart/classes/artist_textual_content.php');
require_once('admin_datart/classes/artwork.php');
require_once('admin_datart/classes/exhibit.php');
require_once('admin_datart/classes/exhibit_textual_content.php');
require_once('admin_datart/classes/event.php');

include('header.php');
?>

<section id="pic_artist">
	<div class="container_pic">
		<img src="<?= URL_ASSETS_FRONT ?>images/brett.jpg" alt="">
	</div>
</section>

<section id="artist_presentation">
	<div class="artist">
		<h4>Johnny Dpoule // $targetArtist</h4>
		<p>Bio de l'artiste... blablablablablbalbalbalblablablablbal
		balbalbalbalbalbalbalbalbalbalbalbalab</p>
	</div>
</section>

<section id="artist_listArtwork">
	<div class="listArtwork">
		<h5><?= $lang[$_SESSION['lang_user']]['artist.liste.oeuvres'] ?></h5>
		<table>
			<tr>
				<td>Nom oeuvre</td>
			</tr>
			<tr>
				<td>Nom oeuvre</td>
			</tr>
			<tr>
				<td>Nom oeuvre</td>
			</tr>
			<tr>
				<td>Nom oeuvre</td>
			</tr>
		</table>
	</div>
</section>


<?php

include('footer.php');
 ?>
