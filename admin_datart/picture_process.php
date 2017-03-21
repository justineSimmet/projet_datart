<?php
	require_once('classes/user.php');
	require_once('classes/artwork.php');
	require_once('classes/artwork_visual.php');
	require_once('includes/include.php');

    $data['file'] = $_FILES;
    $data['text'] = $_POST;

if (isset($_POST['action']) && $_POST['action'] == 'deletePicture') {
   $newVisual  = new Visual($_POST['pictureId']);
   $filename = 'art'.$newVisual->getArtworkId().'_img'.$newVisual->getDisplayOrder().'.';
   $path = __DIR__."\assets\images\artwork\\".$newVisual->getArtworkId().'\\';
   $clear = '';
   foreach (glob($path . $filename . '*') as $file) {
       $clear = unlink($file);
   }
   if ($clear) {
        $delete = $newVisual->delete();
        if ($delete) {
            $res = array ('response'=>'success');
            echo json_encode($res); 
        }
        else{
            $res = array ('response'=>'error');
            echo json_encode($res); 
        }
   }
   else{
        $res = array ('response'=>'error');
        echo json_encode($res); 
   }
}
elseif(isset($_POST['action']) && $_POST['action'] == 'uploadPictures'){
    /*var_dump($_POST);
    var_dump($_FILES);
    var_dump($data);*/
    $maxSize = 2097152;
    $size = filesize($_FILES['file']['tmp_name']);
    $uploadFormat = array('.jpg','.jpeg');
    $extension = strrchr($_FILES['file']['name'], '.');
    $targetArtwork = new Artwork($_POST['artworkId']);
    $path = __DIR__."\assets\images\artwork\\".$targetArtwork->getId().'\\';
    $main = 0;
    if(!empty($targetArtwork->getPictureOne()) ){
        $main++;
    }
    if(!empty($targetArtwork->getPictureTwo())){
        $main++;
    }
    if(!empty($targetArtwork->getPictureThree())){
        $main++;
    }
    $total = requete_sql("SELECT COUNT(id) AS total FROM visual WHERE artwork_id = '".$targetArtwork->getId()."' ");
    $total = $total->fetch(PDO::FETCH_ASSOC);
    $total = $total['total'];
    var_dump($total);

    $last = requete_sql("SELECT display_order FROM visual WHERE artwork_id = '".$targetArtwork->getId()."' ORDER BY display_order DESC LIMIT 1");
    $last = $last->fetch(PDO::FETCH_ASSOC);
    $last = $last['display_order'];
    var_dump($last);
    if($total < 4 && $last < 4){
        $displayNumber = 4;
    }
    elseif ($last == '4' || $last > '4' || $total > '3') {
        $last++;
        $displayNumber = $last++;
    }
    else{
        $last++;
        $displayNumber = $last++;
    }
    
    if(!file_exists($path)){
        $newFolder = mkdir($path, 0755, TRUE);
    }
    if (!empty($_FILES['file'])){
        $uploadTarget = URL_IMAGES."artwork/".$targetArtwork->getId();

        if(!in_array($extension, $uploadFormat)){
            $data['file']['file']['error'] = 'Votre visuel doit être au format jpeg.';
            echo json_encode($data);
        }
        elseif ($size>$maxSize){
            $data['file']['file']['error'] = 'Votre visuel ne doit pas dépasser 2,5 Mo.';
            echo json_encode($data);
        }
        else{
            $namePicture = 'art'.$targetArtwork->getId().'_img'.$displayNumber.$extension;
            $newVisual = new Visual();
            $artworkId = $targetArtwork->getId();
            $newVisual->setArtworkId($artworkId);
            $newVisual->setTarget($uploadTarget.'/'.$namePicture);
            $newVisual->setLegend('');
            $newVisual->setDisplayOrder($displayNumber);
            $move = move_uploaded_file($_FILES['file']['tmp_name'], $path.$namePicture);
            if ($move) {
                $synch = $newVisual->synchroDb();
                if ($synch) {
                    $data['text']['pictureId'] = $synch;
                    echo json_encode($data);
                }
                else{
                    $data['file']['file']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                    echo json_encode($data);
                }
            }
            else{
                $data['file']['file']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                echo json_encode($data);
            }
        }
    }
}
else{
    $maxSize = 2097152;
    $size = filesize($_FILES['file']['tmp_name']);
    $uploadFormat = array('.jpg','.jpeg');
    $extension = strrchr($_FILES['file']['name'], '.');
    if(isset($_POST['action']) && isset($_FILES)){
    	$targetArtwork = new Artwork($_POST['artworkId']);
    	$path = __DIR__."\assets\images\artwork\\".$targetArtwork->getId().'\\';
    	if(!file_exists($path)){
    		$newFolder = mkdir($path, 0755, TRUE);
    	}
    	if (!empty($_FILES['file'])){
    		$uploadTarget = URL_IMAGES."artwork/".$targetArtwork->getId();

    		if(!in_array($extension, $uploadFormat)){
    			$data['file']['file']['error'] = 'Votre visuel doit être au format jpeg.';
    			echo json_encode($data);
    		}
    		elseif ($size>$maxSize){
    			$data['file']['file']['error'] = 'Votre visuel ne doit pas dépasser 2 Mo.';
    			echo json_encode($data);
    		}
    		else{
                if($_POST['action'] == 'add-picture-one'){
                    if (empty($_POST['pictureId'])) {
                        $namePicture = 'art'.$targetArtwork->getId().'_img1'.$extension;
            			$newVisual = new Visual();
            			$artworkId = $targetArtwork->getId();
            			$newVisual->setArtworkId($artworkId);
            			$newVisual->setTarget($uploadTarget.'/'.$namePicture);
            			$newVisual->setLegend($_POST['legend']);
            			$newVisual->setDisplayOrder('1');
                        $move = move_uploaded_file($_FILES['file']['tmp_name'], $path.$namePicture);
                        if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['file']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['file']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                            echo json_encode($data);
                        }
                    }
                    else{
                       $namePicture = 'art'.$targetArtwork->getId().'_img1'.$extension;
                       $newVisual = new Visual($_POST['pictureId']);
                       $newVisual->setLegend($_POST['legend']);
                       $move = move_uploaded_file($_FILES['file']['tmp_name'], $path.$namePicture);
                       if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['file']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                       }
                       else{
                            $data['file']['file']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                            echo json_encode($data);
                       }
                    }
                }
                elseif($_POST['action'] == 'add-picture-two'){
                    if (empty($_POST['pictureId'])) {
                        $namePicture = 'art'.$targetArtwork->getId().'_img2'.$extension;
                        $newVisual = new Visual();
                        $artworkId = $targetArtwork->getId();
                        $newVisual->setArtworkId($artworkId);
                        $newVisual->setTarget($uploadTarget.'/'.$namePicture);
                        $newVisual->setLegend($_POST['legend']);
                        $newVisual->setDisplayOrder('2');
                        $move = move_uploaded_file($_FILES['file']['tmp_name'], $path.$namePicture);
                        if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['file']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['file']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                            echo json_encode($data);
                        }
                    }
                    else{
                        $namePicture = 'art'.$targetArtwork->getId().'_img2'.$extension;
                        $newVisual = new Visual($_POST['pictureId']);
                        $newVisual->setLegend($_POST['legend']);
                        $move = move_uploaded_file($_FILES['file']['tmp_name'], $path.$namePicture);
                        if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['file']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['file']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                            echo json_encode($data);
                        }
                    }
                }
                elseif($_POST['action'] == 'add-picture-three'){
                    if (empty($_POST['pictureId'])) {
                        print_r($size);
                        $namePicture = 'art'.$targetArtwork->getId().'_img3'.$extension;
                        $newVisual = new Visual();
                        $artworkId = $targetArtwork->getId();
                        $newVisual->setArtworkId($artworkId);
                        $newVisual->setTarget($uploadTarget.'/'.$namePicture);
                        $newVisual->setLegend($_POST['legend']);
                        $newVisual->setDisplayOrder('3');
                        $move = move_uploaded_file($_FILES['file']['tmp_name'], $path.$namePicture);
                        var_dump($_FILES);
                        if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['file']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            /*$data['file']['file']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';*/
                            echo json_encode($data);
                        }
                    }
                    else{
                        $namePicture = 'art'.$targetArtwork->getId().'_img3'.$extension;
                        $newVisual = new Visual($_POST['pictureId']);
                        $newVisual->setLegend($_POST['legend']);
                        $move = move_uploaded_file($_FILES['file']['tmp_name'], $path.$namePicture);
                        if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['file']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['file']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                            echo json_encode($data);
                        }
                    }
                }
    		}
    	}
    }
}

    
?>