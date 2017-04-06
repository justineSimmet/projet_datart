<?php 

/* ---------------------------
  DatArt
  Creation Date : 18/02/2017
  classes/artist.php
  author : ApplePie Cie
---------------------------- */

// creation de ma class artist avec les attributs qu'on lui a crée en bdd
class Artist{  
	private $id;
	private $surname;
	private $name;
	private $alias;
	private $photographic_portrait;
	private $creation_date;
	private $visible;
	private $artwork;

// Le constructeur est la pour initialiser les attributs lors de la création d'un objet(artist)
	function __construct($id = ''){  
		
		if($id != 0){
			$res = requete_sql("SELECT * FROM artist WHERE id='".$id."'");
    		$artist = $res->fetch(PDO::FETCH_ASSOC);
    		$this->id = $artist['id'];
    		$this->surname = $artist['surname'];
    		$this->name = $artist['name'];
    		$this->alias = $artist['alias'];
    		$this->photographic_portrait = $artist['photographic_portrait'];
    		$this->creation_date = $artist['creation_date'];
    		$this->visible = $artist['visible'];
    		$this->textual_content = array();
    		$text = requete_sql("SELECT id, language, subject FROM textual_content_artist WHERE artist_id = '".$id."' ");

    		        if (count($text) !== 0) {

          while ($t = $text->fetch(PDO::FETCH_ASSOC)) {

            if ($t['language'] == 'french') {
              
                if ($t['subject'] == 'biography') {
                array_push($this->textual_content, new ArtistFrenchBiography($t['id']));
              }
              else{
                array_push($this->textual_content, new ArtistFrenchNote($t['id']));
              }
            }
            elseif ($t['language'] == 'english') {
              
              if ($t['subject'] == 'biography') {
                array_push($this->textual_content, new ArtistEnglishBiography($t['id']));
              }
              else{
                array_push($this->textual_content, new ArtistEnglishNote($t['id']));
              }   
            }
              elseif ($t['language'] == 'german') {
              
              if ($t['subject'] == 'biography') {
                array_push($this->textual_content, new ArtistGermanBiography($t['id']));
              }
              else{
                array_push($this->textual_content, new ArtistGermanNote($t['id']));
              }   
            }
              elseif ($t['language'] == 'russian') {
              
              if ($t['subject'] == 'biography') {
                array_push($this->textual_content, new ArtistRussianBiography($t['id']));
              }
              else{
                array_push($this->textual_content, new ArtistRussianNote($t['id']));
              }   
            }
            elseif ($t['language'] == 'chinese') {
              
              if ($t['subject'] == 'biography') {
                array_push($this->textual_content, new ArtistChineseBiography($t['id']));
              }
              else{
                array_push($this->textual_content, new ArtistChineseNote($t['id']));
              }   
            }               
          }
        }
        else{
          $this->textual_content = array();
        }
        $this->artwork = array();
        $artwork = requete_sql("SELECT id FROM artwork WHERE artist_id = '".$this->id."' AND visible = TRUE ");
        if (count($text) !== 0) {
          while ($art = $artwork->fetch(PDO::FETCH_ASSOC)){
            array_push($this->artwork, new Artwork($art['id']));
          }
        }
    }
    else{
      $this->textual_content = array();
      $this->artwork = array();
    }
  }

	function getId(){
	    return $this->id;
	}
	
	function setSurname($surname){
		$this->surname = $surname;
		return TRUE;
	}

	function getSurname(){
	    return $this->surname;
	}
	
	function setName($name){
		$this->name = $name;
		return TRUE;
	}

	function getName(){
	    return $this->name;
	}
	
	function setAlias($alias){
		$this->alias = $alias;
		return TRUE;
	}

	function getAlias(){
	    return $this->alias;
	}
	
	function setPhotographicPortrait($photographic_portrait){
		$this->photographic_portrait = $photographic_portrait;
		return TRUE;
	}

	function getPhotographicPortrait(){
	    return $this->photographic_portrait;
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

	function getFrenchBiography(){
		return $this->textual_content[0];
	}

	function getFrenchNote(){
		return $this->textual_content[1];
	}

	function getEnglishBiography(){
		return $this->textual_content[2];
	}

	function getEnglishNote(){
		return $this->textual_content[3];
	}

	function getGermanBiography(){
		return $this->textual_content[4];
	}

	function getGermanNote(){
		return $this->textual_content[5];
	}

	function getRussianBiography(){
		return $this->textual_content[6];
	}

	function getRussianNote(){
		return $this->textual_content[7];
	}

	function getChineseBiography(){
		return $this->textual_content[8];
	}

	function getChineseNote(){
		return $this->textual_content[9];
	}

	function getIdentity(){
    if (!empty($this->name) && !empty($this->surname)) {
      if (!empty($this->alias)) {
        $retour = $this->alias.' ('.$this->surname.' '.$this->name.')';
        return $retour; 
      }
      else{
        $retour = $this->surname.' '.$this->name;
        return $retour;
      }
    }
    else{
      $retour = $this->alias;
      return $retour;
    }
  }

  function getArtwork(){
    return $this->artwork;
  }

  /*
	Retourne la liste des expos ou l'artiste a été présenté
  */
	function listArtistExhibit(){
		$res = requete_sql("SELECT exhibit_id FROM artist_exposed WHERE artist_id = '".$this->id."'");
		$listExhibit = array();
		while ($exhibit = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($listExhibit, new Exhibit($exhibit['exhibit_id']));
        }
        return $listExhibit;
	}



/***************************************************************************



							Synchro avec la BDD


* comme tu me l'as expliqué, cela sert:
soit à inserer une nouvelle entrée en bdd si on ne recoit pas d'id
soit à faire un update des données de l'id reçue
***************************************************************************/


	function synchroDb(){
		if (empty($this->id)) { //  si l'id est vide alors on crée une variable create avec une requete sql pour inserer une nouvelle entrée
			$create = requete_sql(" 
				INSERT INTO artist 
				VALUES(
					NULL,
					'".addslashes($this->surname)."',
					'".addslashes($this->name)."',
					'".addslashes($this->alias)."',
					NULL,
					now(),
					TRUE 
				)"); // le true est pour le $visible qui retourne un booléen
			if ($create) { // si $create est true
				$this->id = $create;
				return $this->id; // alors tu retournes l'id que l'on vient de créer
			}
			else{
				return FALSE;
			}
		}
		else{ // si on un id alors on fait une MAJ/update
			$update = requete_sql("
				UPDATE artist SET 
				surname = '".addslashes($this->surname)."',
				name = '".addslashes($this->name)."',
				alias = '".addslashes($this->alias)."'
				WHERE id = '".$this->id."'
				");
			// je ne fais pas d'update sur creation_date car on a pas besoin de la changer et la même sur le visible
			if ($update) { // si l'update est true et dc a fonctionné
				return TRUE; // alors je retourne true;
			}
			else{ // sinon c'est faux
				return FALSE;
			}
		}
	}


/***************************************************************************


							Liste Artiste


* retourne la liste de tout les artistes
***************************************************************************/


  static function listArtist(){
    $listArtist = requete_sql("SELECT id, CONCAT(alias,surname) AS identity FROM artist WHERE visible = TRUE ORDER BY identity ASC");
    $listArtist = $listArtist->fetchAll(PDO::FETCH_ASSOC);
    $tabList = array();
    foreach ($listArtist as $artist){
      $artist = new Artist($artist['id']);
      array_push($tabList, $artist);
    }
    return $tabList;
  }

/***************************************************************************


							Liste oeuvres exposées // Artiste



***************************************************************************/

	function listDisplayedArtwork($targetExhibit){
		$res = requete_sql("SELECT artwork_displayed.artwork_id AS artwork_id FROM artwork_displayed
                LEFT JOIN artwork ON artwork_id = artwork.id
                WHERE artwork.artist_id = '".$this->id."'
                AND artwork_displayed.exhibit_id = '".$targetExhibit."'
                ORDER BY artwork.artwork_title ASC
                ");
        $listArtworks = array();
        while ($art = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($listArtworks, new Artwork($art['artwork_id']));
        }
        return $listArtworks;
    }

/***************************************************************************



							Form créa artiste


***************************************************************************/	


	function formInfos($target, $action=''){
	?>
		<form class="form-horizontal clearfix" action=<?= $target ?> method="POST">
			<fieldset <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> >
			
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-3" for="surname">Nom :</label>
					<div class="col-sm-9">
					<input class="form-control" type="text" name="surname" id="surname" value="<?= $this->surname; ?>" />
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="control-label col-sm-3" for="name">Prénom :</label>
					<div class="col-sm-9">				
					<input class="form-control" type="text" name="name" id="name" value="<?= $this->name; ?>" />
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="control-label col-sm-3" for="alias">Pseudonyme :</label>
					<div class="col-sm-9">
					<input class="form-control" type="text" name="alias" id="alias" value="<?= $this->alias; ?>" />
					</div>
				</div>

			</fieldset>	

					<input type="hidden" name="id" value="<?= $this->id; ?>" />
					<input class="btn btn-default pull-right" type="submit" value="<?= $action; ?>" <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> />
		</form>
		<?php 

	}


	function formText($target, $action=''){
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
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="biographyFrench" class="control-label col-lg-2 col-md-2 col-sm-3">Biographie :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
								<textarea name="biographyFrench" <?= empty($this->getId()) || $this->getVisible() == FALSE?'class="form-control" disabled':'class="form-control textarea-avaible"'; ?>><?= !empty($this->getTextualContent())?$this->getFrenchBiography()->getContent():'';?></textarea>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="noteFrench" class="control-label col-lg-2 col-md-2 col-sm-3">Mot de l'artiste :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
								<textarea name="noteFrench" <?= empty($this->getId()) || $this->getVisible() == FALSE?'class="form-control" disabled':'class="form-control textarea-avaible"'; ?>><?= !empty($this->getTextualContent())?$this->getFrenchNote()->getContent():'';?></textarea>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="english" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="biographyEnglish" class="control-label col-lg-2 col-md-2 col-sm-3">Biographie :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
								<textarea name="biographyEnglish" <?= empty($this->getId()) || $this->getVisible() == FALSE?'class="form-control" disabled':'class="form-control textarea-avaible"'; ?>><?= !empty($this->getTextualContent())?$this->getEnglishBiography()->getContent():'';?></textarea>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="noteEnglish" class="control-label col-lg-2 col-md-2 col-sm-3">Mot de l'artiste :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
								<textarea name="noteEnglish" <?= empty($this->getId()) || $this->getVisible() == FALSE?'class="form-control" disabled':'class="form-control textarea-avaible"'; ?>><?= !empty($this->getTextualContent())?$this->getEnglishNote()->getContent():'';?></textarea>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="german" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="biographyGerman" class="control-label col-lg-2 col-md-2 col-sm-3">Biographie :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
								<textarea name="biographyGerman" <?= empty($this->getId()) || $this->getVisible() == FALSE?'class="form-control" disabled':'class="form-control textarea-avaible"'; ?>><?= !empty($this->getTextualContent())?$this->getGermanBiography()->getContent():'';?></textarea>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="noteGerman" class="control-label col-lg-2 col-md-2 col-sm-3">Mot de l'artiste :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
								<textarea name="noteGerman" <?= empty($this->getId()) || $this->getVisible() == FALSE?'class="form-control" disabled':'class="form-control textarea-avaible"'; ?>><?= !empty($this->getTextualContent())?$this->getGermanNote()->getContent():'';?></textarea>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="russian" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
						<div class="form-group form-group-lg">
							<label for="biographyRussian" class="control-label col-lg-2 col-md-2 col-sm-3">Biographie :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
								<textarea name="biographyRussian" <?= empty($this->getId()) || $this->getVisible() == FALSE?'class="form-control" disabled':'class="form-control textarea-avaible"'; ?>><?= !empty($this->getTextualContent())?$this->getRussianBiography()->getContent():'';?></textarea>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="noteRussian" class="control-label col-lg-2 col-md-2 col-sm-3">Mot de l'artiste :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
								<textarea name="noteRussian" <?= empty($this->getId()) || $this->getVisible() == FALSE?'class="form-control" disabled':'class="form-control textarea-avaible"'; ?>><?= !empty($this->getTextualContent())?$this->getRussianNote()->getContent():'';?></textarea>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="chinese" class="tab-pane fade">
					<fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?> >
						<div class="form-group form-group-lg">
							<label for="biographyChinese" class="control-label col-lg-2 col-md-2 col-sm-3">Biographie :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
								<textarea name="biographyChinese" <?= empty($this->getId()) || $this->getVisible() == FALSE?'class="form-control" disabled':'class="form-control textarea-avaible"'; ?>><?= !empty($this->getTextualContent())?$this->getChineseBiography()->getContent():'';?></textarea>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label for="noteChinese" class="control-label col-lg-2 col-md-2 col-sm-3">Mot de l'artiste :</label>
							<div class="col-lg-10 col-md-10 col-sm-12">
								<textarea name="noteChinese" <?= empty($this->getId()) || $this->getVisible() == FALSE?'class="form-control" disabled':'class="form-control textarea-avaible"'; ?>><?= !empty($this->getTextualContent())?$this->getChineseNote()->getContent():'';?></textarea>
							</div>
						</div>
					</fieldset>
				</div>
				<input type="hidden" name="artistId" value="<?= isset($this)?$this->getId():'' ?>">
				<input type="submit" value="<?= $action; ?>" class="btn btn-default pull-right" <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?> />
			</div>
		</form>	

	<?php

	}

	function formPhoto($target, $action=''){
	?>
		<form action=<?= $target ?> method="POST" enctype="multipart/form-data">
			<fieldset <?= (!empty($this->getId()) && $this->getVisible() == FALSE) || empty($this->getId()) ?'disabled':''; ?> >
				<label for="file">Fichier (JPG | max. 2 Mo) :</label><br>
				<input type="hidden" name="taille Maxi" value="2097152" />
				<input type="file" name="fichier" class="form-control"/>
			<input type="hidden" name="action" value="addArtistPicture">
			<input type="hidden" name="artistId" value="<?= isset($this)?$this->getId():'' ?>"><br>
			<input type="submit" value="<?= $action; ?>" class="btn btn-default pull-right" <?= empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> />
			</fieldset>
		</form>	
	<?php


	}



/***************************************************************************



							Visibilité fiche artiste


***************************************************************************/	

	function publishArtist(){
		$this->setVisible('1');
		$res = requete_sql("UPDATE artist set visible ='".$this->visible."' WHERE id='".$this->id."'");
		if ($res) {
			return TRUE;
		}
		else{
			return FALSE;
		}
	}



/***************************************************************************



							Invisibilité d'une fiche artiste


***************************************************************************/	

	function hideArtist(){
		$this->setVisible('0');
		$res = requete_sql("UPDATE artist set visible ='".$this->visible."' WHERE id='".$this->id."'");
		if ($res) {
			return TRUE;
		}
		else{
			return FALSE;
		}

	}

/***************************************************************************



							Supprimer définitivement un artiste


***************************************************************************/


	function deleteArtist(){
		$deleteText = requete_sql("DELETE FROM textual_content_artist WHERE artist_id='".$this->id."'");
		if ($deleteText) {
			$delete = requete_sql("DELETE FROM artist WHERE id='".$this->id."'");
		
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


/***************************************************************************



				Liste des artistes cachés (en cours de suppression)



***************************************************************************/


	static function listHidenArtist(){
		$res = requete_sql("SELECT id FROM artist WHERE visible = FALSE ORDER BY name DESC");
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$list = array();
		foreach ($res as $artist) {
			$artist = new Artist($artist['id']);
			array_push($list, $artist);
		}
		return $list;
	}


/***************************************************************************



							Upload photo



***************************************************************************/


	function synchroPicture(){

		$updatePicture = requete_sql("UPDATE artist SET photographic_portrait = '".$this->photographic_portrait."' WHERE id = '".$this->id."'");
		
		if ($updatePicture) {
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

/******************************************

	 Comparaison de liste

*******************************************/

	static function compareList($listA, $listB)
	{
	  $clone = array();
	  $count = count($listB);
	  foreach ($listA as $list) {
	    for ($i=0; $i < $count ; $i++) { 
	      if ($list->getId() == $listB[$i]->getId()) {
	        array_push($clone, $list->getId());
	      }
	    }
	  }

  	return $clone;
	}


/**************************************************
	
	liste totale des oeuvres d'un artiste


***************************************************/ 


	function totalArtistArtwork(){
		$count = count($this->getArtwork());
		return $count;
	}


/**************************************************
	
	verif' presence bio+note d'un artiste


***************************************************/ 

	function checkBioNote(){
		if (!empty($this->getTextualContent())) {
			if (!empty($this->getFrenchBiography()->getContent()) && !empty($this->getFrenchNote()->getContent())) {
			
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



/**************************************************
	
	verif' trad de la bio d'un artiste


***************************************************/ 


	function checkTradArtist($language){

		if (!empty($this->getTextualContent())) {
			switch ($language) {
				case 'english':
					if (!empty($this->getEnglishBiography()->getContent())) {
					return TRUE;
					}
					else{
						return FALSE;
					}
					break;
				
				case 'german':
					if (!empty($this->getGermanBiography()->getContent())) {
					return TRUE;
					}
					else{
						return FALSE;
					}
					break;
				
				case 'russian':
					if (!empty($this->getRussianBiography()->getContent())) {
					return TRUE;
					}
					else{
						return FALSE;
					}
					break;
				
				case 'chinese':
					if (!empty($this->getChineseBiography()->getContent())) {
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

