<?php
require 'includes/config.php';
require 'PHPMailer/PHPMailerAutoload.php';

//RECUPERATION DU TEMPLATE MAIL
$mailTemplate = file_get_contents('mail_templates/user_email.html');

//INITIALISATION DES DONNEES UTILISATEUR - Celui qui a été edité
$resetUserName = $_POST['username'];
$resetUserSurname = $_POST['usersurname'];
$resetUserLogin = $_POST['userlogin'];
$resetUserMail = $_POST['usermail'];

//INITIALISATION DES DONNEES Admin - Celui qui a edité
$adminName = $_POST['adminname'];
$adminSurname = $_POST['adminsurname'];
$adminMail = $_POST['adminmail'];

//MESSAGES USER
$titleUser = 'Modification de votre compte DATART Grand Angle';
$textActionUser = 'Bonjour '.$resetUserName.' '.$resetUserSurname.'.';
$textPrincipalUser = 'Votre mot de passe a été réinitialisé par un administrateur.';
$textInformatifUser = 'Il est nécessaire, pour des raisons de sécurité, que vous remplaciez le mot de passe par défaut par celui de votre choix. Merci d\'effectuer rapidement cette action.';

//INSERE LES DONNEES UTILISATEUR DANS LE MAIL
$mailToUser = $mailTemplate;
$mailToUser = str_replace('%TITLE%', $titleUser, $mailToUser);
$mailToUser = str_replace('%TEXTEACTION%', $textActionUser, $mailToUser);
$mailToUser = str_replace('%TEXTEPRINCIPAL%', $textPrincipalUser, $mailToUser);
$mailToUser = str_replace('%TEXTEINFORMATIF%', $textInformatifUser, $mailToUser);

//MESSAGES ADMIN
$titleAdmin = 'Vous avez modifié les données d\'un utilisateur.';
$textActionAdmin = $adminName.' '.$adminSurname.', vous venez de réinitialiser le mot de passe d\'un utilisateur de l\'application DATART Grand Angle.';
$textPrincipalAdmin = $resetUserName.' '.$resetUserSurname.' recevra un e-mail l\'avertissant de ce changement.';
$textInformatifAdmin = 'Il est necéssaire, pour des raisons de sécurité, que vous insistiez auprès de votre utilisateur pour qu\'il effectue rapidement la mise à jour du mot de passe par défaut lors de sa première connexion.';

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
/*$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';*/

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
			'adresse' => $resetUserMail,
			'contact' => "$resetUserName $resetUserSurname",
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