<?php 

/* ---------------------------
  DatArt
  Creation Date : 24/03/2017
  classes/artwork.php
  author : ApplePie Cie
---------------------------- */
class Additional{
	private $id;
	private $artwork_id;
	private $name;
	private $format;
	private $target;

	function __construct($id=''){
		if ($id != 0) {
			$res = requete_sql("SELECT * FROM additional_content WHERE id = '".$id."' ");
			$additional = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $additional['id'];
			$this->artwork_id = $additional['artwork_id'];
			$this->name = $additional['name'];
			$this->format = $additional['format'];
			$this->target = $additional['target'];
		}
	}

	function getId(){
		return $this->id;
	}

	function setArtworkId($artwork_id){
		$this->artwork_id = $artwork_id ;
		return TRUE;
	}

	function getArtworkId(){
		return $this->artwork_id;
	}

	function setTarget($target){
		$this->target = $target;
		return TRUE;
	}

	function getTarget(){
		return $this->target;
	}

	function setName($name){
		$this->name = $name;
		return TRUE;
	}

	function getName(){
		return $this->name;
	}

	function setFormat($format){
		$this->format = $format;
		return TRUE;
	}

	function getFormat(){
		return $this->format;
	}

	function synchroDb(){
		if (empty($this->id)) {
			if ($this->format == 'link') {
				$testHttp = stripos($this->target, 'http://');
				$testHttps = stripos($this->target, 'https://');
				if ($testHttp === FALSE && $testHttps === FALSE) {
					$this->target = 'http://'.$this->target;
				}
			}
			$create = requete_sql("INSERT INTO additional_content VALUES(
				NULL,
				'".addslashes($this->artwork_id)."',
				'".addslashes($this->name)."',
				'".addslashes($this->format)."',
				'".addslashes($this->target)."'
				)");
			if ($create) {
				return $create;
			}
			else{
				return FALSE;
			}
		}else{
			if ($this->format == 'link') {
				$testHttp = stripos($this->target, 'http://');
				$testHttps = stripos($this->target, 'https://');
				if ($testHttp === FALSE && $testHttps === FALSE) {
					$this->target = 'http://'.$this->target;
				}
			}
			$update = requete_sql("UPDATE additional_content SET 
				name = '".addslashes($this->name)."',
				target = '".addslashes($this->target)."'
				WHERE id = '".$this->id."' ");
			if ($update) {
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}

	function delete(){
		$delete = requete_sql("DELETE FROM additional_content WHERE id ='".$this->id."' ");
		if ($delete) {
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

}