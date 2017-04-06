<?php

require_once('admin_datart/includes/include.php');
require_once('admin_datart/classes/artist.php');
require_once('admin_datart/classes/artist_textual_content.php');
require_once('admin_datart/classes/artwork.php');
require_once('admin_datart/classes/artwork_additional.php');
require_once('admin_datart/classes/artwork_textual_content.php');
require_once('admin_datart/classes/artwork_visual.php');
require_once('admin_datart/classes/exhibit.php');
require_once('admin_datart/classes/exhibit_textual_content.php');


if (isset($_GET['exhibit'])){
	$targetExhibit = new Exhibit($_GET['exhibit']);

if (isset($_GET['id'])){
	$targetArtist = new Artist($_GET['id']);

	if (isset($_SESSION['lang_user']) ) {
	  if ($_SESSION['lang_user'] == 'fr') {
	    require_once('lang_fr.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'en') {
	    require_once('lang_en.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'ge') {
	    require_once('lang_ge.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'ru') {
	    require_once('lang_ru.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'cn') {
	    require_once('lang_cn.php');
	  }
	}

	include_once('header.php');

?>

<section id="pic_artist">
	<?php
		if(!empty($targetArtist->getPhotographicPortrait())){
	?>
	<div class="container_pic">
		<img src="<?= URL_ADMIN.$targetArtist->getPhotographicPortrait() ?>" alt="<?= $targetArtist->getIdentity() ?>">
	</div>
	<?php 
		}

	 ?>
</section>

<section id="artist_presentation">
	<div class="artist">
		<h3><?= $targetArtist->getIdentity() ?></h3>
		<p><?= $lang[$_SESSION['lang_user']]['artist.biography'] ?></p>
	</div>
</section>

<section id="artist_listArtwork">
	<div class="listArtwork">
		<h3><?= $lang[$_SESSION['lang_user']]['artist.liste.oeuvres'] ?></h3>
		<ul>
		<?php 
			$listArtwork = $targetExhibit->getArtworkDisplayed();
			foreach ($listArtwork as $artworkId => $artwork) {
				if ($artwork['artist_id'] == $targetArtist->getId()) {
			?>
				<li>
					<a href="<?= URL_ROOT ?>artwork.php?exhibit=<?= $targetExhibit->getId() ?>&id=<?= $artworkId ?>"><span class="fa fa-eye"></span> <?= $artwork['title'] ?></a>
				</li>
			<?php
				}
			}
		?>
		</ul>

	</div>
</section>


<?php
}
}
else{
$currentExhibit = Exhibit::currentExhibit();
foreach ($currentExhibit as $targetExhibit) {

	if (isset($_SESSION['lang_user']) ) {
	  if ($_SESSION['lang_user'] == 'fr') {
	    require_once('lang_fr.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'en') {
	    require_once('lang_en.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'ge') {
	    require_once('lang_ge.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'ru') {
	    require_once('lang_ru.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'cn') {
	    require_once('lang_cn.php');
	  }
	}

	include_once('header.php');
	?>

	<h2><?= $lang[$_SESSION['lang_user']]['expo.titre'] ?></h2>
	<section id="list_artwork">
		<h3><?= $lang[$_SESSION['lang_user']]['artist.list.artistes'] ?></h3>
			<div>
				<ul>
			<?php 
				$listArtist = $targetExhibit->getArtistExposed();
				foreach ($listArtist as $artistId => $artistIdentity) {
						?>
						<li><a href="<?= URL_ROOT ?>artist.php?exhibit=<?= $targetExhibit->getId() ?>&id=<?= $artistId ?>"><span class="fa fa-paint-brush"></span> <?= $artistIdentity ?></a>
						<?php
						echo'</li>';
					}	
			?>	
				</ul>
			</div>
	</section>
	<?php

}
}
include('footer.php');
?>
