<?php
	require_once('classes/user.php');
	require_once('classes/artwork.php');
	require_once('classes/artwork_visual.php');
	require_once('includes/include.php');


    $data['file'] = $_FILES;
    $data['text'] = $_POST;
    $maxSize = 2097152;
    $size = filesize($_FILES['image']['tmp_name']);
    $uploadFormat = array('.jpg','.jpeg');
    $extension = strrchr($_FILES['image']['name'], '.');
 
    if(isset($_POST['action']) && isset($_FILES)){
    	$targetArtwork = new Artwork($_POST['artworkId']);
    	$path = __DIR__."\assets\images\artwork\\".$targetArtwork->getId();
    	var_dump($path);
    	if(!file_exists($path)){
    		$newFolder = mkdir($path, 0755, TRUE);
    		if (!$newFolder) {
    			echo "NAN";
    		}
    	}
    	if ($_POST['action'] == 'add-picture-one'){
    		$namePicture = 'art'.$targetArtwork->getId().'_img001'.$extension;
    		$uploadTarget = URL_IMAGES."artwork/".$targetArtwork->getId();
    		$uploadFile = __DIR__."\assets\images\artwork\\".$targetArtwork->getId().'\\';

    		if(!in_array($extension, $uploadFormat)){
    			$data['file']['image']['error'] = 'Votre visuel doit être au format jpeg.';
    			echo json_encode($data);
    		}
    		elseif ($size>$maxSize) {
    			$data['file']['image']['error'] = 'Votre visuel ne doit pas dépasser 2,5 Mo.';
    			echo json_encode($data);
    		}
    		else{
    			$newVisual = new Visual();
    			$artworkId = $targetArtwork->getId();
    			$newVisual->setArtworkId($artworkId);
    			$newVisual->setTarget($uploadTarget.'/'.$namePicture);
    			$newVisual->setLegend($_POST['legend']);
    			$newVisual->setDisplayOrder('1');
    			$synch = $newVisual->synchroDb();
    			if($synch){
	    			$move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
	    			if ($move) {
	    				echo json_encode($data);
    				}
    				else{
    					$data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
    					echo json_encode($data);
    				}
    			}
    			else{
    				$data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
    				echo json_encode($data);
    			}
    		}
    	}
    }

    
?>