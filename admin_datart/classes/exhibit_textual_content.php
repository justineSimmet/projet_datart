<?php
/* ---------------------------
	DatArt
	Creation Date : 20/02/2017
	classes/exhibit_textual_content.php
	author : ApplePie Cie
---------------------------- */

class ExhibitText{
	protected $id;
	protected $exhibit_id;
	protected $content;
	protected $language;
	protected $subject;


	protected function __construct($id, $language, $subject){
			$res= requete_sql("SELECT * FROM textual_content_exhibit WHERE id = '".$id."' ");
			$exhibit = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $exhibit['id'];
			$this->exhibit_id = $exhibit['exhibit_id'];
			$this->content = $exhibit['content'];
			$this->language = $language;
			$this->subject = $subject;
	}

	function getId(){
		return $this->id;
	}

	function setExhibitId($exhibit_id){
		$this->exhibit_id = $exhibit_id;
		return TRUE;
	}

	function getExhibitId(){
		return $this->exhibit_id;
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
			$create = requete_sql("INSERT INTO textual_content_exhibit 
				VALUES(
				NULL,
				'".addslashes($this->exhibit_id)."',
				'".addslashes($this->content)."',
				'".addslashes($this->language)."',
				'".addslashes($this->subject)."'
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
				UPDATE textual_content_exhibit SET
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

class ExhibitFrenchCategory extends ExhibitText{

	function __construct($id = 0){
		parent::__construct($id, 'french', 'category');
	}
}


class ExhibitFrenchSummary extends ExhibitText{	

	function __construct($id = 0){
		parent::__construct($id, 'french', 'summary');
	}
}


class ExhibitEnglishCategory extends ExhibitText{	

	function __construct($id = 0){
		parent::__construct($id, 'english', 'category');
	}
}


class ExhibitEnglishSummary extends ExhibitText{	

	function __construct($id = 0){
		parent::__construct($id, 'english', 'summary');
	}	
}


class ExhibitGermanCategory extends ExhibitText{	

	function __construct($id = 0){
		parent::__construct($id, 'german', 'category');
	}
}


class ExhibitGermanSummary extends ExhibitText{	

	function __construct($id = 0){
		parent::__construct($id, 'german', 'summary');
	}	
}

class ExhibitRussianCategory extends ExhibitText{	

	function __construct($id = 0){
		parent::__construct($id, 'russian', 'category');
	}	
}


class ExhibitRussianSummary extends ExhibitText{	

	function __construct($id = 0){
		parent::__construct($id, 'russian', 'summary');
	}	
}

class ExhibitChineseCategory extends ExhibitText{	

	function __construct($id = 0){
		parent::__construct($id, 'chinese', 'category');
	}	
}


class ExhibitChineseSummary extends ExhibitText{	

	function __construct($id = 0){
		parent::__construct($id, 'chinese', 'summary');
	}	
}