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
			$this->id = $visual['id'];
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

	function defineDisplayOrderAnnexe($countMain){
		$total = requete_sql("SELECT COUNT(*) AS total FROM visual WHERE artwork_id = '".$this->artwork_id."' ");
		$total = $total->fetch(PDO::FETCH_ASSOC);
		$total = $total['total'];

		$last = requete_sql("SELECT display_order FROM visual WHERE artwork_id = '".$this->artwork_id."' ORDER BY display_order DESC LIMIT 1");
		$last = $last->fetch(PDO::FETCH_ASSOC);
		$last = $last['display_order'];

		if ($countMain == $total ) {
			return 4;
		}
		elseif ($countMain < $total) {
			$last ++;
			return $last;
		}

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
				return $create;
			}
			else{
				return FALSE;
			}
		}else{
			$update = requete_sql("UPDATE visual SET 
				legend = '".addslashes($this->legend)."' 
				WHERE id = '".$this->id."' ");
			if ($update) {
				return $this->id;
			}
			else{
				return FALSE;
			}
		}
	}

	function delete(){
		$delete = requete_sql("DELETE FROM visual WHERE id ='".$this->id."' ");
		if ($delete) {
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

}