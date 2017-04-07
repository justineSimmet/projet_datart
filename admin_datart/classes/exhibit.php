<?php
/* ---------------------------
	DatArt
	Creation Date : 20/02/2017
	classes/exhibit.php
	author : ApplePie Cie
---------------------------- */

class Exhibit{
	private $id;
	private $title;
	private $begin_date;
	private $end_date;
	private $public_opening;
	private $art_zoning;
	private $visible;
	private $creation_date;
	private $textual_content;

	function __construct($id=''){

		if($id != 0){

			$res= requete_sql("SELECT * FROM exhibit WHERE id = '".$id."' ");
			$exhibit = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $exhibit['id'];	
			$this->title = $exhibit['exhibit_title'];	
			$this->begin_date = $exhibit['begin_date'];	
			$this->end_date = $exhibit['end_date'];	
			$this->public_opening = $exhibit['public_opening'];	
			$this->art_zoning = $exhibit['art_zoning'];	
			$this->visible = $exhibit['visible'];	
			$this->creation_date = $exhibit['creation_date'];
			$this->textual_content = array();
			$text = requete_sql("SELECT id, language, subject FROM textual_content_exhibit WHERE exhibit_id = '".$id."' ");

			if (count($text) !== 0) {

				while ( $t = $text->fetch(PDO::FETCH_ASSOC)) {
					if ($t['language'] == 'french') {
						if ($t['subject'] == 'category') {
							array_push($this->textual_content, new ExhibitFrenchCategory($t['id']));
						}
						else{
							array_push($this->textual_content, new ExhibitFrenchSummary($t['id']));
						}
					}
					elseif ($t['language'] == 'english') {
						if ($t['subject'] == 'category') {
							array_push($this->textual_content, new ExhibitEnglishCategory($t['id']));
						}
						else{
							array_push($this->textual_content, new ExhibitEnglishSummary($t['id']));
						}
					}
					elseif ($t['language'] == 'german') {
						if ($t['subject'] == 'category') {
							array_push($this->textual_content, new ExhibitGermanCategory($t['id']));
						}
						else{
							array_push($this->textual_content, new ExhibitGermanSummary($t['id']));
						}
					}
					elseif ($t['language'] == 'russian') {
						if ($t['subject'] == 'category') {
							array_push($this->textual_content, new ExhibitRussianCategory($t['id']));
						}
						else{
							array_push($this->textual_content, new ExhibitRussianSummary($t['id']));
						}
					}
					elseif ($t['language'] == 'chinese') {
						if ($t['subject'] == 'category') {
							array_push($this->textual_content, new ExhibitChineseCategory($t['id']));
						}
						else{
							array_push($this->textual_content, new ExhibitChineseSummary($t['id']));
						}
					}
				}

			}
			else{
				$this->textual_content = array();
			}
			
		}
		else {
        	$this->textual_content = array();
        }
	}

	function getId(){
		return $this->id;
	}

	function setTitle($title){
		$this->title = $title;
		return TRUE;
	}

	function getTitle(){
		return $this->title;
	}

	function setBeginDate($beginDate){
		$this->begin_date = $beginDate;
		return TRUE;
	}

	function getBeginDate(){
		return $this->begin_date;
	}

	function setEndDate($endDate){
		$this->end_date = $endDate;
		return TRUE;
	}

	function getEndDate(){
		return $this->end_date;
	}

	function setPublicOpening($publicOpening){
		$this->public_opening = $publicOpening;
		return TRUE;
	}

	function getPublicOpening(){
		return $this->public_opening;
	}

	function setVisible($visible){
		$this->visible = $visible;
		return TRUE;
	}

	function getVisible(){
		return $this->visible;
	}

	function setZoning($art_zoning){
		$this->art_zoning = $art_zoning;
		return TRUE;
	}

	function getZoning(){
		return $this->art_zoning;
	}

	function setCreationDate($creationDate){
		$this->creation_date = $creationDate;
		return TRUE;
	}

	function getCreationDate(){
		return $this->creation_date;
	}

	function getTextualContent(){
		return $this->textual_content;
	}

	function getFrenchCategory(){
		if(!empty($this->getTextualContent())){
			return $this->textual_content[0];
		}
	}

	function getFrenchSummary(){
		if(!empty($this->getTextualContent())){
			return $this->textual_content[1];
		}
	}

	function getEnglishCategory(){
		if(!empty($this->getTextualContent())){
			return $this->textual_content[2];
		}
	}

	function getEnglishSummary(){
		if(!empty($this->getTextualContent())){
			return $this->textual_content[3];
		}
	}

	function getGermanCategory(){
		if(!empty($this->getTextualContent())){
			return $this->textual_content[4];
		}
	}

	function getGermanSummary(){
		if(!empty($this->getTextualContent())){
			return $this->textual_content[5];
		}
	}

	function getRussianCategory(){
		if(!empty($this->getTextualContent())){
			return $this->textual_content[6];
		}
	}

	function getRussianSummary(){
		if(!empty($this->getTextualContent())){
			return $this->textual_content[7];
		}
	}

	function getChineseCategory(){
		if(!empty($this->getTextualContent())){
			return $this->textual_content[8];
		}
	}

	function getChineseSummary(){
		if(!empty($this->getTextualContent())){
			return $this->textual_content[9];
		}
	}

	function getEvents(){
		$res = requete_sql("SELECT id FROM event WHERE exhibit_id = '".$this->id."' ORDER BY event_date, event_start_time");
		$events =  $res->fetchAll(PDO::FETCH_ASSOC);
		$listEvent = array();
		foreach ($events as $event) {
			array_push($listEvent, new Event($event['id']));
		}
		return $listEvent;
	}

	//Récupère l'ID de l'événement d'ouverture
	function getOpenEvent(){
		$res = requete_sql("SELECT id FROM event WHERE exhibit_id = '".$this->id."' AND name = 'Début' ");
		$open = $res->fetch(PDO::FETCH_ASSOC);
		$open = implode("','",$open);
		return (int) $open;
	}

	//Récupère l'ID de l'événement de fermeture
	function getCloseEvent(){
		$res = requete_sql("SELECT id FROM event WHERE exhibit_id = '".$this->id."' AND name = 'Fin' ");
		$close = $res->fetch(PDO::FETCH_ASSOC);
		$close = implode("','",$close);
		return (int) $close;	
	}

	function getArtistExposed(){
		$res = requete_sql("SELECT artist.id, artist.surname, artist.name, artist.alias FROM artist
							LEFT JOIN artist_exposed ON artist.id = artist_exposed.artist_id
							WHERE artist_exposed.exhibit_id = '".$this->id."' ORDER BY CONCAT(artist.alias, artist.surname) ASC ");
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$listArtists = array();
		foreach ($res as $artist) {
			$identity = '';
			if (!empty($artist['name']) && !empty($artist['surname'])) {
	      		if (!empty($artist['alias'])) {
	        	$identity = $artist['alias'].' ('.$artist['surname'].' '.$artist['name'].')';
	      		}
	      		else{
	        		$identity = $artist['surname'].' '.$artist['name'];
	      		}
	    	}
	    	else{
	      		$identity = $artist['alias'];
	    	}
	    	$listArtists[$artist['id']] = $identity;
		}
		return $listArtists;
	}

	function cleanArtistExposed(){
		$res = requete_sql("DELETE FROM artist_exposed WHERE exhibit_id = '".$this->id."' ");
		if($res){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	function getArtworkDisplayed(){
		$res = requete_sql("SELECT artwork.id, artwork.artwork_title, artwork.artist_id, artwork.reference_number FROM artwork
							LEFT JOIN artwork_displayed ON artwork.id = artwork_displayed.artwork_id
							WHERE artwork_displayed.exhibit_id = '".$this->id."' ORDER BY artwork.artwork_title ASC");
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$listArtworks = array();
		foreach ($res as $artwork) {
			$listArtworks[] = ['artist_id'=>$artwork['artist_id'],'title'=>$artwork['artwork_title'],'artwork_id'=>$artwork['id'],'reference'=>$artwork['reference_number']];
		}
		return $listArtworks;
	}

	function cleanArtworkdDisplayed(){
		$res = requete_sql("DELETE FROM artwork_displayed WHERE exhibit_id = '".$this->id."' ");
		if($res){
			$this->artwork_displayed = array();
			return TRUE;
		}
		else{
			return FALSE;
		}
	}


/*********************************
**
** SYNCHRO AVEC LA BASE DE DONNEES
** Si pas d'Id -> INSERT
** Sinon -> UPDATE
**
*********************************/
	function synchroDb(){
		if (empty($this->id)) {
			$create = requete_sql("
				INSERT INTO exhibit 
				VALUES(
					NULL,
					'".addslashes($this->title)."',
					'".addslashes($this->begin_date)."',
					'".addslashes($this->end_date)."',
					'".addslashes($this->public_opening)."',
					NULL,
					TRUE,
					now()
				)");
			if ($create) {
				$this->id = $create;
				return $this->id;
			}
			else{
				return FALSE;
			}
		}
		else{
			$update = requete_sql("
				UPDATE exhibit SET
				exhibit_title = '".addslashes($this->title)."',
				begin_date =  '".addslashes($this->begin_date)."',
				end_date = '".addslashes($this->end_date)."',
				public_opening = '".addslashes($this->public_opening)."'
				WHERE id = '".$this->id."'
				");
			if ($update) {
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}

/******************************************************
**
** Traitement des DATA zoning en BD
**
******************************************************/
	function zoningManagement($action=''){
		if ($action == 'insert') {
			$insert = requete_sql("UPDATE exhibit SET art_zoning = '".$this->art_zoning."' WHERE id = '".$this->id."'");
			if ($insert) {
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		elseif ($action == 'delete') {
			$delete = requete_sql("UPDATE exhibit SET art_zoning = NULL WHERE id = '".$this->id."'");
			if ($delete) {
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}


/******************************************************
**
** CACHER L'EXPOSITION AUX UTILISATEURS BASIQUES
**
******************************************************/

	function hideExhibit(){
		$this->setVisible('0');
		$res = requete_sql("UPDATE exhibit SET visible = '".$this->visible."' WHERE id = '".$this->id."' ");
		if ($res) {
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

/******************************************************
**
** RENDRE VISIBLE L'EXPOSITION AUX UTILISATEURS BASIQUES
**
******************************************************/

	function publishExhibit(){
		$this->setVisible('1');
		$res = requete_sql("UPDATE exhibit SET visible = '".$this->visible."' WHERE id = '".$this->id."' ");
		if ($res) {
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
/******************************************************
**
** SUPPRIMER DEFINITIVEMENT L'EXPOSITION
**
******************************************************/

	function deleteExhibit(){
		$cleanText = requete_sql("DELETE FROM textual_content_exhibit WHERE exhibit_id ='".$this->id."' ");
		$cleanEvent = requete_sql("DELETE FROM event WHERE exhibit_id ='".$this->id."' ");
		if ($cleanText && $cleanEvent) {
			$delete = requete_sql("DELETE FROM exhibit WHERE id ='".$this->id."' ");
			if ($delete) {
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}
	}

/******************************************************
**
** ARTISTES LIES
** Enregistre la liste des artistes associés à l'expo
**
******************************************************/

	function linkExposedArtist($array){
		$artistLinkedList = $this->getArtistExposed();
		$comparList = array();
		foreach ($artistLinkedList as $art=>$identity) {
			$id = $art;
			array_push($comparList, $id);
		};
		$differentPlus = array_diff($array, $comparList);
		$differentMinus = array_diff($comparList, $array);
		$result = FALSE;
		if( empty($differentPlus) && empty($differentMinus) ){
			$result = TRUE;
		}
		else{
			foreach ($differentMinus as $dm) {
				$delete = requete_sql("DELETE FROM artist_exposed WHERE exhibit_id = '".$this->id."' AND artist_id = '".$dm."' ");
				if ($delete) {
					$result = TRUE;
				}
				else{
					$result = FALSE;
				}
			}
			foreach ($differentPlus as $dp) {
				$insert = requete_sql("INSERT INTO artist_exposed VALUES(NULL, '".$this->id."', '".$dp."' )");
				if ($insert) {
					$result = TRUE;
				}
				else{
					$result = FALSE;
				}
			}
		}
		if($result != TRUE){
			return FALSE;
		}
		else{
			return TRUE;
		}

	}

/******************************************************
**
** OEUVRES LIEES
** Enregistre la liste des oeuvres associées à l'expo
**
******************************************************/

	function linkDisplayedArtwork($array){
		$artworkLinkedList = $this->getArtworkDisplayed();
		$comparList = array();
		foreach ($artworkLinkedList as $art) {
			$id = $art['artwork_id'];
			array_push($comparList, $id);
		};
		$differentPlus = array_diff($array, $comparList);
		$differentMinus = array_diff($comparList, $array);
		$result = FALSE;
		if( empty($differentPlus) && empty($differentMinus) ){
			$result = TRUE;
		}
		else{
			foreach ($differentMinus as $dm) {
				$delete = requete_sql("DELETE FROM artwork_displayed WHERE exhibit_id = '".$this->id."' AND artwork_id = '".$dm."' ");
				if ($delete) {
					$result = TRUE;
				}
				else{
					$result = FALSE;
				}
			}
			foreach ($differentPlus as $dp) {
				$insert = requete_sql("INSERT INTO artwork_displayed VALUES(NULL, '".$this->id."', '".$dp."' )");
				if ($insert) {
					$result = TRUE;
				}
				else{
					$result = FALSE;
				}
			}
		}
		if($result != TRUE){
			return FALSE;
		}
		else{
			return TRUE;
		}

	}


/******************************************************
**
** FORMULAIRE
** Infos générales de l'expo
**
******************************************************/
	function formInfos($target, $action){
		?>
		<form method="POST" action="<?= $target ?>" class="form-horizontal clearfix">
				<div class="form-group form-group-lg">
					<label for="title" class="control-label col-sm-3">Titre de l'exposition :</label>
					<div class="col-sm-9">
					<input type="text" name="title" value="<?= $this->title ?>" class="form-control" required <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?'readonly':''; ?> />
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="control-label col-sm-3"></label>
					<div class="col-sm-9">
					<div class="row">
						<div class="form-group form-group-lg col-sm-6">
							<label for="begin_date" class="control-label col-sm-4">Début :</label>
							<div class="col-sm-8">
							<input type="text" name="begin_date" id="begin_date" class="form-control" value="<?= dateFormat($this->begin_date) ?>" placeholder="ex. : 02/02/2017" required <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?'readonly':''; ?> />
							</div>
						</div>
						<div class="form-group form-group-lg col-sm-6">
							<label for="end_date" class="control-label col-sm-4">Fin :</label>
							<div class="col-sm-8">
							<input type="text" name="end_date" id="end_date" class="form-control" value="<?= dateFormat($this->end_date) ?>" placeholder="ex. : 02/02/2017" required <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?'readonly':''; ?> />
							</div>
						</div>
					</div>
					</div>
				</div>
	
				<div class="form-group form-group-lg">
					<label for="public_opening" class="control-label col-sm-3">Horaires d'ouverture :</label>
					<div class="col-sm-9">
					<input type="text" name="public_opening" class="form-control" value="<?= $this->public_opening ?>" placeholder="Ex. : Ouvert du lundi au vendredi de 9h à 12h30 et de..." required <?= !empty($this->getId()) &&$this->getVisible() == FALSE?'disabled':''; ?> <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?'readonly':''; ?> />
					</div>
				</div>
				<input type="hidden" name="id" value="<?= $this->id ?>">
				<input type="submit" value="<?= $action; ?>" class="btn btn-default pull-right" <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?'disabled':''; ?> />
		</form>
		<?php
	}


/**********************************************************
**
** FORMULAIRE DES TEXTES COMPLEMENTAIRES
**
**********************************************************/
	function formText($target, $action){
		?>
			<form method="POST" action="<?= $target ?>" class="form-horizontal clearfix" id="formTextualContent">
				
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#french">Français</a></li>
					<li><a data-toggle="tab" href="#english">Anglais</a></li>
				    <li><a data-toggle="tab" href="#german">Allemand</a></li>
					<li><a data-toggle="tab" href="#russian">Russe</a></li>
					<li><a data-toggle="tab" href="#chinese">Chinois</a></li>
				</ul>
				<div class="tab-content">
					<div id="french" class="tab-pane fade in active">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>  >
						<div class="form-group form-group-lg">
							<label for="categoryfrench" class="control-label col-lg-2 col-md-2 col-sm-3">Catégorie :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<input type="text" name="categoryFrench" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getFrenchCategory()->getContent():'' ?>" <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?'readonly':''; ?> >
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="summaryfrench" class="control-label col-lg-2 col-md-2 col-sm-3">Résumé :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<textarea name="summaryFrench" <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?' class="form-control" readonly':'class="form-control textarea-avaible"'; ?> ><?= !empty($this->getTextualContent())?$this->getFrenchSummary()->getContent():'' ?></textarea>
							</div>
						</div>
					</fieldset>
					</div>
					<div id="english" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="categoryEnglish" class="control-label col-lg-2 col-md-2 col-sm-3">Catégorie :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<input type="text" name="categoryEnglish" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getEnglishCategory()->getContent():'' ?>" <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?'readonly':''; ?> >
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="summaryEnglish" class="control-label col-lg-2 col-md-2 col-sm-3">Résumé :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<textarea name="summaryEnglish" <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?' class="form-control" readonly':'class="form-control textarea-avaible"'; ?> ><?= !empty($this->getTextualContent())?$this->getEnglishSummary()->getContent():'' ?></textarea>
							</div>
						</div>
					</fieldset>
					</div>
					<div id="german" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="categoryGerman" class="control-label col-lg-2 col-md-2 col-sm-3">Catégorie :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<input type="text" name="categoryGerman" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getGermanCategory()->getContent():'' ?>" <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?'readonly':''; ?> >
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="summaryGerman" class="control-label col-lg-2 col-md-2 col-sm-3">Résumé :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<textarea name="summaryGerman" <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?' class="form-control" readonly':'class="form-control textarea-avaible"'; ?> ><?= !empty($this->getTextualContent())?$this->getGermanSummary()->getContent():'' ?></textarea>
							</div>
						</div>
					</fieldset>
					</div>
					<div id="russian" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="categoryRussian" class="control-label col-lg-2 col-md-2 col-sm-3">Catégorie :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<input type="text" name="categoryRussian" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getRussianCategory()->getContent():'' ?>" <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?'readonly':''; ?> >
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="summaryRussian" class="control-label col-lg-2 col-md-2 col-sm-3">Résumé :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<textarea name="summaryRussian" <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?' class="form-control" readonly':'class="form-control textarea-avaible"'; ?> ><?= !empty($this->getTextualContent())?$this->getRussianSummary()->getContent():'' ?></textarea>
							</div>
						</div>
					</fieldset>
					</div>
					<div id="chinese" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="categoryChinese" class="control-label col-lg-2 col-md-2 col-sm-3">Catégorie :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<input type="text" name="categoryChinese" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getChineseCategory()->getContent():'' ?>" <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?'readonly':''; ?> >
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="summaryChinese" class="control-label col-lg-2 col-md-2 col-sm-3">Résumé :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<textarea name="summaryChinese" <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?' class="form-control" readonly':'class="form-control textarea-avaible"'; ?> ><?= !empty($this->getTextualContent())?$this->getChineseSummary()->getContent():'' ?></textarea>
							</div>
						</div>
					</fieldset>				
					</div>
				<input type="hidden" name="id" value="<?= isset($this)?$this->getId():'' ?>">
				<input type="submit" value="<?= $action; ?>" class="btn btn-default pull-right" <?= !empty($this->getId()) &&  $this->getVisible() == FALSE?'disabled':''; ?> <?= !empty($this->id) && $this->getEndDate() < date('Y-m-d')?'disabled':''; ?> <?= empty($this->id)?'disabled':''; ?> />
				</div>
			
			</form>
	<?php
	}

/******************************************************
**
** RETOURNE L'EXPOSITION ACTUELLE SOUS FORME DE TABLEAU
**
******************************************************/

	static function currentExhibit(){
		$res = requete_sql("SELECT id FROM exhibit WHERE begin_date <= now() AND end_date >= now() ");
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$currentExhibit = array();
		foreach ($res as $exhibit) {
			$exhibit = new Exhibit($exhibit['id']);
			array_push($currentExhibit, $exhibit);
		}
		return $currentExhibit;
	}

/******************************************************
**
** RETOURNE LA LISTE DES EXPOS A VENIR
**
******************************************************/

	static function listNextExhibit(){
		$res = requete_sql("SELECT id FROM exhibit WHERE begin_date > now() AND visible = TRUE ORDER BY begin_date ASC");
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$list = array();
		foreach ($res as $exhibit) {
			$exhibit = new Exhibit($exhibit['id']);
			array_push($list, $exhibit);
		}
		return $list;
	}



/******************************************************
**
** RETOURNE LA LISTE DES EXPOS PASSEES SOUS FORME DE TABLEAU
**
******************************************************/

	static function listPassedExhibit(){
		$res = requete_sql("SELECT id FROM exhibit WHERE end_date < now() ORDER BY end_date ASC");
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$list = array();
		foreach ($res as $exhibit) {
			$exhibit = new Exhibit($exhibit['id']);
			array_push($list, $exhibit);
		}
		return $list;
	}

/******************************************************
**
** RETOURNE LA LISTE DES EXPOS CACHEES
**
******************************************************/

	static function listHiddenExhibit(){
		$res = requete_sql("SELECT id FROM exhibit WHERE visible = FALSE ORDER BY creation_date ASC");
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$list = array();
		foreach ($res as $exhibit) {
			$exhibit = new Exhibit($exhibit['id']);
			array_push($list, $exhibit);
		}
		return $list;
	}


/******************************************************
**
** CONTROLE SUR LES TEXTES EN ANGLAIS
**
******************************************************/

	function checkTrad($language){

		if (!empty($this->getTextualContent())) {
			switch ($language) {
				case 'english':
					if (!empty($this->getEnglishCategory()->getContent()) && !empty($this->getEnglishSummary()->getContent())) {
					return TRUE;
					}
					else{
						return FALSE;
					}
					break;
				
				case 'german':
					if (!empty($this->getGermanCategory()->getContent()) && !empty($this->getGermanSummary()->getContent())) {
					return TRUE;
					}
					else{
						return FALSE;
					}
					break;
				
				case 'russian':
					if (!empty($this->getRussianCategory()->getContent()) && !empty($this->getRussianSummary()->getContent())) {
					return TRUE;
					}
					else{
						return FALSE;
					}
					break;
				
				case 'chinese':
					if (!empty($this->getChineseCategory()->getContent()) && !empty($this->getChineseSummary()->getContent())) {
					return TRUE;
					}
					else{
						return FALSE;
					}
					break;
				
				default:
					return FALSE;
					break;
			}
		}
		else{
			return FALSE;
		}		
	}

	function totalArtistExposed(){
		$count = count($this->getArtistExposed());
		return $count;
	}

	function totalArtworkDisplayed(){
		$count = count($this->getArtworkDisplayed());
		return $count;
	}

	function totalAvaibleArtwork(){
		$res = requete_sql("SELECT COUNT(artwork_displayed.artwork_id) AS total FROM artwork_displayed LEFT JOIN artwork ON artwork_displayed.artwork_id = artwork.id WHERE artwork.disponibility = '1' AND artwork_displayed.exhibit_id = '".$this->id."' ");
		$res = $res->fetch(PDO::FETCH_ASSOC);
		$total = $res['total'];
		return $total;
	}

	function listAvailableArtwork(){
		$res = requete_sql("
			SELECT artwork.artist_id AS artist_id,
			GROUP_CONCAT(artwork_displayed.artwork_id) AS artwork
			FROM artwork_displayed 
			LEFT JOIN artwork ON artwork_displayed.artwork_id = artwork.id 
			LEFT JOIN artist ON artwork.artist_id = artist.id 
			WHERE artwork_displayed.exhibit_id = '".$this->id."' 
			AND artist.visible = TRUE
			AND artwork.visible = TRUE
			GROUP BY artist_id
			ORDER BY CONCAT(artist.surname, artist.alias) ASC
			");
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$list = array();
        foreach ($res as $key => $value) {
            $artist = new Artist($value['artist_id']);
            if(strpos($value['artwork'],',') != FALSE ){
                $id = explode(',', $value['artwork']);
                $listArtwork = array();
                foreach ($id as $key => $id) {
                    $artwork = new Artwork($id);
                    $list[$artist->getIdentity()][$key] = $artwork;
                }
            }
            else{
                $artist = new Artist($value['artist_id']);
                $artwork = new Artwork($value['artwork']);
                $list[$artist->getIdentity()][$key] = $artwork;
            }
            
        }

        return $list;

	}		
		
}