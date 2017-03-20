<?php 

/* ---------------------------
  DatArt
  Creation Date : 20/03/2017
  classes/artwork.php
  author : ApplePie Cie
---------------------------- */
class Visual{
	private $id;
	private $artwork_id;
	private $target;
	private $legend;
	private $display_order;

	function __construct($id=''){
		if ($id != 0) {
			$res = requete_sql("SELECT * FROM visual WHERE id = '".$id."' ");
			$visual = $res->fetch(PDO::FETCH_ASSOC);
			$this->artwork_id = $visual['artwork_id'];
			$this->target = $visual['target'];
			$this->legend = $visual['legend'];
			$this->display_order = $visual['display_order'];
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

	function setLegend($legend){
		$this->legend = $legend;
		return TRUE;
	}

	function getLegend(){
		return $this->legend;
	}

	function setDisplayOrder($display_order){
		$this->display_order = $display_order;
		return TRUE;
	}

	function getDisplayOrder(){
		return $this->display_order;
	}

	function synchroDb(){
		if (empty($this->id)) {
			$create = requete_sql("INSERT INTO visual VALUES(
				NULL,
				'".addslashes($this->artwork_id)."',
				'".addslashes($this->target)."',
				'".addslashes($this->legend)."',
				'".addslashes($this->display_order)."'
				)");
			if ($create) {
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}

}