<?php 
$lang = [
	
	'en' =>[
		"nav.lien.oeuvres" => "Artwork",
		"nav.lien.artistes" => "Artist",
		"nav.lien.expo" => "Exhibit",
		"nav.lien.infos" => "About",
		"nav.lang.fr" => "Francais",
		"nav.lang.en" => "English",
		"nav.lang.ge" => "Deutsche",
		"nav.lang.ru" => "Pусский",
		"nav.lang.cn" => "中文",

		"artist.biography"=> !isset($targetArtist)?'':$targetArtist->getEnglishBiography()->getContent(),
		"artist.name"=>!isset($targetArtist)?'':$targetArtist->getIdentity(),
		"artist.oeuvres.exposees"=> !isset($targetArtist)?'':$targetArtist->getArtworkDisplayed(),
		"artist.pic"=> !isset($targetArtist)?'':$targetArtist->getPhotographicPortrait(),
		"artist.liste.oeuvres" => "Exposed artwork's list",
		"artist.list.artistes" => "Artists exposed",

		"expo.duree" => !isset($targetExhibit)?'':dateFormat($targetExhibit->getBeginDate()).' - '.dateFormat($targetExhibit->getEndDate()),
		"expo.titre"=> !isset($targetExhibit)?'':$targetExhibit->getTitle(),
		"expo.resume"=> !isset($targetExhibit)?'Nothing':$targetExhibit->getEnglishSummary()->getContent(),
		"expo.tableau.oeuvres" => !isset($targetExhibit)?'':$targetExhibit->listAvailableArtwork(),
		"expo.horaires" => !isset($targetExhibit)?'':$targetExhibit->getPublicOpening(),
		"expo.titre.artistes.oeuvres" => "Artists and works proposed",

		"artwork.titre.galerie" => "Gallery",
		"artwork.name" => !isset($targetArtwork)?'':$targetArtwork->getTitle(),
		"artwork.titre.oeuvre" => "Beyond artwork",
		"artwork.pic"=> !isset($targetArtwork)?'':$targetArtwork->getPictureOne()->getTarget(),
		"artwork.pic.two"=> isset($targetArtwork) && !empty($targetArtwork->getPictureTwo())?$targetArtwork->getPictureTwo()->getTarget():'',
		"artwork.nature"=> !isset($targetArtwork)?'':$targetArtwork->getEnglishCharacteristic()->getContent(),
		"artwork.artist.name"=> !isset($targetArtwork)?'':$targetArtwork->getArtistId(),
		"artwork.main"=> !isset($targetArtwork)?'':$targetArtwork->getEnglishMain()->getContent(),
		
		"about.titre.presentation" => "Presentation of the association",
		"about.presentation" => "Since 1996, the association Grand Angle has been managing an exhibition space dedicated to culture in the city of Tours. It is very active in the cultural development of the region. Grand Angle regularly offers artists the use of its premises.",
		"about.titre.mentions" => "Legal Notice",
		"about.mentions" => " Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloribus ipsam earum ipsum eum excepturi iure et sequi commodi sunt facere sed adipisci asperiores quas, quia ullam, animi repellat minus ea!"
	],
];


?>

