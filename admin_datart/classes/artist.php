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
					'".addslashes($this->photographic_portrait)."',
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
				alias = '".addslashes($this->alias)."',
				photographic_portrait = '".addslashes($this->photographic_portrait)."'
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
		$listArtist = requete_sql("SELECT * FROM artist ");
		$listArtist = $listArtist->fetchAll(PDO::FETCH_ASSOC);
		$tabList = array();
		foreach ($listArtist as $artist){
			$artist = new Artist($artist['id']);
			array_push($tabList, $artist);
		}
		return $tabList;
	}


/***************************************************************************


							Form créa artiste


***************************************************************************/	


	function formInfos($target, $action=''){
	?>
		<form action=<?= $target ?> method="POST">

			<div class="form-group">
				<label for="surname">Nom :</label>
				<input type="text" name="surname" id="surname" value="<?= $this->surname; ?>" />
			</div>

			<div class="form-group">
				<label for="name">Prénom :</label>
				<input type="text" name="name" id="name" value="<?= $this->name; ?>" />
			</div>

			<div class="form-group">
				<label for="alias">Pseudonyme :</label>
				<input type="text" name="alias" id="alias" value="<?= $this->alias; ?>" />
			</div>
				
				<input type="hidden" name="id" value="<?= $this->id; ?>" />
				<input type="submit" value="<?= $action; ?>" />
		</form>
		<?php 

	}


	function formText($target, $action=''){
	?>
		<form action=<?= $target ?> method="POST">
			<textarea name="bio" id="" cols="30" rows="10" <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?> > </textarea>
			<textarea name="text_artist" id="" cols="30" rows="10" <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?> > </textarea>
			<input type="hidden" name="id" value="<?= $this->id; ?>" />
			<input type="submit" value="<?= $action;?>" />
		</form>	
		<br>
	<?php

	}

	function formPhoto($target, $action=''){
	?>
		<form action=<?= $target ?> method="POST">
			<input type="file" name="" <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?> />
			<input type="hidden" name="id" value="<?= $this->id; ?>" />
			<input type="submit" value="<?= $action;?>" />
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



}

