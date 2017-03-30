<?php
	require_once('classes/user.php');
	require_once('classes/exhibit.php');
	require_once('classes/exhibit_textual_content.php');
	require_once('classes/event.php');
	require_once('classes/artist.php');
	require_once('classes/artwork.php');
    require_once('classes/artwork_visual.php');
    require_once('classes/artwork_additional.php');
    require_once('classes/artwork_textual_content.php');
	require_once('includes/include.php');


if (isset($_POST)) {
	if (isset($_POST['action']) && $_POST['action'] == 'save') {
		$targetExhibit = new Exhibit($_POST['target']);
		$targetExhibit->setZoning($_POST['data']);
		$synchro = $targetExhibit->zoningManagement('insert');
		if ($synchro) {
			$res = array ('response'=>'success');
            echo json_encode($res); 
		}
		else{
			$res = array ('response'=>'error ');
            echo json_encode($res); 
		}
	}
	elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
		$targetExhibit = new Exhibit($_POST['target']);
		$synchro = $targetExhibit->zoningManagement('delete');
		if ($synchro) {
			$res = array ('response'=>'success');
            echo json_encode($res); 
		}
		else{
			$res = array ('response'=>'error ');
            echo json_encode($res); 
		}
	}
	elseif (isset($_POST['action']) && $_POST['action'] == 'imageCreate') {
		$name = 'exhibit'.$_POST['target'];
		$data = $_POST['data'];
		$path = __DIR__."\assets\images\\exhibit\\";
    	if(!file_exists($path)){
        	$newFolder = mkdir($path, 0755, TRUE);
    	}
    	file_put_contents($path.$name.'.jpg', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data)));
	}
}


?>