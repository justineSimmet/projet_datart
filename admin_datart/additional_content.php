<?php
	require_once('classes/user.php');
	require_once('classes/artwork.php');
    require_once('classes/artwork_visual.php');
    require_once('classes/artwork_additional.php');
	require_once('includes/include.php');

    $data['file'] = $_FILES;
    $data['text'] = $_POST;

if (isset($_POST['action']) && $_POST['action'] == 'uploadFiles') {

    $maxSize = 5242880;
    $size = filesize($_FILES['file']['tmp_name']);
    $uploadFormat = array('.pdf','.mp3', '.mp4','.wav');
    $extension = strrchr($_FILES['file']['name'], '.');

	$targetArtwork = new Artwork($_POST['artworkId']);
    $path = __DIR__."\assets\images\artwork\\".$targetArtwork->getId().'\\';
    if(!file_exists($path)){
        $newFolder = mkdir($path, 0755, TRUE);
    }
    if (!empty($_FILES['file'])){
        $uploadTarget = URL_IMAGES."artwork/".$targetArtwork->getId();
        $uploadFile = __DIR__."\assets\images\artwork\\".$targetArtwork->getId().'\\';

        if(!in_array($extension, $uploadFormat)){
            $data['file']['file']['error'] = 'Votre visuel n\'est pas au bon format.';
                echo json_encode($data);
        }
        elseif ($size>$maxSize){
            $data['file']['file']['error'] = 'Votre fichier est trop lourd. Max : 5Mo.';
            echo json_encode($data);
        }
        else{
        	$newAdditional = new Additional();
            $artworkId = $targetArtwork->getId();
            $newAdditional->setArtworkId($artworkId);
            $newAdditional->setName($_FILES['file']['name']);
            $newAdditional->setFormat(str_replace('.', '', $extension));
            $newAdditional->setTarget($uploadTarget.'/'.$newAdditional->getName($_FILES['file']['name']));
            $move = move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile.$newAdditional->getName($_FILES['file']['name']));
            if ($move) {
                $synch = $newAdditional->synchroDb();
                if ($synch) {
                    $data['text']['addId'] = $synch;
                    $data['text']['name'] =  $newAdditional->getName();
                    $data['text']['format'] = $newAdditional->getFormat();
                    $data['text']['target'] = $newAdditional->getTarget();
                    echo json_encode($data);
                }
                else{
                    $data['file']['file']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                    echo json_encode($res);
                }
            }
            else{
                $data['file']['file']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                echo json_encode($res);

            }

        }

    }
}
elseif (isset($_POST['action']) && $_POST['action'] == 'editFiles') {
    $newAdditional = new Additional($_POST['addId']);
    if (empty($_POST['target'])) {
        $newAdditional->setName($_POST['name']);
        $update = $newAdditional->synchroDb();
        if ($update) {
            $res = array ('response'=>'success');
            echo json_encode($res);
        }
        else{
            $res = array ('response'=>'error');
            echo json_encode($res); 
        }
    }
    else{
        $newAdditional->setName($_POST['name']);
        $newAdditional->setTarget($_POST['target']);
        $update = $newAdditional->synchroDb();
        if ($update) {
            $res = array ('response'=>'success', 'target'=>$newAdditional->getTarget());
            echo json_encode($res);
        }
        else{
            $res = array ('response'=>'error');
            echo json_encode($res); 
        }  
    }

}
elseif (isset($_POST['action']) && $_POST['action'] == 'addLink') {
    $newAdditional = new Additional();
    $newAdditional->setArtworkId($_POST['artwork']);
    $newAdditional->setTarget($_POST['target']);
    $newAdditional->setName($_POST['name']);
    $newAdditional->setFormat('link');
    $insert = $newAdditional->synchroDb();
    if ($insert) {
        $res = array ('response'=>'success', 'target'=>$newAdditional->getTarget(), 'name'=>$newAdditional->getName(), 'id'=>$insert);
        echo json_encode($res);
    }
    else{
        $res = array ('response'=>'error');
        echo json_encode($res); 
    }

}
elseif (isset($_POST['action']) && $_POST['action'] == 'deleteFiles') {
    $newAdditional = new Additional($_POST['addId']);
    $delete = $newAdditional->delete();
    if ($delete) {
        $res = array ('response'=>'success');
        echo json_encode($res);
    }
    else{
        $res = array ('response'=>'error');
        echo json_encode($res); 
    }

}