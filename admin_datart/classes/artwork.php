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
	private $additionnal;

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
			$this->additional = array();
			$additionals = requete_sql("SELECT id FROM additional_content WHERE artwork_id = '".$this->id."' ");
			if (count($additionals) !== 0){
				while ( $ad = $additionals->fetch(PDO::FETCH_ASSOC)) {
					array_push($this->additional, new Additional($ad['id']));
				}
			}
			else{
				$this->additional = array();
			}
		}
		else{
			$this->textual_content = array();
			$this->visual = array();
			$this->additional = array();
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
		if (!empty($this->getTextualContent())){
			return $this->textual_content[0];
		}
	}

	function getFrenchMain(){
		if (!empty($this->getTextualContent())){
			return $this->textual_content[1];
		}
	}

	function getEnglishCharacteristic(){
		if (!empty($this->getTextualContent())){
			return $this->textual_content[2];
		}
	}

	function getEnglishMain(){
		if (!empty($this->getTextualContent())){
			return $this->textual_content[3];
		}
	}

	function getGermanCharacteristic(){
		if (!empty($this->getTextualContent())){
			return $this->textual_content[4];
		}
	}

	function getGermanMain(){
		if (!empty($this->getTextualContent())){
			return $this->textual_content[5];
		}
	}

	function getRussianCharacteristic(){
		if (!empty($this->getTextualContent())){
			return $this->textual_content[6];
		}
	}

	function getRussianMain(){
		if (!empty($this->getTextualContent())){
			return $this->textual_content[7];
		}
	}

	function getChineseCharacteristic(){
		if (!empty($this->getTextualContent())){
			return $this->textual_content[8];
		}
	}

	function getChineseMain(){
		if (!empty($this->getTextualContent())){
			return $this->textual_content[9];
		}
	}

	function getVisual(){
		return $this->visual;
	}

	function getPictureOne(){
		foreach ($this->visual as $visual) {
			if ($visual->getDisplayOrder() == 1) {
				return $visual;
			}
		}
	}

	function getPictureTwo(){
		foreach ( $this->visual as $visual) {
			if ($visual->getDisplayOrder() == 2) {
				return $visual;
			}
		}
	}

	function getPictureThree(){
		foreach ( $this->visual as $visual) {
			if ($visual->getDisplayOrder() == 3) {
				return $visual;
			}
		}
	}

	function getAdditional(){
		return $this->additional;
	}

	function getExhibit(){
		$exhibit = requete_sql("SELECT exhibit_id FROM artwork_displayed WHERE artwork_id = '".$this->id."' ");
		$exhibit = $exhibit->fetchAll(PDO::FETCH_ASSOC);
		$listExhibit = array();
		foreach ($exhibit as $ex) {
			array_push($listExhibit, new Exhibit($ex['exhibit_id']));
		}
		return $listExhibit;
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
					".$this->disponibility.",
					NULL,
					'".addslashes($this->artist_request)."',
					'".addslashes($this->qrcode)."',
					now(),
					TRUE
				)");
			if ($create) {
				$artist = new Artist($this->artist_id);
				$artist = substr(preg_replace('/[^a-z]/i', '', $artist->getIdentity()), 0, 3);
				$title = substr(preg_replace('/[^a-z]/i', '', $this->artwork_title), 0, 4);
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
				disponibility = ".$this->disponibility.",
				artist_request = '".addslashes($this->artist_request)."',
				qrcode = '".addslashes($this->qrcode)."'
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
				$cleanAdd = requete_sql("DELETE FROM additional_content WHERE artwork_id ='".$this->id."' ");
				if ($cleanAdd) {
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
		<form method="POST" action="<?= $target ?>" class="form-horizontal clearfix" id="artworkMainForm">
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
					<textarea class="form-control" name="artistRequest"><?= isset($this->artist_request)?$this->getArtistRequest():''; ?></textarea>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<div class="col-sm-9 col-sm-offset-3">
					<div class="switch">
	      				<input type="radio" class="switch-input" name="disponibility" value="<?= '1' ?>" id="disponible" <?= $this->getDisponibility()=='1'?'checked':''; ?> />
	      				<label for="disponible" class="switch-label switch-label-off">Disponible</label>
	      				<input type="radio" class="switch-input" name="disponibility" value="<?= '0' ?>" id="indisponible" <?= $this->getDisponibility()== '0'?'checked':''; ?> <?= empty($this->getId())?'checked':''; ?> />
	      				<label for="indisponible" class="switch-label switch-label-on">Indisponible</label>
	      				<span class="switch-selection"></span>
	      			</div>
      				</div>
    			</div>

			</fieldset>
				<input type="hidden" name="id" value="<?= !empty($this->id)?$this->getId():''; ?>" >
				<?php
					if (empty($this->id)) {
						?>
						<input type="submit" value="<?= $action; ?>" class="btn btn-default pull-right">
						<?php
					}
					else{
						?>
							<button type="button" class="btn btn-default pull-right artworkEdit"><?= $action; ?></button>
						<?php
					}
				?>
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

/**********************************************************
**
** FORMULAIRE DES VISUELS PRINCIPAUX
**
**********************************************************/
	function formMainPictures($number){
		$picture = '';
		if ($number == 'one') {
			$picture = $this->getPictureOne();
		}
		elseif ($number == 'two') {
			$picture = $this->getPictureTwo();
		}
		elseif ($number == 'three') {
			$picture = $this->getPictureThree();
		}
		?>
		<form action="<?= URL_ADMIN ?>picture_process.php" method="POST" enctype="multipart/form-data" class="text-center form-vertical" id="main-<?= $number ?>">
			<fieldset <?= empty($this->id)?'disabled':''; ?> class="clearfix">
				<div id="visual-<?= $number ?>"><img src="<?= !empty($this->getId()) && !empty($picture)?$picture->getTarget():'' ?>" alt="<?= !empty($this->getId()) && !empty($picture)?$picture->getLegend():''; ?>" /></div>
				<div id="caption-<?= $number ?>" class="hidden">
					<p>
						<button type="button" name="cancel" class="btn btn-danger">Annuler</button>
					</p>
				</div>
				<div class="form-group input-image">
					<label for="image-<?= $number ?>" class="control-label btn btn-default">Choisir un visuel</label>
					<input id ="image-<?= $number ?>" class="input-file" type="file" name="image" accept="image/jpeg" value="<?= !empty($this->getId()) && !empty($picture)?$picture->getTarget():'' ?>">
				</div>
				<div class="form-group">
					<label for="legend" class="control-label">Légende du visuel :</label>
					<textarea name="legend" placeholder="Légende" class="form-control"><?= !empty($this->getId()) && !empty($picture)?$picture->getLegend():'' ?></textarea>
				</div>
				<input type="hidden" name="artworkId" value="<?= !empty($this->getId())?$this->getId():''; ?>">
				<input type="hidden" name="action" value="add-picture-<?= $number ?>">
				<input type="hidden" name="pictureId" value="<?= !empty($this->getId()) && !empty($picture)?$picture->getId():'' ?>" >
				<?php
				if(!empty($this->getId()) && !empty($picture)){
					?>
					<button type="button" class="btn btn-danger pull-left delete-main-picture" data-action="deletePicture" data-picture="<?= !empty($this->getId()) && !empty($picture)?$picture->getId():'' ?>">Supprimer</button>
					<?php
				}
				?>
				<button type="submit" class="btn btn-default pull-right"><?= !empty($this->getId()) && !empty($picture)?'Modifier':'Ajouter' ?></button>
			</fieldset>
				<p class="target-file hidden"></p>
				<div class="alert-area-picture"></div>

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

	function checkTrad($language){

		if (!empty($this->getTextualContent())) {
			switch ($language) {
				case 'english':
					if (!empty($this->getEnglishCharacteristic()->getContent()) && !empty($this->getEnglishMain()->getContent())) {
					return TRUE;
					}
					else{
						return FALSE;
					}
					break;
				
				case 'german':
					if (!empty($this->getGermanCharacteristic()->getContent()) && !empty($this->getGermanMain()->getContent())) {
					return TRUE;
					}
					else{
						return FALSE;
					}
					break;
				
				case 'russian':
					if (!empty($this->getRussianCharacteristic()->getContent()) && !empty($this->getRussianMain()->getContent())) {
					return TRUE;
					}
					else{
						return FALSE;
					}
					break;
				
				case 'chinese':
					if (!empty($this->getChineseCharacteristic()->getContent()) && !empty($this->getChineseMain()->getContent())) {
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
}