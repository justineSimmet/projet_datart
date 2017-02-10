<?php
require 'includes/config.php';
require 'PHPMailer/PHPMailerAutoload.php';

//RECUPERATION DU TEMPLATE MAIL
$mailTemplate = file_get_contents('mail_templates/user_email.html');

//INITIALISATION DES DONNEES UTILISATEUR - Celui qui a été edité
$updateUserName = $_POST['username'];
$updateUserSurname = $_POST['usersurname'];
$updateUserLogin = $_POST['userlogin'];
$updateUserMail = $_POST['usermail'];

//INITIALISATION DES DONNEES Admin - Celui qui a edité
$adminName = $_POST['adminname'];
$adminSurname = $_POST['adminsurname'];
$adminMail = $_POST['adminmail'];

//MESSAGES USER
$titleUser = 'Modification de votre compte DATART Grand Angle';
$textActionUser = 'Bonjour '.$updateUserName.' '.$updateUserSurname.'.';
$textPrincipalUser = 'Des modifications sur votre compte '.$updateUserLogin.' ont été effectuées.';
$textInformatifUser = 'Désormais voici vos nouvelles informations: <br/ ><b>Login : '.$updateUserLogin.'</b><br/><b>Prénom : '.$updateUserName.'</b><br/><b>Nom :'.$updateUserSurname.'</b><br/><b>E-mail : '.$updateUserMail.'</b>';

//INSERE LES DONNEES UTILISATEUR DANS LE MAIL
$mailToUser = $mailTemplate;
$mailToUser = str_replace('%TITLE%', $titleUser, $mailToUser);
$mailToUser = str_replace('%TEXTEACTION%', $textActionUser, $mailToUser);
$mailToUser = str_replace('%TEXTEPRINCIPAL%', $textPrincipalUser, $mailToUser);
$mailToUser = str_replace('%TEXTEINFORMATIF%', $textInformatifUser, $mailToUser);

//MESSAGES ADMIN
$titleAdmin = 'Vous avez modifié les données d\'un utilisateur.';
$textActionAdmin = $adminName.' '.$adminSurname.', vous venez de modifier un utilisateur de l\'application DATART Grand Angle.';
$textPrincipalAdmin = $updateUserName.' '.$updateUserSurname.' recevra un mail avec les modifications que vous avez apportées à son compte.';
$textInformatifAdmin = 'Désormais voici les nouvelles informations de '.$updateUserName.' '.$updateUserSurname.': <br/ ><b>Login : '.$updateUserLogin.'</b><br/><b>Prénom : '.$updateUserName.'</b><br/><b>Nom :'.$updateUserSurname.'</b><br/><b>E-mail : '.$updateUserMail.'</b>';

//INSERE LES DONNEES UTILISATEUR DANS LE MAIL
$mailToAdmin = $mailTemplate;
$mailToAdmin = str_replace('%TITLE%', $titleAdmin, $mailToAdmin);
$mailToAdmin = str_replace('%TEXTEACTION%', $textActionAdmin, $mailToAdmin);
$mailToAdmin = str_replace('%TEXTEPRINCIPAL%', $textPrincipalAdmin, $mailToAdmin);
$mailToAdmin = str_replace('%TEXTEINFORMATIF%', $textInformatifAdmin, $mailToAdmin);

/****************************************
**
** INITIALISATION DE LA CONNEXION
**
*****************************************/
//Mise en place de l'objet PHPMailer
$mail = new PHPMailer;

$mail->CharSet = "UTF-8";

$mail->isSMTP();
// $mail->SMTPDebug = 2;
// $mail->Debugoutput = 'html';

//Initialisation des paramètres Gmail
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = DATART_ADDRESS;
$mail->Password = DATART_PWD;

//Adresse de l'expéditeur
$mail->setFrom(DATART_ADDRESS, 'Administration DATART');

//Initialise tableau avec les destinataires
$destinataires = array(
	'user' => array(
			'adresse' => $updateUserMail,
			'contact' => "$updateUserName $updateUserSurname",
			'subject' => 'Modification de votre compte DATART Grand Angle',
			'mailBody' => $mailToUser,
		),
	'admin'=> array(
			'adresse' => $adminMail,
			'contact' => "$adminName $adminSurname",
			'subject' => 'Vous venez de modifier un utilisateur',
			'mailBody' => $mailToAdmin,
		),
);

$loopResult = 0;

foreach ($destinataires as $d) {
	$mail->addAddress($d['adresse'], $d['contact']);
	$mail->Subject = $d['subject'];
	$mail->msgHTML($d['mailBody']);
	
	if (!$mail->send()) {
		$loopResult ++;
	}
	

	$mail->ClearAllRecipients();
}

 
if ($loopResult !== 0) {
	$res = array ('response'=>'error');
	echo json_encode($res);
}
else{
	$res = array ('response'=>'success');
	echo json_encode($res);	
}