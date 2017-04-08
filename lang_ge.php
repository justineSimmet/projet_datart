<?php 
$lang = [
	
	'ge' =>[
		"nav.lien.oeuvres" => "Kunstwerk",
		"nav.lien.artistes" => "Künstler",
		"nav.lien.expo" => "Ausstellungsstück",
		"nav.lien.infos" => "Über",
		"nav.lang.fr" => "Francais",
		"nav.lang.en" => "English",
		"nav.lang.ge" => "Deutsche",
		"nav.lang.ru" => "Pусский",
		"nav.lang.cn" => "中文",

		"artist.biography"=> !isset($targetArtist)?'':$targetArtist->getGermanBiography()->getContent(),
		"artist.name"=>!isset($targetArtist)?'':$targetArtist->getIdentity(),
		"artist.liste.oeuvres" => "Liste der vorhandenen Kunstwerke",
		"artist.pic"=> !isset($targetArtist)?'':$targetArtist->getPhotographicPortrait(),
		"artist.list.artistes" => "Ausgestellte Künstler",

		"expo.titre"=> !isset($targetExhibit)?'':$targetExhibit->getTitle(),
		"expo.resume"=> !isset($targetExhibit)?'':$targetExhibit->getGermanSummary()->getContent(),
		"expo.duree" => !isset($targetExhibit)?'':dateFormat($targetExhibit->getBeginDate()).' - '.dateFormat($targetExhibit->getEndDate()),
		"expo.horaires" => !isset($targetExhibit)?'':$targetExhibit->getPublicOpening(),
		"expo.titre.artistes.oeuvres" => "Künstler und Werke vorgeschlagen",

		"artwork.titre.galerie" => "Galerie",
		"artwork.titre.oeuvre" => "Jenseits von Kunstwerken",
		"artwork.nature"=> !isset($targetArtwork)?'':$targetArtwork->getGermanCharacteristic()->getContent(),
		"artwork.main"=> !isset($targetArtwork)?'':$targetArtwork->getGermanMain()->getContent(),

		"about.titre.presentation" => "Präsentation des Vereins",
		"about.presentation" => "Seit 1996 leitet der Verein Grand Angle einen Ausstellungsraum für Kultur in der Stadt Tours. Es ist sehr aktiv in der kulturellen Entwicklung der Region. Grand Angle bietet den Künstlern regelmäßig die Verwendung ihrer Räumlichkeiten an.",
		"about.titre.mentions" => "Impressum",
		"about.titre.credits" => "Kredite"
	],	
	
];


?>

