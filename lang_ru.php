<?php 
$lang = [
	
	'ru' =>[
		"nav.lien.oeuvres" => "Художественные работы",
		"nav.lien.artistes" => "Исполнитель",
		"nav.lien.expo" => "Экспозиция",
		"nav.lien.infos" => "Около",
		"nav.lang.fr" => "Francais",
		"nav.lang.en" => "English",
		"nav.lang.ge" => "Deutsche",
		"nav.lang.ru" => "Pусский",
		"nav.lang.cn" => "中文",

		"artist.biography"=> !isset($targetArtist)?'':$targetArtist->getRussianBiography()->getContent(),
		"artist.name"=>!isset($targetArtist)?'':$targetArtist->getIdentity(),
		"artist.liste.oeuvres" => "Список выставленных художественных работ",
		"artist.pic"=> !isset($targetArtist)?'':$targetArtist->getPhotographicPortrait(),
		"artist.list.artistes" => "экспонируются художников",

		"expo.titre"=> !isset($targetExhibit)?'':$targetExhibit->getTitle(),
		"expo.resume"=> !isset($targetExhibit)?'':$targetExhibit->getRussianSummary()->getContent(),
		"expo.duree" => !isset($targetExhibit)?'':dateFormat($targetExhibit->getBeginDate()).' - '.dateFormat($targetExhibit->getEndDate()),
		"expo.horaires" => !isset($targetExhibit)?'':$targetExhibit->getPublicOpening(),
		"expo.titre.artistes.oeuvres" => "Художники и произведения предложили",

		"artwork.titre.galerie" => "Галерея",
		"artwork.titre.oeuvre" => "Помимо художественных работ",
		"artwork.nature"=> !isset($targetArtwork)?'':$targetArtwork->getRussianCharacteristic()->getContent(),
		"artwork.main"=> !isset($targetArtwork)?'':$targetArtwork->getRussianMain()->getContent(),

		"about.titre.presentation" => "Представление ассоциации",
		"about.presentation" => "С 1996 года ассоциация Grand Angle управляет выставочным пространством, посвященным культуре в городе Тур. Он очень активен в культурном развитии региона. Grand Angle регулярно предлагает художникам использовать свои помещения.",
		"about.titre.mentions" => "выходные данные",
		"about.titre.credits" => "кредиты"
	],	

];


?>

