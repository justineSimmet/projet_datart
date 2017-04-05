<?php

require_once('admin_datart/includes/include.php');
require_once('admin_datart/classes/exhibit.php');
require_once('admin_datart/classes/artist.php');
require_once('admin_datart/classes/artist_textual_content.php');
require_once('admin_datart/classes/artwork.php');
require_once('admin_datart/classes/artwork_additional.php');
require_once('admin_datart/classes/artwork_textual_content.php');
require_once('admin_datart/classes/artwork_visual.php');
require_once('admin_datart/classes/exhibit_textual_content.php');
require_once('admin_datart/classes/event.php');

$currentExhibit = Exhibit::currentExhibit();
foreach ($currentExhibit as $targetExhibit) {


if (!strpos($_SERVER['PHP_SELF'], 'admin_datart/')) {
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

<section id="exhibit_presentation">
	<div class="expo">
		<h2><?= $lang[$_SESSION['lang_user']]['expo.titre'] ?></h2>
		<p class="date">
		<?= $lang[$_SESSION['lang_user']]['expo.duree'] ?>
			
		</p>
	</div>
</section>

<section id="exhibit_summary">
	<div>
		<?= $lang[$_SESSION['lang_user']]['expo.resume'] ?>
	</div>
</section>

<section id="horaires">
	<div>
		<p class="date"><?= $lang[$_SESSION['lang_user']]['expo.horaires'] ?></p>
	</div>
</section>

<section id="list_artwork">
	<h3><?= $lang[$_SESSION['lang_user']]['expo.titre.artistes.oeuvres'] ?></h3>
		<div>
			<ul>
		<?php 
			$list = $targetExhibit->getArtistExposed();
			foreach ($list as $artist) {
			
		 ?>
				<li>
					<a href="<?= URL_ROOT?>artist.php?exhibit=<?= $targetExhibit->getId()?>&artist=<?= $artist->getId()?>" class="list"><span class="fa fa-paint-brush"></span> <?= $artist->getIdentity() ?></a>
					<ul>
						<?php 
							$artworks = $artist->listDisplayedArtwork($targetExhibit->getId());

							foreach ($artworks as $artwork) {
						?>
								<li>
									<a href=""><span class="fa fa-eye"></span> <?= $artwork->getTitle() ?></a>
								</li>
						<?php 
							}
						 ?>
					</ul>
				</li>	
		 	</ul>
		 	<?php 
		 	}
		 	 ?>	
		</div>
</section>

<?php
};

include('footer.php');
 ?>
