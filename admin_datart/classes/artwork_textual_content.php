<?php
/* ---------------------------
	DatArt
	Creation Date : 19/03/2017
	classes/exhibit_textual_content.php
	author : ApplePie Cie
---------------------------- */

class ArtworkText{
	protected $id;
	protected $artwork_id;
	protected $language;
	protected $subject;
	protected $content;

	protected function __construct($id, $language, $subject){
			$res= requete_sql("SELECT * FROM textual_content_artwork WHERE id = '".$id."' ");
			$artwork = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $artwork['id'];
			$this->artwork_id = $artwork['artwork_id'];
			$this->language = $language;
			$this->subject = $subject;
			$this->content = $artwork['content'];
	}
	function getId(){
		return $this->id;
	}

	function setArtworkId($artwork_id){
		$this->artwork_id = $artwork_id;
		return TRUE;
	}

	function getArtworkId(){
		return $this->artwork_id;
	}

	function setContent($content){
		$this->content = $content;
		return TRUE;
	}

	function getContent(){
		return $this->content;
	}

	function synchroDb(){
		if (empty($this->id)) {
			$create = requete_sql("INSERT INTO textual_content_artwork 
				VALUES(
				NULL,
				'".addslashes($this->artwork_id)."',
				'".addslashes($this->language)."',
				'".addslashes($this->subject)."',
				'".addslashes($this->content)."'
				)");
			if($create){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else{
			$update = requete_sql("
				UPDATE textual_content_artwork SET
				content = '".addslashes($this->content)."' 
				WHERE id = '".$this->id."'			
				");
			if ($update){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}

}

class ArtworkFrenchCharacteristic extends ArtworkText{

	function __construct($id = 0){
		parent::__construct($id, 'french', 'characteristic_feature');
	}
}


class ArtworkFrenchMain extends ArtworkText{	

	function __construct($id = 0){
		parent::__construct($id, 'french', 'main_text');
	}
}


class ArtworkEnglishCharacteristic extends ArtworkText{	

	function __construct($id = 0){
		parent::__construct($id, 'english', 'characteristic_feature');
	}
}


class ArtworkEnglishMain extends ArtworkText{	

	function __construct($id = 0){
		parent::__construct($id, 'english', 'main_text');
	}	
}


class ArtworkGermanCharacteristic extends ArtworkText{	

	function __construct($id = 0){
		parent::__construct($id, 'german', 'characteristic_feature');
	}
}


class ArtworkGermanMain extends ArtworkText{	

	function __construct($id = 0){
		parent::__construct($id, 'german', 'main_text');
	}	
}

class ArtworkRussianCharacteristic extends ArtworkText{	

	function __construct($id = 0){
		parent::__construct($id, 'russian', 'characteristic_feature');
	}	
}


class ArtworkRussianMain extends ArtworkText{	

	function __construct($id = 0){
		parent::__construct($id, 'russian', 'main_text');
	}	
}

class ArtworkChineseCharacteristic extends ArtworkText{	

	function __construct($id = 0){
		parent::__construct($id, 'chinese', 'characteristic_feature');
	}	
}


class ArtworkChineseMain extends ArtworkText{	

	function __construct($id = 0){
		parent::__construct($id, 'chinese', 'main_text');
	}	

}