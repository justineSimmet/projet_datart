<?php 

/* ---------------------------
  DatArt
  Creation Date : 05/03/2017
  classes/artwork.php
  author : ApplePie Cie
---------------------------- */

class Artwork{
	private $id;
	private $artist_id;
	private $artwork_title;
	private $dimensions;
	private $disponibility;
	private $reference_number;
	private $artist_request;
	private $qrcode;
	private $creation_date;
	private $visible;
	private $visual;

	function __construct($id=''){
		if ($id != 0) {
			
			$res = requete_sql("SELECT * FROM artwork WHERE id = '".$id."' ");
			$artwork = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $artwork['id'];
			$this->artist_id = $artwork['artist_id'];
			$this->artwork_title = $artwork['artwork_title'];
			$this->dimensions = $artwork['dimensions'];
			$this->disponibility = $artwork['disponibility'];
			$this->reference_number = $artwork['reference_number'];
			$this->artist_request = $artwork['artist_request'];
			$this->qrcode = $artwork['qrcode'];
			$this->creation_date = $artwork['creation_date'];
			$this->visible = $artwork['visible'];
			$this->textual_content = array();
			$text = requete_sql("SELECT id, language, subject FROM textual_content_artwork WHERE artwork_id = '".$id."' ");
			if (count($text) !== 0) {
				while ( $t = $text->fetch(PDO::FETCH_ASSOC)) {
					if ($t['language'] == 'french') {
						if ($t['subject'] == 'characteristic_feature') {
							array_push($this->textual_content, new ArtworkFrenchCharacteristic($t['id']));
						}
						else{
							array_push($this->textual_content, new ArtworkFrenchMain($t['id']));
						}
					}
					elseif ($t['language'] == 'english') {
						if ($t['subject'] == 'characteristic_feature') {
							array_push($this->textual_content, new ArtworkEnglishCharacteristic($t['id']));
						}
						else{
							array_push($this->textual_content, new ArtworkEnglishMain($t['id']));
						}
					}
					elseif ($t['language'] == 'german') {
						if ($t['subject'] == 'characteristic_feature') {
							array_push($this->textual_content, new ArtworkGermanCharacteristic($t['id']));
						}
						else{
							array_push($this->textual_content, new ArtworkGermanMain($t['id']));
						}
					}
					elseif ($t['language'] == 'russian') {
						if ($t['subject'] == 'characteristic_feature') {
							array_push($this->textual_content, new ArtworkRussianCharacteristic($t['id']));
						}
						else{
							array_push($this->textual_content, new ArtworkRussianMain($t['id']));
						}
					}
					elseif ($t['language'] == 'chinese') {
						if ($t['subject'] == 'characteristic_feature') {
							array_push($this->textual_content, new ArtworkChineseCharacteristic($t['id']));
						}
						else{
							array_push($this->textual_content, new ArtworkChineseMain($t['id']));
						}
					}
				}	
			}
			else{
				$this->textual_content = array();
			}
			$this->visual = array();
			$visuals = requete_sql("SELECT id FROM visual WHERE artwork_id = '".$this->id."' ");
			if (count($visuals) !== 0){
				while ( $v = $visuals->fetch(PDO::FETCH_ASSOC)) {
					array_push($this->visual, new Visual($v['id']));
				}
			}
			else{
				$this->visual = array();
			}
		}
		else{
			$this->textual_content = array();
			$this->visual = array();
		}
	}

	function getId(){
		return $this->id;
	}

	function setArtistId($artist_id){
		$this->artist_id = $artist_id;
		return TRUE;
	}

	function getArtistId(){
		return $this->artist_id;
	}

	function setTitle($artwork_title){
		$this->artwork_title = $artwork_title;
		return TRUE;
	}

	function getTitle(){
		return $this->artwork_title;
	}

	function setDimensions($dimensions){
		$this->dimensions = $dimensions;
		return TRUE;
	}

	function getDimensions(){
		return $this->dimensions;
	}

	function setDisponibility($disponibility){
		$this->disponibility = $disponibility;
		return TRUE;
	}

	function getDisponibility(){
		return $this->disponibility;
	}

	function setReferenceNumber($reference_number){
		$this->reference_number = $reference_number;
		return TRUE;
	}

	function getReferenceNumber(){
		return $this->reference_number;
	}

	function setArtistRequest($artist_request){
		$this->artist_request = $artist_request;
		return TRUE;
	}

	function getArtistRequest(){
		return $this->artist_request;
	}

	function setQrCode($qrcode){
		$this->qrcode = $qrcode;
		return TRUE;
	}

	function getQrCode(){
		return $this->qrcode;
	}

	function setCreationDate($creation_date){
		$this->creation_date = $creation_date;
		return TRUE;
	}

	function getCreationDate(){
		return $this->creation_date;
	}

	function setVisible($visible){
		$this->visible = $visible;
		return TRUE;
	}

	function getVisible(){
		return $this->visible;
	}

	function getTextualContent(){
		return $this->textual_content;
	}

	function getFrenchCharacteristic(){
		return $this->textual_content[0];
	}

	function getFrenchMain(){
		return $this->textual_content[1];
	}

	function getEnglishCharacteristic(){
		return $this->textual_content[2];
	}

	function getEnglishMain(){
		return $this->textual_content[3];
	}

	function getGermanCharacteristic(){
		return $this->textual_content[4];
	}

	function getGermanMain(){
		return $this->textual_content[5];
	}

	function getRussianCharacteristic(){
		return $this->textual_content[6];
	}

	function getRussianMain(){
		return $this->textual_content[7];
	}

	function getChineseCharacteristic(){
		return $this->textual_content[8];
	}

	function getChineseMain(){
		return $this->textual_content[9];
	}

	function getVisual(){
		return $this->visual;
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
				INSERT INTO artwork 
				VALUES(
					NULL,
					'".addslashes($this->artist_id)."',
					'".addslashes($this->artwork_title)."',
					'".addslashes($this->dimensions)."',
					'".addslashes($this->disponibility)."',
					NULL,
					'".addslashes($this->artist_request)."',
					NULL,
					now(),
					TRUE
				)");
			if ($create) {
				$artist = new Artist($this->artist_id);
				$artist = substr(str_replace(' ', '', $artist->getIdentity()), 0, 3);
				$title = substr(str_replace(' ', '', $this->artwork_title), 0, 4);
				$ref = $artist.''.$create.''.$title;
				$this->setReferenceNumber(strtolower($ref));
				$reference = requete_sql("UPDATE artwork SET reference_number = '".$this->reference_number."' WHERE id = '".$create."' ");
				if($reference){
					return $create;
				}
				else{
					return FALSE;
				}

			}
			else{
				return FALSE;
			}
		}
		else{
			$update = requete_sql("
				UPDATE artwork SET
				artwork_title = '".addslashes($this->artwork_title)."',
				dimensions =  '".addslashes($this->dimensions)."',
				disponibility = '".addslashes($this->disponibility)."',
				artist_request = '".addslashes($this->artist_request)."'
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
** CACHER L'OEUVRE AUX UTILISATEURS BASIQUES
**
******************************************************/

	function hideArtwork(){
		$this->setVisible('FALSE');
		$res = requete_sql("UPDATE artwork SET visible = ".$this->visible." WHERE id = '".$this->id."' ");
		if ($res) {
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

/******************************************************
**
** RENDRE VISIBLE L'OEUVRE AUX UTILISATEURS BASIQUES
**
******************************************************/

	function publishArtwork(){
		$this->setVisible('TRUE');
		$res = requete_sql("UPDATE artwork SET visible = ".$this->visible." WHERE id = '".$this->id."' ");
		if ($res) {
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	

/******************************************************
**
** SUPPRIMER DEFINITIVEMENT L'OEUVRE
**
******************************************************/

	function deleteArtwork(){
		$cleanText = requete_sql("DELETE FROM textual_content_artwork WHERE artwork_id ='".$this->id."' ");
		if ($cleanText) {
			$cleanDisplay = requete_sql("DELETE FROM artwork_displayed WHERE artwork_id ='".$this->id."' ");
			if ($cleanDisplay) {
				$delete = requete_sql("DELETE FROM artwork WHERE id ='".$this->id."' ");
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
		else{
			return FALSE;
		}
	}
/******************************************************
**
** FORMULAIRE
** Infos générales de l'oeuvre
**
******************************************************/
	function formInfos($target, $action){
		?>
		<form method="POST" action="<?= $target ?>" class="form-horizontal clearfix">
			<fieldset <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> >
				<div class="form-group form-group-lg">
					<label for="artist" class="control-label col-sm-3">Artiste :</label>
					<div class="col-sm-9">
						<select class="form-control" name="artist" <?= !empty($this->getId())?'disabled':''; ?> >
							<option>...</option>
							<?php
							$artistAvaible =  Artist::listArtist();
							foreach ($artistAvaible as $artist) {
							?>
							<option value="<?= $artist->getId(); ?>" <?= (!empty($this->id) && $this->artist_id == $artist->getId()) || (isset($_GET['artist']) && $_GET['artist'] == $artist->getId())?'selected="selected"':''; ?>> <?= $artist->getIdentity(); ?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group form-group-lg">
					<label for="title" class="control-label col-sm-3">Titre de l'oeuvre :</label>
					<div class="col-sm-9">
					<input type="text" name="title" class="form-control" value="<?= isset($this->artwork_title)?$this->getTitle():''; ?>" required />
					</div>
				</div>
				<div class="form-group form-group-lg">
					<div class="col-sm-6">
						<label for="referenceNumber" class="control-label col-sm-6">Numéro de référence :</label>
						<div class="col-sm-6">
						<input type="text" name="referenceNumber" class="form-control" value="<?= isset($this->reference_number)?$this->getReferenceNumber():''; ?>"readonly />
						</div>
					</div>
					<div class="col-sm-6">
						<label for="dimensions" class="control-label col-sm-6">Dimensions (en cm) :</label>
						<div class="col-sm-6">
						<input type="text" name="dimensions" class="form-control" value="<?= isset($this->dimensions)?$this->getDimensions():''; ?>" />
						</div>
					</div>
				</div>
				<div class="form-group form-group-lg">
					<label for="artistRequest" class="control-label col-sm-3">Requêtes de l'artiste :</label>
					<div class="col-sm-9">
					<textarea class="form-control" name="artistRequest"> <?= isset($this->artist_request)?$this->getArtistRequest():''; ?> </textarea>
					</div>
				</div>
				<div class="form-group form-group-lg">
					<label for="disponibility" class="control-label col-sm-3 ">Oeuvre disponible : </label>
					<div class="col-sm-9">
					<label class="radio-inline"><input type="radio" value="<?= TRUE ?>"  type="radio" name="disponibility" <?= $this->getDisponibility()==TRUE?'checked':''; ?>/>Oui</label>
					<label class="radio-inline"><input type="radio" value="<?= FALSE ?>"  type="radio" name="disponibility"  <?= $this->getDisponibility()==FALSE?'checked':''; ?>  <?= empty($this->getId())?'checked':''; ?> />Non</label>
				</div>
			</fieldset>
				<input type="hidden" name="id" value="<?= !empty($this->id)?$this->getId():''; ?>" >
				<input type="submit" value="<?= $action; ?>" class="btn btn-default pull-right">
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
							<label for="characteristicfrench" class="control-label col-lg-2 col-md-2 col-sm-3">Nature :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<input type="text" name="characteristicFrench" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getFrenchCharacteristic()->getContent():'' ?>" <?= !empty($this->id) && $this->getVisible() == FALSE?'readonly':''; ?> >
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="mainfrench" class="control-label col-lg-2 col-md-2 col-sm-3">Description :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<textarea name="mainFrench" <?= !empty($this->id) && $this->getVisible() == FALSE?' class="form-control" readonly':'class="form-control textarea-avaible"'; ?> ><?= !empty($this->getTextualContent())?$this->getFrenchMain()->getContent():'' ?></textarea>
							</div>
						</div>
					</fieldset>
					</div>
					<div id="english" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="characteristicEnglish" class="control-label col-lg-2 col-md-2 col-sm-3">Nature :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<input type="text" name="characteristicEnglish" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getEnglishCharacteristic()->getContent():'' ?>" <?= !empty($this->id) && $this->getVisible() == FALSE?'readonly':''; ?> >
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="mainEnglish" class="control-label col-lg-2 col-md-2 col-sm-3">Description :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<textarea name="mainEnglish" <?= !empty($this->id) && $this->getVisible() == FALSE?' class="form-control" readonly':'class="form-control textarea-avaible"'; ?> ><?= !empty($this->getTextualContent())?$this->getEnglishMain()->getContent():''; ?></textarea>
							</div>
						</div>
					</fieldset>
					</div>
					<div id="german" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="characteristicGerman" class="control-label col-lg-2 col-md-2 col-sm-3">Nature :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<input type="text" name="characteristicGerman" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getGermanCharacteristic()->getContent():'' ?>" <?= !empty($this->id) && $this->getVisible() == FALSE?'readonly':''; ?> >
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="mainGerman" class="control-label col-lg-2 col-md-2 col-sm-3">Description :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<textarea name="mainGerman" <?= !empty($this->id) && $this->getVisible() == FALSE?' class="form-control" readonly':'class="form-control textarea-avaible"'; ?> ><?= !empty($this->getTextualContent())?$this->getGermanMain()->getContent():'' ?></textarea>
							</div>
						</div>
					</fieldset>
					</div>
					<div id="russian" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="characteristicRussian" class="control-label col-lg-2 col-md-2 col-sm-3">Nature :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<input type="text" name="characteristicRussian" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getRussianCharacteristic()->getContent():'' ?>" <?= !empty($this->id) && $this->getVisible() == FALSE?'readonly':''; ?> >
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="mainRussian" class="control-label col-lg-2 col-md-2 col-sm-3">Description :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<textarea name="mainRussian" <?= !empty($this->id) && $this->getVisible() == FALSE?' class="form-control" readonly':'class="form-control textarea-avaible"'; ?> ><?= !empty($this->getTextualContent())?$this->getRussianMain()->getContent():'' ?></textarea>
							</div>
						</div>
					</fieldset>
					</div>
					<div id="chinese" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="characteristicChinese" class="control-label col-lg-2 col-md-2 col-sm-3">Nature :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<input type="text" name="characteristicChinese" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getChineseCharacteristic()->getContent():'' ?>" <?= !empty($this->id) && $this->getVisible() == FALSE?'readonly':''; ?> >
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="mainChinese" class="control-label col-lg-2 col-md-2 col-sm-3">Description :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
							<textarea name="mainChinese" <?= !empty($this->id) && $this->getVisible() == FALSE?' class="form-control" readonly':'class="form-control textarea-avaible"'; ?> ><?= !empty($this->getTextualContent())?$this->getChineseMain()->getContent():'' ?></textarea>
							</div>
						</div>
					</fieldset>				
					</div>
				<input type="hidden" name="id" value="<?= isset($this)?$this->getId():'' ?>">
				<input type="submit" value="<?= $action; ?>" class="btn btn-default pull-right" <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> />
				</div>
			
			</form>
	<?php
	}


/******************************************************
**
** LISTES DE TOUTES LES OEUVRES
**
******************************************************/

	static function listArtwork(){
		$listArtist = Artist::listArtist();
		$list = array();
		foreach ($listArtist as $artist) {
			if (!empty($artist->getArtwork()) ){
				array_push($list, $artist);
			}
		}
		return $list;
	}

	static function listHiddenArtwork(){
		$res = requete_sql("SELECT GROUP_CONCAT(artwork.id) AS artwork, artist.id AS artiste FROM artwork LEFT JOIN artist ON artist.id = artwork.artist_id WHERE artwork.visible = FALSE OR artist.visible = FALSE GROUP BY artiste");
		$hidden = $res->fetchAll(PDO::FETCH_ASSOC);
		$list = array();
		foreach ($hidden as $key => $value) {
			$artist = new Artist($value['artiste']);
			if(strpos($value['artwork'],',') != FALSE ){
				$id = explode(',', $value['artwork']);
				$listArtwork = array();
				foreach ($id as $id) {
					$artwork = new Artwork($id);
					array_push($listArtwork, $artwork);
				}
				$list[$artist->getIdentity()][$key] = $listArtwork;
			}
			else{
				$artist = new Artist($value['artiste']);
				$artwork = new Artwork($value['artwork']);
				$list[$artist->getIdentity()][$key] = $artwork;
			}
		}
		return $list;
	}

}