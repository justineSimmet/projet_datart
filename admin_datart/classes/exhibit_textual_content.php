<?php
/* ---------------------------
	DatArt
	Creation Date : 20/02/2017
	classes/exhibit_textual_content.php
	author : ApplePie Cie
---------------------------- */

class ExhibitText{
	private $id;
	private $exhibit_id;
	private $content;
	private $language;
	private $subject;

	function __construct($id = 0){
		if($id != 0){
			$res= requete_sql("SELECT * FROM textual_content_exhibit WHERE id = '".$id."' ");
			$exhibit = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $exhibit['id'];
			$this->exhibit_id = $exhibit['exhibit_id'];
			$this->content = $exhibit['content'];
			$this->language = $exhibit['language'];
			$this->subject = $exhibit['subject'];
		}
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

	function setLanguage($language){
		$this->language = $language;
		return TRUE;
	}

	function getLanguage(){
		return $this->language;
	}

	function setSubject($subject){
		$this->subject = $subject;
		return TRUE;
	}

	function getSubject(){
		return $this->subject;
	}
}